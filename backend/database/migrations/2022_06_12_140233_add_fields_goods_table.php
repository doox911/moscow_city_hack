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
    Schema::table('goods', function (Blueprint $table) {
      $table->longText('description')->after('name')->nullable();
      $table->decimal('price')->after('description')->nullable();
      $table->longText('price_description')->after('price')->nullable();
      $table->json('keywords_for_search')->after('price_description')->nullable();
      $table->string('data_source_item_url')->after('keywords_for_search')->nullable();
      $table->dateTime('data_source_item_last_edit')->after('data_source_item_url')->nullable();
      $table->string('price_min_party')->after('data_source_item_last_edit')->nullable();
      $table->json('properties')->after('price_min_party')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    //
  }
};
