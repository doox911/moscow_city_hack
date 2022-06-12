<?php

namespace App\Http\Controllers;

use App\Models\Good;
use Illuminate\Http\JsonResponse;

/**
 * Класс поиска
 */
class SearchController extends Controller {

  /**
   * Возвращает результаты объектов поиска
   * На выходе должен быть массив с компаниями производителями
   *
   * @param string $query
   * @return JsonResponse
   */
  public function search(string $query): JsonResponse {
    $goods = collect();
    $services = collect();
    $companies = collect();

    // сначала ищем полную фразу
    $search_goods = Good::where('name', 'like', "%$query%")
      ->orWhere('brand', 'like', "%$query%")
      ->get();

    $companies = $companies->merge($search_goods->flatMap->activities->map->counterparty);
    $goods = $goods->merge($search_goods);

    // затем разбиваем по словам и ищем для каждого слова
    $words = explode(' ', $query);

    if (count($words) > 1) {
      foreach ($words as $word) {
        $search_goods = Good::where('name', 'like', "%$word%")
          ->orWhere('brand', 'like', "%$word%")
          ->get();

        $companies = $companies->merge($search_goods->flatMap->activities->map->counterparty);
        $goods = $goods->merge($search_goods);
      }
    }

    return response()->json([
      'content' => [
        'companies' => $companies->unique('id')->values(),
        'goods' => $goods->unique('id')->values(),
        'services' => $services->unique('id')->values(),
      ]
    ]);
  }
}
