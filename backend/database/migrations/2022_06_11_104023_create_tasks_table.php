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
    Schema::create('tasks', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->foreign('user_id')->references('id')->on('users');

      $table->unsignedBigInteger('entity_id')->nullable();
      $table->string('entity_type');

      $table->json('value');
      $table->boolean('is_moderated')->default(false);
      $table->boolean('is_accepted')->default(false);
      $table->longText('comment')->nullable();
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
    Schema::dropIfExists('tasks');
  }
};
