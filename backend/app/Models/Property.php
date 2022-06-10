<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'name'
  ];

  public function activities() {
    return $this->hasMany(ActivityProperty::class);
  }
}
