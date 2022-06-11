<?php

namespace App\Http\Controllers;

use App\Http\Resources\OkvedResource;
use App\Models\Okved;
use Illuminate\Http\Request;

class OkvedController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index() {
    return response()->json([
      'content' => [
        'okved' => OkvedResource::collection(Okved::all()),
      ],
      'messages' => ['Список ОКВЭД успешно загружен'],
    ]);
  }
}
