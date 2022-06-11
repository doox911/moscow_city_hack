<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Okved extends Model {
  use HasFactory;

  protected $table = 'class_okved';

  protected $fillable = [
    'id',
    'name',
    'code',
    'additional_info',
    'parent_id',
    'parent_code',
    'node_count',
  ];
}
