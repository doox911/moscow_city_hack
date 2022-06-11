<?php

namespace App\Traits;

use ReflectionClass;
use ReflectionProperty;

trait ValueObjectHelperTrait {

  /**
   * Инициализирует свойства объекта из массива
   *
   * @param array $data
   * @return void
   * @see \App\Abstractions\AbstractValueObject
   */
  public function initializePropertiesFromArray(array $data): void {
    $class = new ReflectionClass(static::class);
    $props = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

    foreach ($props as $property) {
      $name = $property->getName();

      if (array_key_exists($name, $data)) {
        $this->{$name} = $data[$name];
      }
    }
  }

  /**
   * Initialize default input data values if they are not defined
   *
   * @param array $data
   * @param array $default_data
   */
  public function initializeDefaultValues(array &$data, array $default_data): void {
    $class = new ReflectionClass(static::class);
    $props = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

    foreach ($props as $property) {
      $name = $property->getName();

      if (array_key_exists($name, $default_data) && !isset($data[$name])) {
        $data[$name] = $default_data[$name];
      }
    }
  }
}
