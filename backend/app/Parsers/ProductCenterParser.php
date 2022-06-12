<?php

namespace App\Parsers;

use App\Abstractions\AbstractParser;
use App\Exceptions\ValidationException;
use App\Models\DataSource;
use App\ValueObjects\CompanyFromParserValueObject;
use App\ValueObjects\CompanyGoodFromParserValueObject;
use Carbon\Carbon;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Collection;
use JsonException;
use Throwable;

/**
 * @description Парсинг информации с сайта "Интернет-выставка Производство России"
 */
class ProductCenterParser extends AbstractParser {

  protected static string $base_url = 'https://productcenter.ru';
  protected static string $search_url = '/search/r-moskovskaia-obl-191?q=#query#&filter=producers&ajax=1&page=#page_number#';
  protected Collection $producers;
  private DataSource $data_source;
  private int $limit_rows;

  /**
   * Инициализация парсера
   */
  public function __construct(int $limit_rows = 0) {
    parent::__construct();

    $this->data_source = DataSource::where('canonical_name', 'productcenter_ru')->first();
    $this->limit_rows = $limit_rows;
    $this->producers = collect();
  }

  /**
   * @param string $query
   * @return Collection of CompanyFromParserValueObject
   * @throws GuzzleException
   */
  public function parse(string $query = ''): Collection {
    for ($page = 1; $page <= 40; $page++) {
      if ($this->limit_rows > 0 && $this->producers->count() >= $this->limit_rows) {
        break;
      }

      $url = self::$base_url . str_replace('#query#', $query, self::$search_url);
      $url = str_replace('#page_number#', $page, $url);

      try {
        $response = $this->client->request('GET', $url);
      } catch (ServerException $e) {
        // очередная страница не существует, превышен лимит страниц на стороне поставщика информации
        break;
      }

      if ($response->getStatusCode() === 200) {
        $response_json = json_decode($response->getBody()->getContents());

        $html_content = trim($response_json->items, "\n");
        $html_content = trim($html_content);

        if (empty($html_content)) {
          // очередная страница не существует
          break;
        }

        // try {
        $search_page_document = new Document($html_content);

        $this->parsePage($search_page_document);
        // } catch (Throwable $e) {
        //   //dd($page, $response_json);
        // }
      }
    }

    return $this->producers;
  }

