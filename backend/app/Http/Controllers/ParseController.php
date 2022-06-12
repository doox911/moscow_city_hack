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
  public function parse(string $query = '') {
    // сохраняем пометку в кеш о том что уже производится парсинг от имени текущего пользователя
    Cache::forever('parsing_' . request()->user()->id, true);

    $inns = [];
    $fns = new FNSParser;

    foreach ($this->parsers as $parser) {
      foreach ($parser->parse() as $company) {
        /** @var CompanyFromParserValueObject $company */

        // ищем ИНН компании в ФНС по
        if (empty($company->inn)) {
          $fns_company = $fns->search($company->name);
          $inns[] = $fns_company->{'ИНН'};

          $founded_inn = $fns_company->{'ИНН'};
        } else {
          $inns[] = $company->inn;

          $founded_inn = $company->inn;
        }

        Counterparty::updateOrCreate([
          'inn' => $founded_inn,
        ], [
          'data_source_id' => $company->data_source_id,
          'data_source_item_id' => $company->data_source_item_id,
          'user_id' => null,
          'name' => $company->name,
          'full_name' => $company->full_name,
          'inn' => $founded_inn,
          'ogrn' => $company->ogrn,
          'address' => $company->actual_address,
          'email' => $company->email,
          'phone' => $company->phone,
          'site' => $company->site,

          'longitude_center' => $company->longitude_center,
          'latitude_center' => $company->latitude_center,
          'longitude' => $company->longitude,
          'latitude' => $company->latitude,
        ]);

        // сохранение товаров производителя
        foreach ($company->goods as $good) {
          /** @var CompanyGoodFromParserValueObject $good */

          $is_already_exists = Good::where('data_source_id', $good->data_source_id)
            ->where('data_source_item_id', $good->data_source_item_id)->exists();

          // если такой товар в БД уже существует
          if (!$is_already_exists) {
            Good::create([
              'data_source_id' => $good->data_source_id,
              'data_source_item_id' => $good->data_source_item_id,
              'brand' => $good->brand,
              'name' => $good->name,
              'description' => $good->description,
              'price' => $good->price,
              'price_description' => $good->price_description,
              'keyword_for_search' => (object)$good->keyword_for_search,
              'data_source_item_url' => $good->data_source_item_url,
              'data_source_item_last_edit' => $good->data_source_item_last_edit,
              'price_min_party' => $good->price_min_party,
              'properties' => (object)$good->properties,
            ]);
          } else {
            // TODO: можно делать обновление товара
          }

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
