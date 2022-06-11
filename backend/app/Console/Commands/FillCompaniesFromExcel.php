<?php

namespace App\Console\Commands;

use App\Exceptions\CommonException;
use App\Models\Counterparty;
use App\Services\CompanyService;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
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

    foreach ($companies as $company) {
      Counterparty::updateOrCreate([
        'inn' => $company->inn,
      ], [
        'name' => $company->name,
        'full_name' => $company->full_name,
        'site' => $company->site,
        'ogrn' => '',
        'address' => '',
      ]);
    }
  }
}
