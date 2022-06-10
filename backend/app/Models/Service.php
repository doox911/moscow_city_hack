<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Услуги
 */
class Service extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'code',
    'name',
  ];

  public function activities() {
    return $this->morphMany(Activity::class, 'activity');
  }
}
