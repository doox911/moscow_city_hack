<?php

namespace App\Services;

use App\Exceptions\ValidationException;
use App\ValueObjects\CompanyFromExcelFileValueObject;
use Illuminate\Support\Collection;
use JsonException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class CompanyService
 *
 * @package Service
 */
class CompanyService {

  /**
   * Принимает объект Spreadsheet (файл Excel) и возвращает коллекцию компаний
   *
   * @param Spreadsheet $spreadsheet
   * @return Collection
   * @throws ValidationException
   * @throws JsonException
   */
  public static function getCompaniesFromSpreadSheet(Spreadsheet $spreadsheet): Collection {
    $companies = collect();

    $active_sheet = $spreadsheet->getActiveSheet();
    $max_count_rows = 10000000;

    for ($row = 2; $row < $max_count_rows; $row++) {
      $inn = $active_sheet->getCell('B' . $row)->getValue();
      $name = $active_sheet->getCell('C' . $row)->getValue();
      $full_name = $active_sheet->getCell('D' . $row)->getValue();
      $site = $active_sheet->getCell('E' . $row)->getValue();

      $is_correct_company = !empty($inn);

      if ($is_correct_company) {
        $companies->push(new CompanyFromExcelFileValueObject([
          'inn' => (string)$inn,
          'name' => (string)$name,
          'full_name' => (string)$full_name,
          'site' => (string)$site,
        ]));
      } else {
        // если нашлась строка у которой не заполнены размеры
        // то это конец файла, завершаем цикл
        break;
      }
    }

    return $companies;
  }
}
