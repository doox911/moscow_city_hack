<?php

namespace App\Classes;

use Exception;

use function fopen;
use function array_key_exists;
use function in_array;
use function sys_get_temp_dir;
use function filesize;
use function file_exists;
use function stream_get_meta_data;
use function is_resource;
use function fseek;
use function fread;
use function fwrite;
use function fclose;
use function pathinfo;
use function unlink;
use function clearstatcache;
use function base64_encode;
use function str_replace;

class File {

  /**
   * Имя файла по умолчанию
   *
   * @var string
   */
  private static string $DEFAULT_NAME = 'default';

  /**
   * Флаг бинарного режима
   *
   * @var string
   */
  private static string $B = 'b';

  /**
   * Дескриптор
   *
   * @var null
   */
  protected $f = null;

  /**
   * Имя файла
   *
   * @var string
   */
  protected string $fn = '';

  /**
   * Тип файла
   *
   * @var string
   */
  protected string $ft = '';

  /**
   * Директория файла
   *
   * @var string
   */
  protected string $fd = '';

  /**
   * Полный путь к файлу
   *
   * @var string
   */
  protected string $fp = '';

  /**
   * Режим в котором открыт файл
   *
   * @var string
   */
  protected string $fm = '';

  /**
   * Автоматическое удаление после закрытия
   *
   * @var bool
   */
  protected bool $auto_remove = false;

  /**
   * Включить бинарный режим.
   * Необходимо использовать при работе не текстовой информацией.
   *
   * @var bool
   */
  protected bool $use_b = false;

  /**
   * Доступные режимы открытия файла
   *
   * @var array
   */
  protected static array $mode = [
    'r' => 'r',  // Открывает файл только для чтения; помещает указатель в начало файла
    'r+' => 'r+', // Открывает файл для чтения и записи; помещает указатель в начало файла
    'w' => 'w',  // Открывает файл только для записи; помещает указатель в начало файла и обрезает файл до нулевой длины. Если файл не существует - пробует его создать
    'w+' => 'w+', // Открывает файл для чтения и записи; помещает указатель в начало файла и обрезает файл до нулевой длины. Если файл не существует - пытается его создать
    'a' => 'a',
    'a+' => 'a+',
    'x' => 'x',
    'x+' => 'x+',
    'c' => 'c',
    'c+' => 'c+',
    'e' => 'e'
  ];

  /**
   * File constructor
   * Варианты создания файла:
   * 1. Если не передать параметров, то будет создан файл с дефолтным именем без расширения. Аналогично временному файлу
   * 2. Если передать только параметры, то будет создан файл по параметрам.
   * 3. Если передать параметры и установить флаг создания в false, то можно использовать как фабрику.
   * 3.1 При использовании фабрики можно запретить автоудаление.
   *
   * @param array $params
   * @param bool $open
   * @param bool $auto_remove
   * @throws Exception
   */
  public function __construct(array $params = [], bool $open = true, bool $auto_remove = false) {
    $this->auto_remove = $auto_remove;
    $this->initParameters($params);

    if ($open) {
      $this->open();
    }
  }

  /**
   * Открыть файл
   *
   * @return File
   */
  protected function open(): self {
    // Если запрашиваемого файла нет и установлен режим открытия файла только на чтение
    // Устанавливаем флаг открытия файла на запись
    if (!$this->isExists() && $this->fm === self::$mode['r']) {
      $this->fm = self::$mode['w+'];
    }

    // Если используем бинарный режим, то дописываем флаг
    $mode = $this->use_b ? $this->fm . self::$B : $this->fm;

    $this->f = fopen($this->fp, $mode);

    return $this;
  }

  /**
   * Публичный метод создания или открытия файла
   *
   * @param string $path - абсолютный путь к файлу
   * @return File
   */
  public function create(string $path = ''): self {
    $this->setAbsoluteFilePath($path)->open();

    return $this;
  }

  /**
   * Инициализация параметров
   *
   * @param array $params
   * @return File
   */
  protected function initParameters(array $params): self {

    // Режим открытия файла
    $mode = $params['mode'] ?? $mode = self::$mode['r'];

    // Имя файла
    $name = $params['name'] ?? self::$DEFAULT_NAME;

    // Тип файла
    $type = $this->ft;
    if (array_key_exists('type', $params)) {
      $type = '.' . $params['type'];
    }

    // Директория
    $dir = $params['dir'] ?? sys_get_temp_dir();

    if (array_key_exists('use_binary', $params)) {
      $this->setUseBinary($params['use_binary']);
    }

    $this
      ->setMode($mode)
      ->setName($name)
      ->setType($type)
      ->setDirectory($dir)
      ->setFilePath();

    if (array_key_exists('path', $params)) {
      $this->setAbsoluteFilePath($params['path']);
    }

    return $this;
  }

  /**
   * Размер файла
   *
   * @return int
   */
  protected function size(): int {
    return filesize($this->uri());
  }

  /**
   * Унифицированный (единообразный) идентификатор ресурса
   *
   * @return string
   */
  protected function uri(): string {
    return stream_get_meta_data($this->f)['uri'];
  }

