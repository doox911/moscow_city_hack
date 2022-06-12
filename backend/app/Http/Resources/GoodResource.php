<?php

namespace App\Http\Resources;

use App\Models\Good;
use Carbon\Carbon;
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
    /** @var Good  */
    $good = $this->resource;

    $data_source_item_last_edit = !empty($good->registration_date) ? Carbon::parse($good->data_source_item_last_edit)->format('Y-m-d') : null;

    return [
      'id' => $good->id,
      'brand' => $good->brand,
      'name' => $good->name,
      'description' => $good->description,
      'additional_info' => $good->additional_info,
      'price' => $good->price,
      'price_description' => $good->price_description,
      'keywords_for_search' => $good->keywords_for_search,
      'data_source_item_url' => $good->data_source_item_url,
      'data_source_item_last_edit' => $data_source_item_last_edit,
      'price_min_party' => $good->price_min_party,
      'properties' => $good->properties,
      'base64_photos' => $good->getPNGBase64Photos(), // галерея фотографий товара

      // источник данных (например: адрес сайта, Excel файл)
      'data_source' => $this->whenLoaded('data_source', DataSourceResource::make($good->data_source)),
    ];
  }
}
