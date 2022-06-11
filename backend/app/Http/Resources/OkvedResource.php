<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OkvedResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request) {

    /**
     * @var \App\Models\Okved $okved
     */
    $okved = $this->resource;

    return [
      'code' => $okved->code,
      'name' => $okved->name,
      'additional_info' => $okved->additional_info,
    ];
  }
}
