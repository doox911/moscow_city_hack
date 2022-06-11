<?php

namespace App\ValueObjects;

use App\Abstractions\AbstractValueObject;
use App\Exceptions\ValidationException;
use App\Traits\ValueObjectHelperTrait;
use JsonException;

/**
 * Class CompanyFromParserValueObject
 *
 * @property int|null $data_source_id
 * @property string|null $data_source_item_id
 * @property string|null $inn
 * @property string|null $name
 * @property string|null $full_name
 * @property string|null $description
 * @property string|null $site
 * @property string|null $logo_url
 * @property array $photos_urls
 * @property array $keywords_for_search
 *
 * @description Объект позиции для быстрого ввода
 * @package Preorder
 */
final class CompanyFromParserValueObject extends AbstractValueObject {
  use ValueObjectHelperTrait;

  /**
   * @description ID источника данных
   * @var int|null
   */
  protected ?int $data_source_id = null;

  /**
   * @description ID элемента источника данных
   * @var string|null
   */
  protected ?string $data_source_item_id = null;

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
   * @description Массив URL фотографий компании
   * @var array
   */
  protected array $photos_urls = [];

  /**
   * @description Ключевые слова для поиска компании
   * @var array
   */
  protected array $keywords_for_search = [];


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
