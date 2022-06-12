<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Услуги
 */
class Service extends Model implements HasMedia {
  use HasFactory, InteractsWithMedia;

  protected $table = 'class_okved';

  protected $fillable = [
    'id',
    'code',
    'name',
    'additional_info'
  ];

  /**
   * @return MorphMany
   */
  public function activities(): MorphMany {
    return $this->morphMany(Activity::class, 'activity');
  }

  /**
   * @return string
   */
  public function getDirectory(): string {
    return 'service/' . $this->id . '/';
  }
}
