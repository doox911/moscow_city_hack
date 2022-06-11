<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest {

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, mixed>
   */
  public function rules() {
    return [
      'entity_id' => 'numeric|present',
      'entity_type' => 'string|required',
      'value' => 'array|required',
      'value.method' => 'string|required',
      'value.data' => 'array|required'
    ];
  }

  /**
   * Messages
   *
   * @return array
   */
  public function messages(): array {
    return [
      'entity_id.required' => 'Поле "Идентификатор сущности" обязательно для заполнения',
      'entity_type.required' => 'Поле "Тип сущности" обязательно для заполнения',
      'value.required' => 'Поле "Значение" введено некорректно',
      'entity_id.numeric' => 'Поле "Идентификатор сущности" должно быть числовым',
      'entity_type.string' => 'Поле "Тип сущности" должен быть строковым',
      'value.array' => 'Поле "Значение" должно быть массивом',
    ];
  }
}
