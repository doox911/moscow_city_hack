<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index(): JsonResponse {
    return response()->json([
      'content' => [
        'menus' => MenuResource::collection(Menu::all()),
      ],
      'messages' => ['Список меню успешно загружен'],
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\Menu $menu
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Menu $menu) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\Menu $menu
   * @return \Illuminate\Http\Response
   */
  public function destroy(Menu $menu) {
    //
  }
}
