<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function index() {
    $tasks = Task::query();
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

    $default_sort = [
      'columns' => [
        'is_accepted' => 'asc',
        'id' => 'asc',
      ]
    ];

    $filters['columns'] = [...$default_sort['columns'], ...($filters['columns'] ?? [])];

    foreach ($filters['columns'] as $column => $sort_direction) {
      if (!empty($filters['search_string'])) {
        $tasks->orWhere($column, 'like', "%{$filters['search_string']}%");
      }

      $tasks->orderBy($column, $sort_direction);
    }

    $total_rows = $tasks->count();

    $pages_count = ceil($total_rows / $items_per_page);

    return response()->json([
      'content' => [
        'tasks' => $tasks->simplePaginate($items_per_page),
        'pages_count' => $pages_count,
        'total_rows' => $total_rows,
      ],
      'messages' => ['Список задач успешно загружен'],
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \App\Http\Requests\StoreTaskRequest $request
   * @return \App\Models\Task
   */
  public function store(StoreTaskRequest $request): Task {
    $data = $request->all();

    return Task::create($data);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \App\Http\Requests\StoreTaskRequest $request
   * @param \App\Models\Task $task
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Task $task) {

    $data = $request->all();

    if ($data['is_accepted']) {

    }

    $task->update($data);
    $task->is_moderated = true;
    $task->save();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\Task $task
   * @return \Illuminate\Http\Response
   */
  public function destroy(Task $task) {
    //
  }
}
