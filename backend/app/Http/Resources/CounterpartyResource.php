<?php

namespace App\Http\Resources;

use App\Models\Counterparty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class CounterpartyResource
 *
 * @description Ресурс данных контрагента
 * @package Counterparty
 */
class CounterpartyResource extends JsonResource {

  /**
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /** @var Counterparty */
    $counterparty = $this->resource;

    $data_source = $counterparty->data_source ? DataSourceResource::make($counterparty->data_source) : null;

    // todo добавить поля
    return [
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
      'registration_date' => $counterparty->registration_date,
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

      'goods' => $counterparty->goods->map->activity,
      'services' => $counterparty->services->map->activity,
    ];
  }
}