  /**
   * Парсинг одной страницы списка производителей
   *
   * @param Document $search_page_document
   * @return void
   * @throws ValidationException
   * @throws InvalidSelectorException
   * @throws GuzzleException
   * @throws JsonException
   */
  private function parsePage(Document $search_page_document): void {
    foreach ($search_page_document->find('div.card_item') as $result_text) {
      // для ускорения запросов можно не вытаскивать все, а ограничиваться каким-то лимитом
      if ($this->limit_rows > 0 && $this->producers->count() >= $this->limit_rows) {
        break;
      }
      $producer_id = $result_text->first('.to_favorites')->attr('data-item-id');

      // TODO: проверить, возможно это изображение брать надёжнее чем из галереи компании
      $image_path = $result_text->first('.image img')->attr('src');

      $producer_page_path = $result_text->first('.text .link')->attr('href');

      // переходим на страницу производителя
      $res = $this->client->request('GET', self::$base_url . $producer_page_path);
      $response_html_producer_page = $res->getBody()->getContents();

      // получаем координаты точки на карте
      $longitude_center = null;
      $latitude_center = null;

      preg_match('/center: \[(.*?), (.*?)\]/ui', $response_html_producer_page, $found);
      if (count($found)) {
        [, $longitude_center, $latitude_center] = $found;
        $longitude_center = (float)$longitude_center;
        $latitude_center = (float)$latitude_center;
      }

      $longitude = null;
      $latitude = null;

      preg_match('/coordinates: \[(.*?), (.*?)\]/ui', $response_html_producer_page, $found);
      if (count($found)) {
        [, $longitude, $latitude] = $found;
        $longitude = (float)$longitude;
        $latitude = (float)$latitude;
      }

      $producer_page = new Document($response_html_producer_page);

      // фотографии галереи и логотип
      $photos_urls = [];
      $logo_url = null;
      foreach ($producer_page->find('.imgs_slider li') as $i => $li_node) {
        $photo_href = $li_node->attr('href');

        // логотип
        if ($i === 0) {
          $logo_url = self::$base_url . $photo_href;

          continue;
        }

        // фотографии галереи
        if (strripos($photo_href, 'images') !== false) {
          $photos_urls[] = self::$base_url . $photo_href;
        }
      }

      // название компании
      $producer_name = $producer_page->first('.tc_contacts meta[itemprop="name"]')->attr('content');

      // описание компании
      $producer_description = $producer_page->first('.iv_bottom .iv_text')->text();

      // ключевые слова для поиска компании
      $keywords_for_search = [];
      foreach ($producer_page->find('.crumbs_list li meta[itemprop="name"]') as $i => $keyword_node) {
        if ($i > 2) {
          $keywords_for_search[] = $keyword_node->attr('content');
        }
      }

      $email = $producer_page->first('.tc_contacts span[itemprop="email"]')?->text();
      $phone = $producer_page->first('.tc_contacts span[itemprop="telephone"]')?->text();
      $site = $producer_page->first('.tc_contacts a[itemprop="url"]')?->text();

      // фактический адрес
      $address_region = $producer_page->first('.tc_contacts span[itemprop="addressRegion"]')?->text();
      $address_locality = $producer_page->first('.tc_contacts span[itemprop="addressLocality"]')?->text();
      $street_address = $producer_page->first('.tc_contacts span[itemprop="streetAddress"]')?->text();

      if (empty($street_address)) {
        $actual_address = implode(', ', [$address_region, $address_locality]);
      } else {
        $actual_address = implode(', ', [$address_region, $address_locality, $street_address]);
      }

      $legal_address = '';

      // ОГРН
      $orgn = '';

      // ИНН
      $inn = '';

      // КПП
      $kpp = '';

      // количество сотрудников
      $number_of_employees = 0;

      // уставной капитал
      $authorized_capital = 0;

      // дата регистрации
      $registration_date = '';

      // реквизиты компании
      foreach ($producer_page->find('.tc_contacts .company_data tr') as $tr_node) {
        [$property_name_node, $property_value_node] = $tr_node->find('td');

        if ($property_name_node->text() === 'Наименование') {
          $producer_name = $property_value_node->text();
        }

        if ($property_name_node->text() === 'ОГРН') {
          $orgn = $property_value_node->text();
        }

        if ($property_name_node->text() === 'ИНН') {
          $inn = $property_value_node->text();
        }

        if ($property_name_node->text() === 'КПП') {
          $kpp = $property_value_node->text();
        }

        if ($property_name_node->text() === 'Юридический адрес') {
          $legal_address = $property_value_node->text();
        }

        if ($property_name_node->text() === 'Дата регистрации') {
          $registration_date = Carbon::parse($property_value_node->text())->format('Y-m-d');
        }

        if ($property_name_node->text() === 'Уставной капитал') {
          $authorized_capital = (float)preg_replace('~\D+~', '', $property_value_node->text());
        }

        if ($property_name_node->text() === 'Сотрудники') {
          $number_of_employees = $property_value_node->text();
        }
      }


      // Основной ОКВЭД (вид деятельности)
      $general_activity = null;

      // Дополнительные ОКВЭД (виды деятельности)
      $activities = [];

      // виды деятельности
      foreach ($producer_page->find('.tc_contacts .company_activities span') as $i => $node) {
        if ($i === 0) {
          $general_activity = $node->text();
        } else {
          if ($node->attr('class') === 'hidden' && !empty($node->text())) {
            $activities[] = $node->text();
          }
        }
      }

      $goods_url = self::$base_url . $producer_page->first('.iv_side_block')
          ->first('b a')->attr('href');

      $goods = collect();
      if (!empty($goods_url)) {
        $goods = $this->parseProducerGoodsPage($goods_url);
      }

      $producer_vo = new CompanyFromParserValueObject([
        'data_source_id' => $this->data_source->id,
        'data_source_item_id' => $producer_id,
        'data_source_item_url' => $producer_page_path,
        'name' => $producer_name,
        'description' => $producer_description,
        'logo_url' => $logo_url,
        'photos_urls' => $photos_urls,
        'keywords_for_search' => $keywords_for_search,
        'email' => $email,
        'phone' => $phone,
        'site' => $site,
        'actual_address' => $actual_address,
        'legal_address' => $legal_address,
        'number_of_employees' => $number_of_employees,
        'authorized_capital' => $authorized_capital,
        'registration_date' => $registration_date,
        'orgn' => $orgn,
        'inn' => $inn,
        'kpp' => $kpp,

        'longitude_center' => $longitude_center,
        'latitude_center' => $latitude_center,
        'longitude' => $longitude,
        'latitude' => $latitude,

        'general_activity' => $general_activity,
        'activities' => $activities,
        'goods' => $goods,
      ]);


      // сохранение логотипа и фотографий галереи
      // $counterparty = Counterparty::where('id', 100)->first();
      // $counterparty->saveLogoFromUrl($producer_vo->logo_url);
      // $counterparty->savePhotosFromUrlArray($producer_vo->photos_urls);

      $this->producers->push($producer_vo);
    }
  }

