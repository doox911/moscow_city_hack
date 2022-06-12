<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  public const TABLE_NAME = 'data_sources';

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table(self::TABLE_NAME, function (Blueprint $table) {
      if (!Schema::hasColumn(self::TABLE_NAME, 'canonical_name')) {
        $table->string('canonical_name')->nullable()->after('name');

        echo "Поле `canonical_name` в таблице `" . self::TABLE_NAME . "` создано\n\n";
      }
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::table(self::TABLE_NAME, static function (Blueprint $table) {
      if (Schema::hasColumn(self::TABLE_NAME, 'canonical_name')) {
        $table->dropColumn('canonical_name');

        echo "Поле `canonical_name` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }
    });
  }

};
