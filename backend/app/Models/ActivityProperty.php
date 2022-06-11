<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityProperty extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'property_id',
    'activity_id',
    'activity_type',
    'value',
    'is_active',
  ];

  /**
   * @return BelongsTo
   */
  public function property(): BelongsTo {
    return $this->belongsTo(Property::class);
  }

  /**
   * @return MorphTo
   */
  public function activity(): MorphTo {
    return $this->morphTo('activity');
  }
}
