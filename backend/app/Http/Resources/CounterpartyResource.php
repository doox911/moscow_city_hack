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
      'base64_logo' => $counterparty->getPNGBase64Logo(),
    ];
  }
}
