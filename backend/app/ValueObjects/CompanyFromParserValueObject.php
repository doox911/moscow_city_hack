<?php

namespace App\ValueObjects;

use App\Abstractions\AbstractValueObject;
use App\Exceptions\ValidationException;
use App\Traits\ValueObjectHelperTrait;
use JsonException;

/**
 * Class CompanyFromParserValueObject
 *
 * @property string|null $inn
 * @property string|null $name
 * @property string|null $full_name
 * @property string|null $description
 * @property string|null $site
 * @property string|null $logo_url
 *
 * @description Объект позиции для быстрого ввода
 * @package Preorder
 */
final class CompanyFromParserValueObject extends AbstractValueObject {
  use ValueObjectHelperTrait;

  /**
   * @description ИНН
   * @var string|null
   */
  protected ?string $inn = null;

  /**
   * @description Наименование
   * @var string|null
   */
  protected ?string $name = null;

  /**
   * @description Наименование полное
   * @var string|null
   */
  protected ?string $full_name = null;

  /**
   * @description Описание производителя
   * @var string|null
   */
  protected ?string $description = null;

  /**
   * @description URL сайта
   * @var string|null
   */
  protected ?string $site = null;

  /**
   * @description URL логотипа
   * @var string|null
   */
  protected ?string $logo_url = null;


  /**
   * CompanyFromParserValueObject constructor.
   *
   * @param mixed $mixed_data
   * @throws ValidationException|JsonException
   */
  public function __construct($mixed_data) {
    $this->validate($mixed_data);
    $this->initializePropertiesFromArray($mixed_data);
  }
}
