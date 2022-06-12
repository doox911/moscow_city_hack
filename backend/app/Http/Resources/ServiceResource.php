<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /**
     * @var Service
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
