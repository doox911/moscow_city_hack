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
      foreach ($parser->parse() as $company_vo) {
        /** @var CompanyFromParserValueObject $company_vo */

        // ищем ИНН компании в ФНС по
        if (empty($company_vo->inn)) {
          $fns_company = $fns->search($company_vo->name);
          $inns[] = $fns_company->{'ИНН'};

          $founded_inn = $fns_company->{'ИНН'};
        } else {
          $inns[] = $company_vo->inn;

          $founded_inn = $company_vo->inn;
        }

        $counterparty = Counterparty::updateOrCreate([
          'inn' => $founded_inn,
        ], [
          'data_source_id' => $company_vo->data_source_id,
          'data_source_item_id' => $company_vo->data_source_item_id,
          'user_id' => null,
          'name' => $company_vo->name,
          'full_name' => $company_vo->full_name,
          'inn' => $founded_inn,
          'ogrn' => $company_vo->ogrn,
          'address' => $company_vo->actual_address,
          'email' => $company_vo->email,
          'phone' => $company_vo->phone,
          'site' => $company_vo->site,

          'longitude_center' => $company_vo->longitude_center,
          'latitude_center' => $company_vo->latitude_center,
          'longitude' => $company_vo->longitude,
          'latitude' => $company_vo->latitude,
        ]);

        if ($company_vo->logo_url) {
          $counterparty->saveLogoFromUrl($company_vo->logo_url);
        }

        $counterparty->savePhotosFromUrlArray($company_vo->photos_urls);

        // сохранение товаров производителя
        foreach ($company_vo->goods as $good_vo) {
          /** @var CompanyGoodFromParserValueObject $good_vo */

          $is_already_exists = Good::where('data_source_id', $good_vo->data_source_id)
            ->where('data_source_item_id', $good_vo->data_source_item_id)->exists();

          // если такой товар в БД уже существует
          if (!$is_already_exists) {
            $good = Good::create([
              'data_source_id' => $good_vo->data_source_id,
              'data_source_item_id' => $good_vo->data_source_item_id,
              'brand' => $good_vo->brand,
              'name' => $good_vo->name,
              'description' => $good_vo->description,
              'price' => $good_vo->price,
              'price_description' => $good_vo->price_description,
              'keyword_for_search' => (object)$good_vo->keyword_for_search,
              'data_source_item_url' => $good_vo->data_source_item_url,
              'data_source_item_last_edit' => $good_vo->data_source_item_last_edit,
              'price_min_party' => $good_vo->price_min_party,
              'properties' => (object)$good_vo->properties,
            ]);

            $good->savePhotosFromUrlArray($good_vo->photos_urls);
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
