<?php

namespace App\Http\Controllers;

use App\Http\Resources\OkvedResource;
use App\Models\Okved;
use Illuminate\Http\JsonResponse;

class OkvedController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse {
    $okveds = Okved::all()->filter(function (Okved $okved) {
      return preg_match("/[.|\d]+/",$okved->code);
    })->values();

    return response()->json([
      'content' => [
        'okved' => OkvedResource::collection($okveds),
      ],
      'messages' => ['Список ОКВЭД успешно загружен'],
    ]);
  }
}
