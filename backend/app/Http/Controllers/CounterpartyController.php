<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\CounterpartyResource;
use App\Models\Counterparty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CounterpartyController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse {
    $counterparties = Counterparty::query();
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
        $counterparties->orWhere($column, 'like', "%{$filters['search_string']}%");
      }

      $counterparties->orderBy($column, $sort_direction);
    }

    $total_rows = $counterparties->count();

    $pages_count = ceil($total_rows / $items_per_page);

    return response()->json([
      'content' => [
        'counterparties' => CounterpartyResource::collection($counterparties->simplePaginate($items_per_page)),
        'pages_count' => $pages_count,
        'total_rows' => $total_rows,
      ],
      'messages' => ['Список компаний успешно загружен'],
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

    if ($request->user()->isOwnerRole()) {
      $tc = new TaskController;
      $req = new StoreTaskRequest;
      $req->replace([
        'user_id' => $request->user()->id,
        'entity_id' => null,
        'entity_type' => Counterparty::class,
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

    $counterparty = Counterparty::create($data);

    return response()->json([
      'content' => [
        'counterparty' => CounterpartyResource::make($counterparty->refresh()),
      ],
      'messages' => [
        'Запись о компании создана'
      ]
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param Counterparty $counterparty
   * @return JsonResponse
   */
  public function update(Request $request, Counterparty $counterparty): JsonResponse {
    $data = $request->all();

    if ($request->user()->isOwnerRole()) {
      $tc = new TaskController;
      $req = new StoreTaskRequest;
      $req->replace([
        'user_id' => $request->user()->id,
        'entity_id' => $counterparty->id,
        'entity_type' => $counterparty::class,
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

    $counterparty->update($data);

    return response()->json([
      'content' => [
        'counterparty' => CounterpartyResource::make($counterparty->refresh()),
      ],
      'messages' => [
        'Информация о компании обновлена'
      ]
    ]);
  }

  /**
   * @param Counterparty $counterparty
   * @return JsonResponse
   */
  public function getCounterparty(Counterparty $counterparty): JsonResponse {
    return response()->json([
      'content' => [
        'counterparty' => CounterpartyResource::make($counterparty),
      ],
      'messages' => [
        'Информация о компании получена'
      ]
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Counterparty $counterparty
   * @return Response
   */
  public function destroy(Counterparty $counterparty): Response {
    // todo realise
  }
}
