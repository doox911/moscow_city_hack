<?php

namespace App\ValueObjects;

use App\Abstractions\AbstractValueObject;
use App\Exceptions\ValidationException;
use App\Traits\ValueObjectHelperTrait;
use JsonException;

/**
 * Class CompanyFromExcelFileValueObject
 *
 * @property string $inn
 * @property string $name
 * @property string $full_name
 * @property string $site
 *
 * @description Объект позиции для быстрого ввода
 * @package Preorder
 */
final class CompanyFromExcelFileValueObject extends AbstractValueObject {
  use ValueObjectHelperTrait;

  /**
   * @description ИНН
   * @var string
   */
  protected string $inn;

  /**
   * @description Наименование
   * @var string
   */
  protected string $name;

  /**
   * @description Наименование полное
   * @var string
   */
  protected string $full_name;

  /**
   * @description URL сайта
   * @var string
   */
  protected string $site;


  /**
   * CompanyFromExcelFileValueObject constructor.
   *
   * @param mixed $mixed_data
   * @throws ValidationException|JsonException
   */
  public function __construct($mixed_data) {
    $this->validate($mixed_data);
    $this->initializePropertiesFromArray($mixed_data);
  }
}
