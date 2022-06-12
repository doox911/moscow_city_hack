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
      $table->string('supervisor')->after('legal_address')->nullable();
      $table->decimal('revenue', 12)->after('supervisor')->nullable();
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
