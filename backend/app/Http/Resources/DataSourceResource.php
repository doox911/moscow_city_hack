<?php

namespace App\Http\Resources;

use App\Models\DataSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class DataSourceResource
 *
 * @description Ресурс источника данных
 */
class DataSourceResource extends JsonResource {

  /**
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /** @var DataSource */
    $data_source = $this->resource;

    return [
      'id' => $data_source->id,
      'name' => $data_source->name,
      'resource_name' => $data_source->resource_name,
    ];
  }
}
