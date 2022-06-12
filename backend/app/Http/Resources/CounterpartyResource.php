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
      'created_at' => $counterparty->created_at,
      'updated_at' => $counterparty->updated_at,

      'data_source' => $data_source, // источник данных (например: адрес сайта, Excel файл)
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
