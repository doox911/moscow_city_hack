<?php

namespace App\Http\Controllers;

use App\Models\Counterparty;
use App\Models\Good;
use App\Parsers\FNSParser;
use App\Parsers\ProductCenterParser;
use App\ValueObjects\CompanyFromParserValueObject;
use App\ValueObjects\CompanyGoodFromParserValueObject;
use Illuminate\Support\Facades\Cache;

class ParseController extends Controller {

  private const PARSERS = [
    ProductCenterParser::class,
    // OptParser::class, // todo дописать
  ];

  private array $parsers;

  /**
   * Конструктор контроллера
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
    // сохраняем пометку в кеш о том что уже производится парсинг от имени текущего пользователя
    Cache::forever('parsing_' . request()->user()->id, true);

    $rows = [];
    $inns = [];
    $names = [];
    $fns = new FNSParser;

    foreach ($this->parsers as $parser) {
      foreach ($parser->parse() as $company) {
        /**
         * @var CompanyFromParserValueObject $company
         */

        // ищем ИНН компании в ФНС по
        if (empty($company->inn)) {
          $fns_company = $fns->search($company->name);
          $inns[] = $fns_company->{'ИНН'};

          Counterparty::updateOrCreate([
            'inn' => $fns_company->{'ИНН'}
          ], [
            'data_source_id' => '',
            'data_source_item_id' => '',
            'user_id' => '',
            'name' => '',
            'full_name' => '',
            'inn' => '',
            'ogrn' => '',
            'address' => '',
            'email' => '',
            'phone' => '',
            'site' => '',

            'longitude_center' => '',
            'latitude_center' => '',
            'longitude' => '',
            'latitude' => '',
          ]);
        } else {
          $inns[] = $company->inn;

          Counterparty::updateOrCreate([
            'inn' => $company->inn
          ], [
            'data_source_id' => '',
            'data_source_item_id' => '',
            'user_id' => '',
            'name' => '',
            'full_name' => '',
            'inn' => '',
            'ogrn' => '',
            'address' => '',
            'email' => '',
            'phone' => '',
            'site' => '',

            'longitude_center' => '',
            'latitude_center' => '',
            'longitude' => '',
            'latitude' => '',
          ]);
        }

        foreach ($company->goods as $good) {
          /**
           * @var CompanyGoodFromParserValueObject $good
           */

          Good::create([
            'data_source_id' => '',
            'data_source_item_id' => '',
            'brand' => '',
            'name' => '',
            'description' => '',
            'price' => '',
            'price_description' => '',
            'keyword_for_search' => '',
            'data_source_item_url' => '',
            'data_source_item_last_edit' => '',
            'price_min_party' => '',
            'properties' => '',
          ]);

        }
      }
    }

    // $additional_companies_info = $fns->searchGroupInfo($inns);
    //
    // foreach ($additional_companies_info as $item) {
    //
    // }

    // после завершения парсинга убираем флаг
    Cache::forget('parsing_' . request()->user()->id);

    dd($inns);

  }
}
