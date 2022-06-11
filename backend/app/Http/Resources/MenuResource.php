<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param \Illuminate\Http\Request $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray($request) {
    /**
     * @var \App\Models\Menu $menu
     */
    $menu = $this->resource;

    $role = $request->user()->roles->first()->name ?? 'guest';
    $path = rtrim('/' . $role . $menu->to, '/');

    return [
      'title' => $menu->title,
      'name' => $menu->name,
      'icon_name' => $menu->icon_name,
      'to' => $path,
      'order' => $menu->order,
      'separator' => $menu->separator,
    ];
  }
}
