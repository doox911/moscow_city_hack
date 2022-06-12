<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array
   */
  public function toArray($request): array {
    /**
     * @var \App\Models\Service
     */
    $service = $this->resource;

    return [
      'id' => $service->id,
      'code' => $service->code,
      'name' => $service->name,
      'additional_info' => $service->additional_info,
    ];
  }
}
