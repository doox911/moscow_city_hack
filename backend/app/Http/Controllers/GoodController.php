<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\GoodResource;
use App\Models\Activity;
use App\Models\Counterparty;
use App\Models\Good;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GoodController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse {
    $goods = Good::query();
    $items_per_page = request()->input('item_per_page');

    /**
     * [
     *  search_string => "",
     *  columns => [
     *   column_name =>"asc|desc"
     *  ]
     * ]
     */
    $filters = request()->input('filters');

    if (is_string($filters)) {
      $filters = json_decode($filters, true);
    }

    foreach ($filters['columns'] as $column => $sort_direction) {
      if (!empty($filters['search_string'])) {
        $goods->orWhere($column, 'like', "%{$filters['search_string']}%");
      }

      $goods->orderBy($column, $sort_direction);
    }

    $total_rows = $goods->count();

    $pages_count = ceil($total_rows / $items_per_page);

    return response()->json([
      'content' => [
        'goods' => GoodResource::collection($goods->simplePaginate($items_per_page)),
        'pages_count' => $pages_count,
        'total_rows' => $total_rows,
      ],
      'messages' => ['Список товаров успешно загружен'],
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return JsonResponse
   */
  public function store(Request $request): JsonResponse {
    $data = $request->all();

    // todo move to Trait or Service (duplicate code)
    if ($request->user()->isOwnerRole()) {
      $tc = new TaskController;
      $req = new StoreTaskRequest;
      $req->replace([
        'user_id' => $request->user()->id,
        'entity_id' => null,
        'entity_type' => Good::class,
        'value' => [
          'method' => 'store',
          'data' => $data,
        ]
      ]);

      $new_task = $tc->store($req);

      return response()->json([
        'content' => [
          'task_id' => $new_task->id,
        ],
        'messages' => [
          'Запрос на добавление данных отправлен'
        ]
      ]);
    }

    $good = Good::create($data);

    return response()->json([
      'content' => [
        'good' => $good->refresh(),
      ],
      'messages' => [
        'Запись о товаре создана'
      ]
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param Good $good
   * @return JsonResponse
   */
  public function update(Request $request, Good $good): JsonResponse {
    $data = $request->all();

    // todo move to Trait or Service (duplicate code)
    if ($request->user()->isOwnerRole()) {
      $tc = new TaskController;
      $req = new StoreTaskRequest;
      $req->replace([
        'user_id' => $request->user()->id,
        'entity_id' => $good->id,
        'entity_type' => $good::class,
        'value' => [
          'method' => 'update',
          'data' => $data,
        ]
      ]);

      $new_task = $tc->store($req);

      return response()->json([
        'content' => [
          'task_id' => $new_task->id,
        ],
        'messages' => [
          'Запрос на модерацию данных отправлен'
        ]
      ]);
    }

    $good->update($data);

    return response()->json([
      'content' => [
        'good' => $good->refresh(),
      ],
      'messages' => [
        'Информация о товаре обновлена'
      ]
    ]);
  }

  /**
   * Массовая обработка массива товаров и создание записей о компании, которая производит эти товары
   *
   * @param array $goods
   * @param Counterparty $counterparty
   * @return void
   */
  public static function massAttachToCounterparty(array $goods, Counterparty $counterparty): void {
    foreach ($goods as $good) {
      $good = Good::updateOrCreate([
        'name' => $good['name'],
        'brand' => $good['brand'],
      ]);

      Activity::updateOrCreate([
        'counterparty_id' => $counterparty->id,
        'activity_id' => $good->id,
        'activity_type' => Good::class,
        'is_active' => true,
      ]);
    }
  }

  /**
   * @param Good $good
   * @return JsonResponse
   */
  public function getGood(Good $good): JsonResponse {
    return response()->json([
      'content' => [
        'good' => GoodResource::make($good),
      ],
      'messages' => [
        'Информация о товаре получена'
      ]
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Good $good
   * @return Response
   */
  public function destroy(Good $good): Response {
    // todo realise
  }
}
