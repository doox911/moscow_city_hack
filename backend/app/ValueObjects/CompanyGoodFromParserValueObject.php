<?php

namespace App\ValueObjects;

use App\Abstractions\AbstractValueObject;
use App\Exceptions\ValidationException;
use App\Traits\ValueObjectHelperTrait;
use JsonException;

/**
 * Class CompanyGoodFromParserValueObject
 *
 * @property int|null $data_source_id
 * @property string|null $data_source_item_id
 * @property string|null $data_source_item_url
 * @property string|null $data_source_item_last_edit
 * @property string|null $name
 * @property string|null $description
 * @property string|null $price
 * @property string|null $price_description
 * @property string|null $price_min_party
 *
 * @property array $photos_urls
 * @property array $keywords_for_search
 * @property array $properties
 *
 * @description Объект товара производителя после парсинга
 * @package Preorder
 */
final class CompanyGoodFromParserValueObject extends AbstractValueObject {
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
   * @description URL источника данных конкретной записи
   * @var string|null
   */
  protected ?string $data_source_item_url = null;

  /**
   * @description Дата изменения
   * @var string|null
   */
  protected ?string $data_source_item_last_edit = null;

  /**
   * @description Наименование
   * @var string|null
   */
  protected ?string $name = null;

  /**
   * @description Описание
   * @var string|null
   */
  protected ?string $description = null;

  /**
   * @description Цена
   * @var float|null
   */
  protected ?float $price = null;

  /**
   * @description Цена (комментарий)
   * @var string|null
   */
  protected ?string $price_description = null;

  /**
   * @description Мининимальная партия покупки (цена за минимальную партию?)
   * @var string|null
   */
  protected ?string $price_min_party = null;

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
   * @description Характеристики товара
   * @var array
   */
  protected array $properties = [];


  /**
   * CompanyGoodFromParserValueObject constructor.
   *
   * @param mixed $mixed_data
   * @throws ValidationException|JsonException
   */
  public function __construct($mixed_data) {
    $this->validate($mixed_data);
    $this->initializePropertiesFromArray($mixed_data);
  }
}
