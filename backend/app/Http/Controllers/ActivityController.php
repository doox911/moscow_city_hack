<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivityController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(): Response {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return Response
   */
  public function store(Request $request): Response {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param Activity $activity
   * @return Response
   */
  public function update(Request $request, Activity $activity): Response {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Activity $activity
   * @return Response
   */
  public function destroy(Activity $activity): Response {
    //
  }
}
