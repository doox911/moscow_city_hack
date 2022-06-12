<?php

namespace App\Models;

use App\Classes\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

/**
 * @description Модель сущности "Производитель/Контрагент"
 */
class Counterparty extends Model implements HasMedia {
  use HasFactory, SoftDeletes, InteractsWithMedia;

  protected $fillable = [
    'id',
    'data_source_id',
    'data_source_item_id',
    'user_id',
    'name',
    'full_name',
    'inn',
    'ogrn',
    'address',
    'email',
    'phone',
    'site',

    'longitude_center',
    'latitude_center',
    'longitude',
    'latitude',
  ];

  /**
   * Все виды товаров и услуг которые предоставляет производитель
   *
   * @return HasMany
   */
  public function activities(): HasMany {
    return $this->hasMany(Activity::class, 'counterparty_id');
  }

  /**
   * Товары компании
   *
   * @return HasMany
   */
  public function goods(): HasMany {
    return $this->activities()
      ->where('activity_type', Good::class);
  }

  /**
   * Услуги компании
   *
   * @return HasMany
   */
  public function services(): HasMany {
    return $this->activities()
      ->where('activity_type', Service::class);
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
    return '/counterparty/' . $this->id . '/';
  }

  /**
   * Сохраняет логотип компании
   *
   * @param string $url
   * @throws FileCannotBeAdded
   */
  public function saveLogoFromUrl(string $url): void {
    $collection = 'company_logo';

    $this->getMedia($collection)->each->forceDelete();

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

  /**
   * Сохраняет фотографии компании
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
   * Логотип компании в формате base64
   *
   * @return string
   * @throws Exception
   */
  public function getPNGBase64Logo(): string {
    $media = $this->getMedia('company_logo')->first();
    $image_content = '';

    if ($media && file_exists($media->getPath())) {
      $file = new File(['path' => $media->getPath(), 'use_binary' => true]);
      $image_content = 'data:image/png;base64,' . $file->getBase64Content();
    }

    return $image_content;
  }

  /**
   * Фотографии компании в формате base64
   *
   * @return array
   * @throws Exception
   */
  public function getPNGBase64Photos(): array {
    $media_photos = $this->getMedia('company_logo');
    $images_base64 = [];

    foreach ($media_photos as $media_photo) {
      if (file_exists($media_photo->getPath())) {
        $file = new File(['path' => $media_photo->getPath(), 'use_binary' => true]);

        $images_base64[] = 'data:image/png;base64,' . $file->getBase64Content();
      }
    }

    return $images_base64;
  }
}
