<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @package User
 */
return new class extends Migration {

  public const TABLE_NAME = 'users';

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::table(self::TABLE_NAME, static function (Blueprint $table) {
      if (!Schema::hasColumn(self::TABLE_NAME, 'second_name')) {
        $table->string('second_name')
          ->after('name')
          ->nullable()
          ->comment('Фамилия');

        echo "Поле `second_name` добавлено в таблицу `" . self::TABLE_NAME . "`\n\n";
      }

      if (!Schema::hasColumn(self::TABLE_NAME, 'patronymic')) {
        $table->string('patronymic')
          ->after('second_name')
          ->nullable()
          ->comment('Отчество');

        echo "Поле `patronymic` добавлено в таблицу `" . self::TABLE_NAME . "`\n\n";
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
      if (Schema::hasColumn(self::TABLE_NAME, 'second_name')) {
        $table->dropColumn('second_name');

        echo "Поле `second_name` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }

      if (Schema::hasColumn(self::TABLE_NAME, 'patronymic')) {
        $table->dropColumn('patronymic');

        echo "Поле `patronymic` удалено из таблицы `" . self::TABLE_NAME . "`\n\n";
      }
    });
  }

};
