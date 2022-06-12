<?php

namespace App\Models;

use App\Classes\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use JsonException;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

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
   * @return HasManyThrough
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

  /**
   * Сохраняет фотографии товара
   *
   * @param array $urls
   * @throws FileCannotBeAdded
   */
  public function savePhotosFromUrlArray(array $urls): void {
    $collection = 'photos';
    $this->getMedia($collection)->each->forceDelete();

    foreach ($urls as $url) {
      // добавляем время в название файла, для уникальности,
      // если в один день загрузят несколько файлов чтобы они не затирали друг друга
      $current_time = Carbon::now()->format('H_i');
      $path_info = pathinfo($url);
      $filename = $path_info['filename'] . "_$current_time." . $path_info['extension'];

      try {
        $this
          ->addMediaFromUrl($url)
          ->usingName($filename)
          ->usingFileName($filename)
          ->toMediaCollection($collection);
      } catch (FileDoesNotExist | FileIsTooBig $e) {
        Log::info($e->getMessage());
      }
    }
  }

  /**
   * Фотографии товара в формате base64
   *
   * @return array
   * @throws Exception
   */
  public function getPNGBase64Photos(): array {
    $collection = 'photos';
    $media_photos = $this->getMedia($collection);
    $images_base64 = [];

    foreach ($media_photos as $media_photo) {
      if (file_exists($media_photo->getPath())) {
        $file = new File(['path' => $media_photo->getPath(), 'use_binary' => true]);

        $images_base64[] = 'data:image/png;base64,' . $file->getBase64Content();
      }
    }

    return $images_base64;
  }

  /**
   * @param $value
   * @return object
   */
  public function getKeywordsForSearchAttribute($value): object {
    return (object)json_decode($value);
  }

  /**
   * @param object $value
   * @throws JsonException
   */
  public function setKeywordsForSearchAttribute(object $value): void {
    $this->attributes['keywords_for_search'] = json_encode($value, JSON_THROW_ON_ERROR, 512);
  }

  /**
   * @param $value
   * @return object
   */
  public function getPropertiesAttribute($value): object {
    return (object)json_decode($value);
  }

  /**
   * @param object $value
   * @throws JsonException
   */
  public function setPropertiesAttribute(object $value): void {
    $this->attributes['properties'] = json_encode($value, JSON_THROW_ON_ERROR, 512);
  }
}
