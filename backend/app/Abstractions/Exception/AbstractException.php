<?php

namespace App\Abstractions\Exception;

use App\Contracts\ExceptionInterface;
use Exception;
use Throwable;

/**
 * Class AbstractException
 */
abstract class AbstractException extends Exception implements ExceptionInterface {

  /**
   * @var string
   */
  protected string $user_message;

  /**
   * @var string
   */
  protected string $system_message;

  /**
   * @var Exception|null
   */
  protected ?Exception $previous;

  /**
   * @var array of string
   */
  protected array $user_messages = [];

  /**
   * @var array of string
   */
  protected array $system_messages = [];

  /**
   * @var array
   */
  protected array $params = [];

  /**
   * AbstractException constructor.
   *
   * @param string $user_message
   * @param string $system_message
   * @param int $code
   * @param Exception|null $previous
   */
  public function __construct(string $user_message, string $system_message, int $code = 500, Exception $previous = null) {
    $this->user_message = $user_message;
    $this->system_message = $system_message;
    $this->code = $code;
    $this->previous = $previous;

    if (!empty($user_message)) {
      $this->user_messages = explode(',', $this->user_message);
    }

    parent::__construct($this->user_message, $this->code, $this->previous);
  }

  /**
   * @description Get system message from exception
   * @return string
   */
  final public function getSystemMessage(): string {
    return $this->system_message;
  }

  /**
   * @description Добавляет одно сообщение в список сообщений исключения
   * @param string $um
   */
  final public function addMessage(string $um): void {
    $this->user_messages[] = $um;
  }

  /**
   * @description Добавляет сообщения в список сообщений исключения
   * @param array $user_messages
   */
  final public function addMessages(array $user_messages): void {
    foreach ($user_messages as $um) {
      $this->addMessage($um);
    }
  }

  /**
   * @description Добавляет одно сообщение в список системных сообщений исключения
   * @param string $sm
   */
  final public function addSystemMessage(string $sm): void {
    $this->system_messages[] = $sm;
  }

  /**
   * @description Добавляет сообщения в список сообщений исключения
   * @param array $system_messages
   */
  final public function addSystemMessages(array $system_messages): void {
    foreach ($system_messages as $sm) {
      $this->addSystemMessage($sm);
    }
  }

  /**
   * @description Возвращает пользовательские сообщения исключения
   * @return array
   */
  final public function getMessages(): array {
    return $this->user_messages;
  }

  /**
   * @description Возвращает системные сообщения исключения
   * @return array
   */
  final public function getSystemMessages(): array {
    return $this->system_messages;
  }

  /**
   * @description Устанавливает дополнительные параметры для исключения
   * @param array $params
   */
  final public function setParams(array $params): void {
    $this->params[] = $params;
  }

  /**
   * @description Export exception to front-end
   */
  abstract public function exportFails(): void;

  /**
   * @description Static export exception when catch default Exception
   * @param string $um
   * @param Throwable $e
   * @param int $ec
   */
  abstract public static function exportException(string $um, Throwable $e, int $ec = 500): void;
}
