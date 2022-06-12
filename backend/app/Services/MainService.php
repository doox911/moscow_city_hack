<?php

namespace App\Services;

/**
 * Class MainService
 *
 * @package Main
 */
class MainService {

  /**
   * @return void
   */
  public static function setUnlimitExecutionResources(): void {
    ini_set('max_execution_time', '999999999');
    set_time_limit(0);
    ini_set('memory_limit', '-1');
  }

}
