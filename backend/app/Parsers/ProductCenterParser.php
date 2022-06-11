<?php

namespace App\Parsers;

use App\Abstractions\AbstractParser;
use App\Models\Counterparty;
use App\ValueObjects\CompanyFromParserValueObject;
use Carbon\Carbon;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

/**
 * @description Парсинг информации с сайта "Интернет-выставка Производство России"
 */
class ProductCenterParser extends AbstractParser {

  protected static string $base_url = 'https://productcenter.ru';
  protected static string $search_url = '/search/r-moskovskaia-obl-191?q=#query#&filter=producers';

  /**
   * @param string $query
   * @return Collection of CompanyFromParserValueObject
   * @throws InvalidSelectorException
   * @throws GuzzleException
   */
  public function parse(string $query = ''): Collection {
    $producers = collect();

    $url = self::$base_url . str_replace('#query#', $query, self::$search_url);

    $res_json = $this->client->request('GET', $url);
    $response_html = $res_json->getBody()->getContents();
    $search_page_document = new Document($response_html);

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

      $email = $producer_page->first('.tc_contacts span[itemprop="email"]')->text();
      $phone = $producer_page->first('.tc_contacts span[itemprop="telephone"]')->text();
      $site = $producer_page->first('.tc_contacts a[itemprop="url"]')->text();

      // фактический адрес
      $address_region = $producer_page->first('.tc_contacts span[itemprop="addressRegion"]')->text();
      $address_locality = $producer_page->first('.tc_contacts span[itemprop="addressLocality"]')->text();
      $street_address = $producer_page->first('.tc_contacts span[itemprop="streetAddress"]')->text();

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
      ]);


      // сохранение логотипа
      // $counterparty = Counterparty::where('id', 100)->first();
      // $counterparty->saveLogoFromUrl($producer_vo->logo_url);
      // $counterparty->savePhotosFromUrlArray($producer_vo->photos_urls);

      dd($producer_vo);
      $producers->push($producer_vo);
    }

    return $producers;
  }
}
