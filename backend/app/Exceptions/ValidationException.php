<?php

namespace App\Exceptions;

use Illuminate\Validation\Validator;

/**
 * Class ValidationException
 */
class ValidationException extends CommonException {

  /**
   * @param Validator $validator
   * @return ValidationException
   */
  public static function newFromValidator(Validator $validator): ValidationException {
    $validation_exception = new ValidationException(
      'Validation Errors',
      'Validation Errors',
    );

    foreach ($validator->messages()->get('*') as $field_errors) {
      $validation_exception->addMessages($field_errors);
    }

    return $validation_exception;
  }
}
