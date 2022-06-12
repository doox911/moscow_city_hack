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
    return response()->json([
      'content' => [
        'okved' => OkvedResource::collection(Okved::all()),
      ],
      'messages' => ['Список ОКВЭД успешно загружен'],
    ]);
  }
}
