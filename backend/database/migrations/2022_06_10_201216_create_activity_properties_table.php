<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('activity_properties', function (Blueprint $table) {
      $table->id();
      $table->foreignId('property_id');

      $table->unsignedInteger('activity_id');
      $table->string('activity_type');
      $table->string('value');
      $table->boolean('is_active');

      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('activity_properties');
  }
};
