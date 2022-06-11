<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TestDataSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    ini_set('max_execution_time', '999999999');
    set_time_limit(0);
    ini_set('memory_limit', '-1');

    $db_dump = 'database/sql/test.sql';


    echo "Инициализация базы данных приложения\n";
    $sql_text = File::get($db_dump);
    DB::unprepared($sql_text);
    echo "Инициализация базы данных приложения завершена\n";
  }
}
