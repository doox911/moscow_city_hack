<?php

namespace App\Http\Resources;

use App\Models\Menu;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource {
  /**
   * Transform the resource into an array.
   *
   * @param Request $request
   * @return array
   */
  public function toArray($request): array {
    /**
     * @var Menu $menu
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