  /**
   * Парсинг страницы товаров производителя
   *
   * @param string $goods_url
   * @return Collection
   * @throws GuzzleException
   * @throws InvalidSelectorException
   * @throws JsonException
   * @throws ValidationException
   */
  private function parseProducerGoodsPage(string $goods_url): Collection {
    $goods = collect();

    // переходим на страницу товаров производителя
    $response = $this->client->request('GET', $goods_url);
    $response_html_producer_goods_page = $response->getBody()->getContents();
    $producer_goods_page = new Document($response_html_producer_goods_page);

    $cards = $producer_goods_page->first('.cards');
    foreach ($cards->find('.card_item') as $card_node) {
      $data_source_item_id = $card_node->first('.to_favorites')->attr('data-item-id');
      $image_url = self::$base_url . $card_node->first('.image img')->attr('src');

      $good_page_url = self::$base_url . $card_node->first('.image a')->attr('href');

      // переходим на страницу товара
      $response_good_page = $this->client->request('GET', $good_page_url);
      $response_html_good_page = $response_good_page->getBody()->getContents();
      $good_page = new Document($response_html_good_page);

      // ключевые слова для поиска компании
      $keywords_for_search = [];
      foreach ($good_page->find('.crumbs_list li meta[itemprop="name"]') as $i => $keyword_node) {
        if ($i > 2) {
          $keywords_for_search[] = $keyword_node->attr('content');
        }
      }

      $good_node = $good_page->first('.item_view');

      $photos_urls = [];
      foreach ($good_node->find('li[data-fancybox="main-photos"]') as $image_node) {
        $photos_urls[] = $image_node->attr('href');
      }

      $name = $good_page->first('.iv_content h1[itemprop="name"]')?->text();
      $description = $good_page->first('.iv_bottom .tc_description div[itemprop="description"]')?->text();
      $price = $good_page->first('.iv_content .iv_main_block meta[itemprop="price"]')?->attr('content');
      $price_description = $good_page->first('.iv_content .iv_main_block div[class="price"]')?->text();
      $price_min_party = $good_page->first('.iv_content .iv_main_block span[class="min_party"]')?->text();


      $last_edit = '';
      //2022-03-15 16:00:34
      // $last_edit = $good_page->first('.iv_bottom .last_edit')?->text();
      //
      // preg_match('/([^0-9]-[^0-9]-[^0-9] [^0-9]:[^0-9]:[^0-9])/ui', $last_edit, $found);
      // if (count($found)) {
      //   dd($found);
      // }



      $good = new CompanyGoodFromParserValueObject([
        'data_source_id' => $this->data_source->id,
        'data_source_item_id' => $data_source_item_id,
        'data_source_item_url' => $good_page_url,
        'data_source_item_last_edit' => $last_edit,
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'price_description' => $price_description,
        'price_min_party' => $price_min_party,
        'photos_urls' => $photos_urls,
        'keywords_for_search' => $keywords_for_search,
      ]);

      $goods->push($good);
    }

    return $goods;
  }
}
