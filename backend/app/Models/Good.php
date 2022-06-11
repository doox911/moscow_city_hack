<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Good extends Model implements HasMedia {
  use HasFactory, SoftDeletes, InteractsWithMedia;

  protected $fillable = [
    'id',
    'data_source_id',
    'data_source_item_id',
    'brand',
    'name',
  ];

  /**
   *
   * @return MorphMany
   */
  public function activities(): MorphMany {
    return $this->morphMany(Activity::class, 'activity');
  }

  /**
   * @return BelongsTo
   */
  public function data_source(): BelongsTo {
    return $this->belongsTo(DataSource::class);
  }

  /**
   * @return string
   */
  public function getDirectory(): string {
    return 'good/' . $this->id . '/';
  }
}
