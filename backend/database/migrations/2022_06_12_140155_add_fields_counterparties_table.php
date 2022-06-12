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
  public function up(): void {
    Schema::table('counterparties', function (Blueprint $table) {
      $table->string('data_source_item_url')->after('site')->nullable();
      $table->longText('description')->after('data_source_item_url')->nullable();
      $table->text('legal_address')->after('description')->nullable();
      $table->integer('number_of_employees')->after('legal_address')->nullable();
      $table->integer('authorized_capital')->after('number_of_employees')->nullable();
      $table->dateTime('registration_date')->after('authorized_capital')->nullable();
      $table->string('kpp')->after('ogrn')->nullable();
      $table->json('keywords_for_search')->after('registration_date')->nullable();
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
