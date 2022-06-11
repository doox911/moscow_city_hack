<?php

namespace App\Helpers;

/**
 * Class ConsoleProgressBar
 */
class ConsoleProgressBar {

  /**
   * Общее количество планируемых итераций
   *
   * @var int
   */
  private static int $total = 0;

  /**
   * @var array
   */
  private static array $operations_periods = [];

  /**
   * Устанавливает сколько единиц считать за 100%
   *
   * @param int $total
   */
  public static function setTotal(int $total): void {
    self::$total = $total;
  }

  /**
   * Устанавливает сколько единиц считать за 100%
   *
   * @experemental "time remaining"
   * @return void
   */
  public static function startOperation(): void {
    self::$operations_periods[] = (object)['start' => time(), 'duration' => 0];
  }

  /**
   * Устанавливает сколько единиц считать за 100%
   *
   * @experemental "time remaining"
   * @return void
   */
  public static function endOperation(): void {
    $last_period_index = count(self::$operations_periods) - 1;

    if ($last_period_index >= 0) {
      $start_timestamp = self::$operations_periods[$last_period_index]->start ?? time();

      self::$operations_periods[$last_period_index]->duration = time() - $start_timestamp;
    }
  }

  /**
   * Консольный прогресс бар
   *
   * @param int $done
   * @param string $text
   */
  public static function updateProgress(int $done, string $text = ''): void {

    /**
     * @experemental "time remaining"
     */
    $avg_sec = null;
    $time_left = null;

    $count_periods = count(self::$operations_periods);
    if ($count_periods > 0) {
      $avg_sec = array_sum(array_column(self::$operations_periods, 'duration')) / $count_periods;

      if ($avg_sec > 0) {
        $avg_min = $avg_sec / 60;
        $time_left = floor((self::$total - $count_periods) * $avg_min);
      }

      if ($time_left !== null) {
        $time_left = "(~$time_left min)";
      }
    }

    $total = self::$total;
    $percent = floor(($done / $total) * 100);
    $left = 100 - $percent;

    $write = sprintf("\033[0G\033[2K[%'={$percent}s>%-{$left}s] - $percent%% - $time_left [$done/$total]\t$text", '', '');
    fwrite(STDOUT, $write);

    if ($done === $total) {
      fwrite(STDOUT, "\n");
    }
  }
}
