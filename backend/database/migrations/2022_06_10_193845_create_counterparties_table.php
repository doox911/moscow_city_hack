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
    Schema::create('counterparties', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->string('inn');
      $table->string('ogrn');
      $table->string('address');
      $table->string('email');
      $table->string('phone');
      //todo возможно еще какие то поля нужны
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
    Schema::dropIfExists('counterparties');
  }
};
