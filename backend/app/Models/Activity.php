<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

  public function counterparty() {
    return $this->belongsTo(Counterparty::class);
  }

  public function activity() {
    return $this->morphTo('activity');
  }

  public function scopeActive($query) {
    return $query->where('is_active', true);
  }
}
