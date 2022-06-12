<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
    'description',
    'price',
    'price_description',
    'keyword_for_search',
    'data_source_item_url',
    'data_source_item_last_edit',
    'price_min_party',
    'properties',
  ];

  protected $with = [
    'data_source',
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
   * Компании которые производят товар
   *
   * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
   */
  public function companies(): HasManyThrough {
    return $this->hasManyThrough(Counterparty::class, Activity::class);
  }

  /**
   * @return string
   */
  public function getDirectory(): string {
    return 'good/' . $this->id . '/';
  }
}
