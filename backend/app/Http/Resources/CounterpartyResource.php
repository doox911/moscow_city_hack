<?php

namespace App\Http\Resources;

use App\Models\Counterparty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CounterpartyResource
 *
 * @description Ресурс данных контрагента
 * @package Counterparty
 */
class CounterpartyResource extends JsonResource {

  private static bool $without_goods = false;

  public static function setWithoutGoods(): void {
    self::$without_goods = true;
  }

  /**
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /** @var Counterparty */
    $counterparty = $this->resource;

    $registration_date = !empty($counterparty->registration_date) ? Carbon::parse($counterparty->registration_date)->format('Y-m-d') : null;

    $response_array = [
      'id' => $counterparty->id,
      'user_id' => $counterparty->user_id,
      'name' => $counterparty->name,
      'full_name' => $counterparty->full_name,
      'inn' => $counterparty->inn,
      'ogrn' => $counterparty->ogrn,
      'address' => $counterparty->address,
      'email' => $counterparty->email,
      'phone' => $counterparty->phone,
      'site' => $counterparty->site,

      'data_source_item_url' => $counterparty->data_source_item_url,
      'description' => $counterparty->description,
      'legal_address' => $counterparty->legal_address,
      'number_of_employees' => $counterparty->number_of_employees,
      'authorized_capital' => $counterparty->authorized_capital,
      'registration_date' => $registration_date,
      'keywords_for_search' => $counterparty->keywords_for_search,

      'created_at' => $counterparty->created_at,
      'updated_at' => $counterparty->updated_at,

      // источник данных (например: адрес сайта, Excel файл)
      'data_source' => $this->whenLoaded('data_source', DataSourceResource::make($counterparty->data_source)),

      'base64_logo' => $counterparty->getPNGBase64Logo(), // логотип компании
      'base64_photos' => $counterparty->getPNGBase64Photos(), // галерея фотографий компании

      'longitude_center' => $counterparty->longitude_center,
      'latitude_center' => $counterparty->latitude_center,
      'longitude' => $counterparty->longitude,
      'latitude' => $counterparty->latitude,

      'goods' => [], // по умолчанию
      'services' => [], // по умолчанию
    ];

    if (self::$without_goods === false) {
      $response_array['goods'] = GoodResource::collection($counterparty->goods->map->activity);
      $response_array['services'] = ServiceResource::collection($counterparty->services->map->activity);
    }

    return $response_array;
  }
}
