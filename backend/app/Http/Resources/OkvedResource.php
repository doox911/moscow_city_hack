<?php

namespace App\Http\Resources;

use App\Models\Okved;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OkvedResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {

    /**
     * @var Okved $okved
     */
    $okved = $this->resource;

    return [
      'code' => $okved->code,
      'name' => $okved->name,
      'additional_info' => $okved->additional_info,
    ];
  }
}
