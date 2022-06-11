<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Создание таблицы для хранения источников данных
 */
return new class extends Migration {

  public const TABLE_NAME = 'data_sources';

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create(self::TABLE_NAME, function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('resource_name');
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
    Schema::dropIfExists(self::TABLE_NAME);
  }

};
