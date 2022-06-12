<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropertyController extends Controller {

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
   * @param Property $property
   * @return Response
   */
  public function update(Request $request, Property $property): Response {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param Property $property
   * @return Response
   */
  public function destroy(Property $property): Response {
    //
  }
}