  /**
   * Закрыть файл
   *
   * @return void
   */
  public function close(): void {
    if (is_resource($this->f)) {
      fclose($this->f);
    }
  }

  /**
   * Helper
   * Удалить файл по абсолютному пути
   *
   * @param string $path
   * @return bool
   * @throws Exception
   */
  public static function removeFileByPath(string $path): bool {
    $deleted = false;
    if (self::isExistsPath($path)) {
      $deleted = unlink($path);

      if (!$deleted) {
        throw new Exception("Can't delete file");
      }
    }

    return $deleted;
  }

  /**
   * Деструктор
   *
   * @throws Exception
   */
  public function __destruct() {
    $this->close();
    if ($this->auto_remove) {
      self::removeFileByPath($this->fp);
    }
  }

  // --------------------------------------------------------------------------
  // -----------------------   Установка значений   ---------------------------
  // --------------------------------------------------------------------------

  /**
   * Установить имя файла
   *
   * @param string $name
   * @return File
   */
  public function setName(string $name): self {
    $this->fn = $name;

    return $this;
  }

  /**
   * Установить тип файла
   *
   * @param string $type
   * @return File
   */
  public function setType(string $type): self {
    $this->ft = $type;

    return $this;
  }

  /**
   * Установить режим файла
   *
   * @param string $mode
   * @return File
   */
  public function setMode(string $mode): self {

    // По умолчанию ставим режим чтения и записи (r+)
    $this->fm = self::ifExistsMode($mode) ? $mode : self::$mode['r+'];

    return $this;
  }

  /**
   * Установить директорию
   *
   * @param string $directory
   * @return File
   */
  public function setDirectory(string $directory): self {
    $this->fd = $directory;

    return $this;
  }

  /**
   * Установить полный путь к файлу
   *
   * @return File
   */
  private function setFilePath(): self {
    $this->fp = $this->fd . '/' . $this->fn . $this->ft;

    return $this;
  }

  /**
   * Установить абсолютный путь к файлу
   *
   * @param string $path
   * @return File
   */
  private function setAbsoluteFilePath(string $path = ''): self {
    if (!empty($path)) {
      $path_parts = pathinfo($path);

      // Имя
      $this->fn = empty($path_parts['filename']) ? self::$DEFAULT_NAME : $path_parts['filename'];

      // Тип
      $this->ft = $path_parts['extension'] ?? $this->ft;

      // Директория
      $this->fd = $path_parts['dirname'];

      // Абсолютный путь
      $this->fp = str_replace('//', '/', $this->fd . '/' . $this->fn . '.' . $this->ft);
    }

    return $this;
  }

  /**
   * Установить/отключить бинарный режим
   *
   * @param bool $use
   * @return File
   */
  private function setUseBinary(bool $use): self {
    $this->use_b = $use;

    return $this;
  }

  /**
   * Добавляет содержимое в файл
   *
   * @param string $data
   * @return File
   */
  public function appendContent(string $data): self {
    fwrite($this->f, $data);

    return $this;
  }

  // --------------------------------------------------------------------------
  // -----------------------   Получение значений   ---------------------------
  // --------------------------------------------------------------------------

  /**
   * Получить имя файла
   *
   * @return string
   */
  public function getName(): string {
    return $this->fn;
  }

  /**
   * Получить расширение файла
   *
   * @return string
   */
  public function getType(): string {
    return $this->ft;
  }

  /**
   * Получить директорию
   *
   * @return string
   */
  public function getDirectory(): string {
    return $this->fd;
  }

  /**
   * Получить содержимое файла
   *
   * @return string
   */
  public function getContent(): string {
    fseek($this->f, 0);

    return fread($this->f, $this->size());
  }

  /**
   * Получить содержимое файла в Base64
   *
   * @return string
   */
  public function getBase64Content(): string {
    return base64_encode($this->getContent());
  }

  /**
   * Получить дескриптор
   */
  public function getHandle() {
    return $this->f;
  }

  /**
   * Получить путь к файлу (определённый в классе)
   *
   * @return string
   */
  public function getPath(): string {
    return $this->fp;
  }

  /**
   * Получить унифицированный (единообразный) идентификатор ресурса
   *
   * @return string
   */
  public function getUri(): string {
    return $this->uri();
  }

  /**
   * Получить размера файла
   *
   * @return int
   */
  public function getSize(): int {
    return $this->size();
  }

  // --------------------------------------------------------------------------
  // --------------------------   Валидация   ---------------------------------
  // --------------------------------------------------------------------------

  /**
   * Проверяет существование файла
   *
   * @return bool
   */
  public function isExists(): bool {
    return self::isExistsPath($this->fp);
  }

  /**
   * Проверяет существование файла по пути
   *
   * @param string $path - путь
   * @return bool
   */
  public static function isExistsPath(string $path): bool {
    clearstatcache();

    return file_exists($path);
  }

  /**
   * Проверяет наличие режима файла
   *
   * @param string $mode
   * @return bool
   */
  protected static function ifExistsMode(string $mode): bool {
    return in_array($mode, self::$mode, true);
  }

}
