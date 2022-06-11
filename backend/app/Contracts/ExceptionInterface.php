<?php

namespace App\Contracts;

use Exception;
use Throwable;

/**
 * Interface ExceptionInterface
 */
interface ExceptionInterface {

  /**
   * @description ExceptionInterface constructor.
   * @param string $user_message
   * @param string $system_message
   * @param int $code
   * @param Exception|null $previous
   */
  public function __construct(string $user_message, string $system_message, int $code = 500, Exception $previous = null);

  /**
   * @description Get system message from exception
   * @return string
   */
  public function getSystemMessage(): string;

  /**
   * @description Добавляет одно сообщение в список сообщений исключения
   * @param string $um
   */
  public function addMessage(string $um): void;

  /**
   * @description Возвращает пользовательские сообщения исключения
   * @return array
   */
  public function getMessages(): array;

  /**
   * @description Устанавливает дополнительные параметры для исключения
   * @param array $params
   */
  public function setParams(array $params): void;

  /**
   * @description Export exception to front-end
   */
  public function exportFails(): void;

  /**
   * @description Static export exception when catch default Exception
   * @param string $um
   * @param Throwable $e
   * @param int $ec
   */
  public static function exportException(string $um, Throwable $e, int $ec = 500): void;
}
