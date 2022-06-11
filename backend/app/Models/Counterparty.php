<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Counterparty extends Model {
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'id',
    'user_id',
    'name',
    'full_name',
    'inn',
    'ogrn',
    'address',
    'email',
    'phone',
    'site',
  ];

  /**
   * @return HasMany
   */
  public function activities(): HasMany {
    return $this->hasMany(Counterparty::class);
  }
}
