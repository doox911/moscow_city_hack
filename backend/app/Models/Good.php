<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Good extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'brand',
    'name',
  ];

  /**
   * @return MorphMany
   */
  public function activities(): MorphMany {
    return $this->morphMany(Activity::class, 'activity');
  }
}
