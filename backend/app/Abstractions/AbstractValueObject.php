<?php

namespace App\Abstractions;

use App\Contracts\IValueObject;
use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Validator;
use JsonException;
use JsonSerializable;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

/**
 * Abstract class AbstractValueObject
 *
 * @description описание базового read-only объекта, паттерн "Value Object(Объект-значение)" из "DDD"
 */
abstract class AbstractValueObject implements IValueObject, JsonSerializable {

  /**
   * This is used to fetch readonly variables
   *
   * @param string $property
   * @return mixed
   */
  final public function __get(string $property) {
    // TODO: подумать нужна ли тут проверка на доступность свойства,
    //  хотя по идее у Value-объекта должны быть все свойства публичные
    //  Можно добавить проверку на приватность свойства
    //$public_properties = call_user_func('get_object_vars', $this);
    //$is_public = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);

    $property_exists = array_key_exists($property, $this->toArray());
    if (!$property_exists) {
      throw new RuntimeException('Call undefined property `' . $property . '` an object ' . static::class);
    }

    return $this->{$property};
  }

  /**
   * @param string $name
   * @param $value
   */
  final public function __set(string $name, $value): void {
    // empty body
  }

  /**
   * @param string $property
   * @return bool
   */
  final public function __isset(string $property): bool {
    return array_key_exists($property, $this->toArray());
  }

  /**
   * @param string $property
   */
  final public function __unset(string $property) {
    // empty body
  }

  /**
   * TODO Make recursive: convert to array nested VOs
   *
   * @return array
   */
  final public function toArray(): array {
    $result = [];

    foreach ($this as $key => $value) {
      $result[$key] = $value;
    }

    return $result;
  }

  /**
   * @return array
   */
  final public function jsonSerialize(): array {
    return $this->toArray();
  }

  /**
   * Проверяет данные на соответствие требованиям по типизации и набору полей
   *
   * @param mixed $data
   * @return bool
   * @throws ValidationException|JsonException
   */
  public function validate($data): bool {
    $validator = Validator::make($data, static::getRules(), static::getMessagesByRules());

    if ($validator->fails()) {
      $validation_exception = new ValidationException(
        static::class . ' validation error',
        static::class . ' validation error in ValueObject'
      );

      $error_messages = $validator->errors()->getMessages();
      $messages = [];

      foreach ($error_messages as $field => $field_messages) {
        array_push($messages, ...$field_messages);
      }

      $messages[] = 'Present data (in json format): ' . json_encode($data, JSON_THROW_ON_ERROR);

      foreach (debug_backtrace() as $trace) {

        try {
          $messages[] = $trace['line'] . ': ' . $trace['file'];
        } catch (\Exception $e) {
        }
      }

      $validation_exception->addSystemMessages($messages);

      throw $validation_exception;
    }

    return true;
  }

  /**
   * Rules based on property typing
   *
   * @return string[]
   */
  protected static function getRules(): array {
    $rules = [];

    $class = new ReflectionClass(static::class);
    $props = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
    foreach ($props as $property) {
      $property->setAccessible(true);

      /**
       * @see https://www.php.net/manual/ru/class.reflectiontype.php
       * @var ReflectionNamedType
       */
      $type_object = $property->getType();

      $type_name = $type_object->getName();

      $rule = '';

      // TODO: добавить валидацию на произвольные тип типа Collection

      /**
       * @tutorial https://www.php.net/manual/ru/reflectionproperty.hasdefaultvalue.php
       */
      $has_default_value = false;
      if ($has_default_value === false) {
        $rule .= 'present|';
      }

      if ($type_name === 'int' || $type_name === 'float') {
        $rule .= 'numeric';
      } else if ($type_name === 'string') {
        $rule .= 'string';
      } else if ($type_name === 'array') {
        $rule .= 'array';
      } else if ($type_name === 'bool') {
        $rule .= 'boolean';
      }

      /**
       * @tutorial https://www.php.net/manual/ru/reflectionparameter.allowsnull.php
       *    Внимание
       *    К настоящему времени эта функция еще не была документирована;
       *    для ознакомления доступен только список аргументов.
       */
      if ($type_object->allowsNull()) {
        $rule .= '|nullable';
      }

      $rules[$property->name] = $rule;
    }

    return $rules;
  }

  /**
   * Messages for rules
   *
   * @return string[]
   */
  protected static function getMessagesByRules(): array {
    $rules = [];

    $class = new ReflectionClass(static::class);
    $props = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

    foreach ($props as $property) {
      $property->setAccessible(true);

      /**
       * @see https://www.php.net/manual/ru/class.reflectiontype.php
       * @var ReflectionNamedType
       */
      $type_object = $property->getType();

      $type_name = $type_object->getName();

      $rules[$property->name . '.required'] = 'Field `' . $property->name . '` is required';
      $rules[$property->name . '.present'] = 'Field `' . $property->name . '` must be present';

      if ($type_name === 'int') {
        $rules[$property->name . '.numeric'] = 'Field `' . $property->name . '` must be an numeric (int)';
      } else if ($type_name === 'float') {
        $rules[$property->name . '.numeric'] = 'Field `' . $property->name . '` must be an numeric (float)';
      } else if ($type_name === 'string') {
        $rules[$property->name . '.string'] = 'Field `' . $property->name . '` must be an string';
      }
    }

    return $rules;
  }
}
