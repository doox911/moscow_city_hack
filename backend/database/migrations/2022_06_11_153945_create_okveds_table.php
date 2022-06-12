<?php

use App\Services\MainService;
use Illuminate\Database\Migrations\Migration;
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
    MainService::setUnlimitExecutionResources();

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
