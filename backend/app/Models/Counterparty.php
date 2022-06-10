<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counterparty extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'name',
    'inn',
    'ogrn',
    'address',
    'email',
    'phone',
  ];

  public function activities() {
    return $this->hasMany(Counterparty::class);
  }
}
