<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
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
        'goods' => $services->simplePaginate($items_per_page),
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
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param \App\Models\Service $service
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Service $service) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param \App\Models\Service $service
   * @return \Illuminate\Http\Response
   */
  public function destroy(Service $service) {
    //
  }
}
