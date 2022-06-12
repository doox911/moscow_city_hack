<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Activity;
use App\Models\Counterparty;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return JsonResponse
   */
  public function index(): JsonResponse {
    $services = Service::query();
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
        $services->orWhere($column, 'like', "%{$filters['search_string']}%");
      }

      $services->orderBy($column, $sort_direction);
    }

    $total_rows = $services->count();

    $pages_count = ceil($total_rows / $items_per_page);

    return response()->json([
      'content' => [
        'services' => ServiceResource::collection($services->simplePaginate($items_per_page)),
        'pages_count' => $pages_count,
        'total_rows' => $total_rows,
      ],
      'messages' => ['Список услуг успешно загружен'],
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
        'entity_type' => Service::class,
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

    $service = Service::create($data);

    return response()->json([
      'content' => [
        'service' => $service->refresh(),
      ],
      'messages' => [
        'Запись об услуге создана'
      ]
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param Service $service
   * @return JsonResponse
   */
  public function update(Request $request, Service $service): JsonResponse {
    $data = $request->all();

    if ($request->user()->isOwnerRole()) {
      $tc = new TaskController;
      $req = new StoreTaskRequest;
      $req->replace([
        'user_id' => $request->user()->id,
        'entity_id' => $service->id,
        'entity_type' => $service::class,
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

    $service->update($data);

    return response()->json([
      'content' => [
        'service' => $service->refresh(),
      ],
      'messages' => [
        'Информация об услуге обновлена'
      ]
    ]);
  }

  /**
   * Массовая обработка массива услуг и создание записей о компании, которая производит эти услуги
   *
   * @param array $services
   * @param Counterparty $counterparty
   * @return void
   */
  public static function massAttachToCounterparty(array $services, Counterparty $counterparty): void {
    foreach ($services as $service_id) {
      Activity::updateOrCreate([
        'counterparty_id' => $counterparty->id,
        'activity_id' => $service_id,
        'activity_type' => Service::class,
        'is_active' => true,
      ]);
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Service $service
   * @return Response
   */
  public function destroy(Service $service): Response {
    // todo realise
  }
}
