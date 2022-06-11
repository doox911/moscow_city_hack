<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Интерфейс для классов парсера
 * М - Масштабируемость
 */
interface IParser {

  /**
   * function for parse
   *
   * @return \Illuminate\Support\Collection
   */
  public function parse(): Collection;
}
