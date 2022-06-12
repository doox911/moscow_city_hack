<?php

namespace App\Http\Resources;

use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /**
     * @var Good
     */
    $good = $this->resource;

    //todo добавить поля
    return [
      'id' => $good->id,
      'brand' => $good->brand,
      'name' => $good->name,
      'additional_info' => $good->additional_info,
      'data_source' => $this->whenLoaded('data_source', DataSourceResource::make($good->data_source)),
    ];
  }
}
