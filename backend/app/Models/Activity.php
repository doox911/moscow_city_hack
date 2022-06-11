<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'counterparty_id',
    'activity_id',
    'activity_type',
    'is_active'
  ];

  /**
   * @return BelongsTo
   */
  public function counterparty(): BelongsTo {
    return $this->belongsTo(Counterparty::class);
  }

  /**
   * @return MorphTo
   */
  public function activity(): MorphTo {
    return $this->morphTo('activity');
  }

  public function scopeActive($query) {
    return $query->where('is_active', true);
  }
}
