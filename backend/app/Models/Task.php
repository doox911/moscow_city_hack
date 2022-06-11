<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JsonException;

class Task extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'user_id',
    'entity_id',
    'entity_type',
    'value',
    'is_moderated',
    'is_accepted',
    'comment',
  ];

  protected $with = [
    'user',
  ];

  public function entity() {
    return $this->morphTo('entity');
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  /**
   * Для работы с полем json формата
   *
   * @param $value
   * @return array
   */
  public function getValueAttribute($value): array {
    return json_decode($value, true);
  }

  /**
   * Для работы с полем json формата
   *
   * @param array $value
   * @throws JsonException
   */
  public function setValueAttribute(array $value): void {
    $this->attributes['value'] = json_encode($value, JSON_THROW_ON_ERROR, 512);
  }
}
