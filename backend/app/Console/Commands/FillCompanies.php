<?php

namespace App\Console\Commands;

use App\Models\Activity;
use App\Models\Counterparty;
use App\Models\Service;
use App\Parsers\FNSParser;
use Illuminate\Console\Command;

class FillCompanies extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'company:fill';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Take companies from FNS';

  /**
   * Execute the console command.
   *
   */
  public function handle() {
    $parser = new FNSParser;

    $companies = $parser->parse();

    foreach ($companies as $company) {
      $c = $company->{"ЮЛ"} ?? $company->{"ИП"};

      $new_company = Counterparty::updateOrCreate([
        'inn' => $c->{"ИНН"}
      ], [
        'name' => $c->{"НаимСокрЮЛ"} ?? $c->{"ФИОПолн"},
        'ogrn' => $c->{"ОГРН"} ?? $c->{"ОГРНИП"},
        'address' => $c->{"Адрес"}->{"АдресПолн"},
      ]);

      $new_service = Service::updateOrCreate([
        'code' => $c->{"ОснВидДеят"}->{"Код"},
      ], [
        'name' => $c->{"ОснВидДеят"}->{"Текст"},
      ]);

      Activity::updateOrCreate([
        'counterparty_id' => $new_company->id,
        'activity_id' => $new_service->id,
        'activity_type' => Service::class,
      ], [
        'is_active' => true,
      ]);

      $extra_services = $c->{"ДопВидДеят"} ?? "";
      foreach (explode(',', $extra_services) as $code) {
        $new_service = Service::updateOrCreate([
          'code' => $code,
        ], [
          'name' => "",
        ]);

        Activity::updateOrCreate([
          'counterparty_id' => $new_company->id,
          'activity_id' => $new_service->id,
          'activity_type' => Service::class,
        ], [
          'is_active' => true,
        ]);
      }
    }
  }
}
