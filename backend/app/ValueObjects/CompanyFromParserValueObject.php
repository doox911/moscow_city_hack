<?php

namespace App\ValueObjects;

use App\Abstractions\AbstractValueObject;
use App\Exceptions\ValidationException;
use App\Traits\ValueObjectHelperTrait;
use Illuminate\Support\Collection;
use JsonException;

/**
 * Class CompanyFromParserValueObject
 *
 * @property int|null $data_source_id
 * @property string|null $data_source_item_id
 * @property string|null $data_source_item_url
 * @property string|null $name
 * @property string|null $full_name
 * @property string|null $description
 * @property string|null $site
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $actual_address
 * @property string|null $legal_address
 * @property string|null $logo_url
 *
 * @property int|null $number_of_employees
 * @property float|null $authorized_capital
 * @property string|null $registration_date
 * @property string|null $orgn
 * @property string|null $inn
 * @property string|null $kpp
 *
 * @property float|null $longitude_center
 * @property float|null $latitude_center
 * @property float|null $longitude
 * @property float|null $latitude
 *
 * @property array $photos_urls
 * @property array $keywords_for_search
 * @property string|null $general_activity
 * @property array $activities
 * @property Collection $goods collection of CompanyGoodFromParserValueObject
 *
 * @description Объект производителя после парсинга
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
   * @description URL источника данных конкретной записи
   * @var string|null
   */
  protected ?string $data_source_item_url = null;

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
   * @description Адрес фактический
   * @var string|null
   */
  protected ?string $actual_address = null;

  /**
   * @description Адрес юридический
   * @var string|null
   */
  protected ?string $legal_address = null;

  /**
   * @description Количество сотрудников
   * @var int|null
   */
  protected ?int $number_of_employees = null;

  /**
   * @description Уставной капитал
   * @var float|null
   */
  protected ?float $authorized_capital = null;

  /**
   * @description Дата регистрации в формате Y-m-d
   * @var string|null
   */
  protected ?string $registration_date = null;

  /**
   * @description ОГРН
   * @var string|null
   */
  protected ?string $orgn = null;

  /**
   * @description ИНН
   * @var string|null
   */
  protected ?string $inn = null;

  /**
   * @description КПП
   * @var string|null
   */
  protected ?string $kpp = null;

  /**
   * @description Контактный телефон
   * @var string|null
   */
  protected ?string $phone = null;

  /**
   * @description Электронная почта
   * @var string|null
   */
  protected ?string $email = null;

  /**
   * @description URL логотипа
   * @var string|null
   */
  protected ?string $logo_url = null;

  /**
   * @description Долгота (для центрирования карты)
   * @var float|null
   */
  protected ?float $longitude_center = null;

  /**
   * @description Широта (для центрирования карты)
   * @var float|null
   */
  protected ?float $latitude_center = null;

  /**
   * @description Долгота (для точки на карте)
   * @var float|null
   */
  protected ?float $longitude = null;

  /**
   * @description Широта (для точки на карте)
   * @var float|null
   */
  protected ?float $latitude = null;

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
   * @description Основной ОКВЭД (вид деятельности)
   * @var string|null
   */
  protected ?string $general_activity = null;

  /**
   * @description Дополнительные ОКВЭД (виды деятельности)
   * @var array
   */
  protected array $activities = [];

  /**
   * @description Товары производителя
   * @var Collection
   */
  protected Collection $goods;

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
