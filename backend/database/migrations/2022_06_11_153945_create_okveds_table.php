<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    self::fillOKVED();
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('okveds');
  }

  public static function fillOKVED() {
    ini_set('max_execution_time', '999999999');
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    $db_schema_dump = 'database/sql/okved_schema.sql';
    $db_dump = 'database/sql/okved_data.sql';

    echo "Создание справочника ОКВЭД\n";
    $sql_text = File::get($db_schema_dump);
    DB::unprepared($sql_text);
    echo "Создание справочника ОКВЭД завершено\n";

    echo "Заполнение справочника ОКВЭД\n";
    $sql_text = File::get($db_dump);
    DB::unprepared($sql_text);
    echo "Заполнение справочника ОКВЭД завершено\n";
  }
};
