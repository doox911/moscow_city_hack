<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Good;
use Illuminate\Http\Request;

class GoodController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index() {
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
        'goods' => $goods->simplePaginate($items_per_page),
        'pages_count' => $pages_count,
        'total_rows' => $total_rows,
      ],
      'messages' => ['Список товаров успешно загружен'],
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function store(Request $request) {
    $data = $request->all();

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
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\Good $good
   * @return \Illuminate\Http\JsonResponse
   */
  public function update(Request $request, Good $good) {
    $data = $request->all();

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
   * Remove the specified resource from storage.
   *
   * @param \App\Models\Good $good
   * @return \Illuminate\Http\Response
   */
  public function destroy(Good $good) {
    // todo realise
  }
}
