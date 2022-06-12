<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MenuController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
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
   * @param Request $request
   * @return Response
   */
  public function store(Request $request): Response {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param Menu $menu
   * @return Response
   */
  public function update(Request $request, Menu $menu): Response {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Menu $menu
   * @return Response
   */
  public function destroy(Menu $menu): Response {
    //
  }
}
