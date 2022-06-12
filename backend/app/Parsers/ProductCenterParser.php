<?php

namespace App\Parsers;

use App\Abstractions\AbstractParser;
use App\Exceptions\ValidationException;
use App\Models\Counterparty;
use App\ValueObjects\CompanyFromParserValueObject;
use Carbon\Carbon;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use JsonException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;

/**
 * @description Парсинг информации с сайта "Интернет-выставка Производство России"
 */
class ProductCenterParser extends AbstractParser {

  protected static string $base_url = 'https://productcenter.ru';
  protected static string $search_url = '/search/r-moskovskaia-obl-191?q=#query#&filter=producers&ajax=1&page=#page_number#';
  protected Collection $producers;

  /**
   * Инициализация парсера
   */
  public function __construct() {
    parent::__construct();

    $this->producers = collect();
  }

  /**
   * @param string $query
   * @return Collection of CompanyFromParserValueObject
   * @throws InvalidSelectorException
   * @throws GuzzleException
   */
  public function parse(string $query = ''): Collection {
    for ($page = 1; $page <= 15; $page++) {
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

        try {
          $search_page_document = new Document($html_content);

          $this->parsePage($search_page_document);
        } catch (Throwable $e) {
          dd($page, $response_json);
        }
      }
    }

    return $this->producers;
  }

  /**
   * @param Document $search_page_document
   * @return Collection
   * @throws GuzzleException
   * @throws InvalidSelectorException
   * @throws ValidationException
   * @throws JsonException
   */
  private function parsePage(Document $search_page_document) {
    foreach ($search_page_document->find('div.card_item') as $result_text) {
      $producer_id = $result_text->first('.to_favorites')->attr('data-item-id');

      // TODO: проверить, возможно это изображение брать надёжнее чем из галереи компании
      $image_path = $result_text->first('.image img')->attr('src');

      $producer_page_path = $result_text->first('.text .link')->attr('href');

      // переходим на страницу производителя
      $res = $this->client->request('GET', self::$base_url . $producer_page_path);
      $response_html_producer_page = (string)$res->getBody();

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


      // виды деятельности - делятся на 2 типа (Основной ОКВЭД, Дополнительные ОКВЭД)
      $activities = [];

      $producer_vo = new CompanyFromParserValueObject([
        //'data_source_id' => $data_source_id,
        'data_source_item_id' => $producer_id,
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
      ]);


      // сохранение логотипа
      // $counterparty = Counterparty::where('id', 100)->first();
      // $counterparty->saveLogoFromUrl($producer_vo->logo_url);
      // $counterparty->savePhotosFromUrlArray($producer_vo->photos_urls);

      $this->producers->push($producer_vo);
    }
  }
}
