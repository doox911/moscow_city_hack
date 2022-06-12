<?php

namespace App\Parsers;

use App\Abstractions\AbstractParser;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;

class OptParser extends AbstractParser {

  protected static string $site_url = 'https://optlist.ru';
  protected static string $base_url = 'https://optlist.ru/manufacturers/_/moskva--524901?country=2017370&q=#query#';

  /**
   * @param string $query
   * @return Collection
   * @throws InvalidSelectorException
   * @throws GuzzleException
   */
  public function parse(string $query = ''): Collection {
    $producers = collect();

    $url = str_replace('#query#', $query, self::$base_url);

    $res_json = $this->client->request('GET', $url);

    $response_html = $res_json->getBody()->getContents();


    $search_page_document = new Document($response_html);

    // todo найти элементы пагинации и пройтись по всем страницам набрать ссылки производителей

    $founded_links = collect();
    foreach ($search_page_document->find('div.card-body') as $i => $node) {
      if ($i == 0 || $node->has('.ad_content')) {
        continue;
      };

      $node_url = $node->first('div > div > div > div > div > a');
      if ($node_url) {
        $url = $node_url->attr('href');
        $founded_links->push($url);
      }
    }

    // парсим найденные ссылки и собираем результаты в объекты
    // todo take 5 - оптимизация скорости ответа
    foreach ($founded_links->take(5)->unique()->values() as $link) {
      $res = $this->client->request('GET', static::$site_url . $link);
      $response_html = (string)$res->getBody();

      dd($response_html);
      $article_page_document = new Document($response_html);

      $description = $article_page_document->first("meta[name='Description']");

      if (!$description) {
        $description = $article_page_document->first("meta[name='description']");
      }

      if (!$description) {
        continue;
      }


      $article = (object)[
        'title' => $article_page_document->first('title')->text(),
        'content' => $description->attr('content'),
        'type' => 'text',
        'source' => $link,
        'source_type' => strtolower(self::class),
      ];

      $articles->push($article);
    }

    return $articles;
  }
}
