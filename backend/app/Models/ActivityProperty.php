<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

  public function property() {
    return $this->belongsTo(Property::class);
  }

  public function activity() {
    return $this->morphTo('activity');
  }
}
