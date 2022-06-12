<?php

namespace App\Http\Controllers;

use App\Parsers\OptParser;
use App\Parsers\ProductCenterParser;

class SearchController extends Controller {

  private const PARSERS = [
    ProductCenterParser::class,
    // OptParser::class, // todo дописать
  ];

  private array $parsers;

  public function __construct() {
    $parsers = [];
    foreach (self::PARSERS as $parser) {
      $parsers[] = new $parser;
    }

    $this->parsers = $parsers;
  }

  /**
   * Возвращает коллекцию объектов поиска
   *
   * @param string $query
   * @return array
   */
  public function search(string $query): array {

    $rows = [];
    foreach ($this->parsers as $parser) {
      $rows = [...$rows, $parser->parse($query)];
    }

    $this->saveResult($rows);

    return $rows;
  }

  /**
   * Сохранение результатов парсинга. На входе массив CompanyFromParserValueObject
   *
   * @param array $rows
   * @return void
   * @see \App\ValueObjects\CompanyFromParserValueObject
   */
  public function saveResult(array $rows) {
    foreach ($rows as $row) {
      /**
       * @var \App\ValueObjects\CompanyFromParserValueObject $row
       */

      foreach ($rows->goods as $good) {
        /**
         * @var \App\ValueObjects\CompanyGoodFromParserValueObject
         */

      }
    }
  }
}
