<?php

use App\Models\DataSource;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {

  public const TABLE_NAME = 'data_sources';

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    if (!DataSource::where('canonical_name', 'productcenter_ru')->first()) {
      DataSource::create([
        'name' => 'Интернет-выставка Производство России',
        'canonical_name' => 'productcenter_ru',
        'resource_name' => 'https://productcenter.ru',
      ]);
    }

    if (!DataSource::where('canonical_name', 'fns_api')->first()) {
      DataSource::create([
        'name' => 'ФНС',
        'canonical_name' => 'fns_api',
        'resource_name' => 'https://api-fns.ru',
      ]);
    }

    if (!DataSource::where('canonical_name', 'excel')->first()) {
      DataSource::create([
        'name' => 'Excel',
        'canonical_name' => 'excel',
        'resource_name' => 'file',
      ]);
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    DataSource::whereIn('canonical_name', [
      'productcenter_ru',
      'fns_api',
      'excel',
    ])->forcedelete();
  }

};
