<?php

namespace App\Http\Controllers;

use App\Parsers\ProductCenterParser;
use App\ValueObjects\CompanyFromParserValueObject;

class ParseController extends Controller {

  private const PARSERS = [
    ProductCenterParser::class,
    // OptParser::class, // todo дописать
  ];

  private array $parsers;

  /**
   * Конструктор контроллера счета
   */
  public function __construct() {
    $parsers = [];
    foreach (self::PARSERS as $parser) {
      $parsers[] = new $parser;
    }

    $this->parsers = $parsers;
  }

  /**
   *
   *
   * @param string $query
   * @return void
   */
  public function parse(string $query) {

    $rows = [];
    foreach ($this->parsers as $parser) {
      $rows = [...$rows, $parser->parse($query)->toArray()];
    }

    $inns = array_map(function (CompanyFromParserValueObject $row) {
      return $row->inn;
    }, $rows);

    dd($inns);

    $this->saveResult($rows);
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
