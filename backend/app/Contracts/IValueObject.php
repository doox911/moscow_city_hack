<?php

namespace App\Contracts;

/**
 * Interface IValueObject
 *
 * @description
 */
interface IValueObject {

  /**
   * IValueObject constructor.
   *
   * @param mixed $data
   */
  public function __construct($data);

  /**
   * @param mixed $data
   * @return bool
   */
  public function validate($data): bool;

  /**
   * @return array
   */
  public function toArray(): array;
}
