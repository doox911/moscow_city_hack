<?php

namespace App\Parsers;

use App\Abstractions\AbstractParser;
use App\ValueObjects\CompanyFromParserValueObject;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

/**
 * @description Парсинг информации с сайта "Интернет-выставка Производство России"
 */
class ProductCenterParser extends AbstractParser {

  protected static string $base_url = 'https://productcenter.ru';
  protected static string $search_url = '/search/r-moskovskaia-obl-191?q=#query#&filter=producers';

  /**
   * @param string $query
   * @return Collection
   * @throws InvalidSelectorException
   * @throws GuzzleException
   */
  public function parse(string $query = ''): Collection {
    $producers = collect();

    $url = self::$base_url . str_replace('#query#', $query, self::$search_url);

    $res_json = $this->client->request('GET', $url);

    $response_html = $res_json->getBody()->getContents();

    $search_page_document = new Document($response_html);

    $founded_links = collect();
    foreach ($search_page_document->find('div.card_item') as $result_text) {
      $produced_id = $result_text->first('.to_favorites')->attr('data-item-id');
      $image_path = $result_text->first('.image img')->attr('src');
      $producer_name = $result_text->first('.text .link')->text();
      $producer_page_path = $result_text->first('.text .link')->attr('href');
      //$producer_page_path = urldecode($result_text->first('.text .link')->attr('href'));
      $city_name = $result_text->first('.item_info .ii_city a')->text();


      $res = $this->client->request('GET', self::$base_url . $producer_page_path);
      $response_html_producer_page = (string)$res->getBody();

      $producer_page = new Document($response_html_producer_page);


      $producer_description = $producer_page->first('.iv_bottom .iv_text')->text();


      $producer_vo = new CompanyFromParserValueObject([
        'description' => $producer_description,
      ]);
dd($producer_vo);


      //$producers->push($url);
    }


    return $producers;
  }
}
