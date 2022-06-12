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
      if (!Schema::hasColumn(self::TABLE_NAME, 'longitude_center')) {
        $table->float('longitude_center', 10, 6)->nullable()->after('site');

        echo "Поле `longitude_center` в таблице `" . self::TABLE_NAME . "` создано\n\n";
      }

      if (!Schema::hasColumn(self::TABLE_NAME, 'latitude_center')) {
        $table->float('latitude_center', 10, 6)->nullable()->after('longitude_center');

        echo "Поле `latitude_center` в таблице `" . self::TABLE_NAME . "` создано\n\n";
      }

      if (!Schema::hasColumn(self::TABLE_NAME, 'longitude')) {
        $table->float('longitude', 10, 6)->nullable()->after('latitude_center');

        echo "Поле `longitude` в таблице `" . self::TABLE_NAME . "` создано\n\n";
      }

      if (!Schema::hasColumn(self::TABLE_NAME, 'latitude')) {
        $table->float('latitude', 10, 6)->nullable()->after('longitude');

        echo "Поле `latitude` в таблице `" . self::TABLE_NAME . "` создано\n\n";
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
      if (Schema::hasColumn(self::TABLE_NAME, 'longitude_center')) {
        $table->dropColumn('longitude_center');

        echo "Поле `longitude_center` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }

      if (Schema::hasColumn(self::TABLE_NAME, 'latitude_center')) {
        $table->dropColumn('latitude_center');

        echo "Поле `latitude_center` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }

      if (Schema::hasColumn(self::TABLE_NAME, 'longitude')) {
        $table->dropColumn('longitude');

        echo "Поле `longitude` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }

      if (Schema::hasColumn(self::TABLE_NAME, 'latitude')) {
        $table->dropColumn('latitude');

        echo "Поле `latitude` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }
    });
  }

};
