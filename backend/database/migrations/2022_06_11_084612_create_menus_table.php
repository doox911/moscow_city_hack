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
    Schema::create('menus', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('name');
      $table->string('icon_name');
      $table->string('to');
      $table->integer('order')->default(100);
      $table->boolean('separator')->default(false);
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
    Schema::dropIfExists('menus');
  }
};
