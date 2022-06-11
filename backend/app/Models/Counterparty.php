<?php

namespace App\Models;

use App\Classes\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Counterparty extends Model implements HasMedia {
  use HasFactory, SoftDeletes, InteractsWithMedia;

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

  /**
   * @return string
   */
  public function getDirectory(): string {
    return '/counterparty/' . $this->id . '/';
  }

  /**
   * @param string $url
   * @throws FileCannotBeAdded
   */
  public function saveLogoFromUrl(string $url): void {
    $this->getMedia('company_logo')->each->forceDelete();

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
        ->toMediaCollection('company_logo');
    } catch (FileDoesNotExist | FileIsTooBig $e) {
      Log::info($e->getMessage());
    }
  }

  /**
   * PNG-изображение в формате base64
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
}
