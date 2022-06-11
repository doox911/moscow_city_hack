<?php

namespace App\Exceptions;

use App\Abstractions\Exception\AbstractException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Throwable;
use function explode;

/**
 * Class CommonException
 */
class CommonException extends AbstractException {

  /**
   * @var string
   */
  protected static string $log_channel = 'single';

  /**
   * Вывод ошибок
   *
   * @throws HttpResponseException
   */
  public function exportFails(): void {
    Log::channel(static::$log_channel)->critical($this->getSystemMessage());

    response()->json([
      'messages' => $this->user_messages,
      'errors' => $this->getSystemMessages(),
    ], 500)->throwResponse();
  }

  /**
   * Вывод ошибок
   *
   * @param string $um - Сообщение для пользователя
   * @param Throwable $e - Объект исключения
   * @param int $ec - код ошибки
   * @throws HttpResponseException
   */
  public static function exportException(string $um, Throwable $e, int $ec = 500): void {
    Log::channel(static::$log_channel)->critical($e->getMessage());

    response()->json([
      'messages' => [$um],
      'errors' => explode(',', $e->getMessage()),
      'trace' => $e->getTrace()
    ], $ec)->throwResponse();
  }

  /**
   * @return void
   */
  public function render(): void {
    $this->exportFails();
  }
}
