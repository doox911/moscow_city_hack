<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

  public const TABLE_NAME = 'counterparties';

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table(self::TABLE_NAME, function (Blueprint $table) {
      if (!Schema::hasColumn(self::TABLE_NAME, 'data_source_id')) {
        $table->unsignedBigInteger('data_source_id')
          ->after('id')
          ->nullable()
          ->comment('ID источника данных');

        $table->foreign('data_source_id')
          ->references('id')
          ->on('data_sources');

        echo "Поле `data_source_id` в таблице `" . self::TABLE_NAME . "` создано\n\n";
      }

      if (!Schema::hasColumn(self::TABLE_NAME, 'data_source_item_id')) {
        $table->string('data_source_item_id')->nullable()->after('data_source_id');

        echo "Поле `data_source_item_id` в таблице `" . self::TABLE_NAME . "` создано\n\n";
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
      if (Schema::hasColumn(self::TABLE_NAME, 'data_source_id')) {
        $table->dropColumn('data_source_id');

        echo "Поле `data_source_id` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }

      if (Schema::hasColumn(self::TABLE_NAME, 'data_source_item_id')) {
        $table->dropColumn('data_source_item_id');

        echo "Поле `data_source_item_id` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }
    });
  }

};
