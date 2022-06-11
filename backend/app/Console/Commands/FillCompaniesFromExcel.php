<?php

namespace App\Console\Commands;

use App\Exceptions\CommonException;
use App\Helpers\ConsoleProgressBar;
use App\Models\Counterparty;
use App\Services\CompanyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use JsonException;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Finder\SplFileInfo;

class FillCompaniesFromExcel extends Command {

  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'company:fill_from_excel';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Take companies from Excel file';

  /**
   * Execute the console command.
   *
   * @throws CommonException|JsonException
   */
  public function handle() {
    $files_array = File::files(database_path() . '/files');
    $file = collect($files_array)->first();

    $companies = collect();
    if ($file instanceof SplFileInfo) {
      $spreadsheet = IOFactory::load($file->getPathname());
      $companies = CompanyService::getCompaniesFromSpreadSheet($spreadsheet);
    } else {
      throw new CommonException(
        'Некорректный файл',
        'Ошибка'
      );
    }

    ConsoleProgressBar::setTotal($companies->count());

    $i = 0;
    foreach ($companies as $company) {
      ConsoleProgressBar::updateProgress($i, "ИНН компании $company->inn, Наименование: " . $company->name);

      Counterparty::updateOrCreate([
        'inn' => $company->inn,
      ], [
        'name' => $company->name,
        'full_name' => $company->full_name,
        'site' => $company->site,
        'ogrn' => '',
        'address' => '',
      ]);

      ConsoleProgressBar::updateProgress(++$i);
    }

    echo "Импорт компаний из Excel файла успешно произведён\t\n";
  }
}
