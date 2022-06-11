<?php

namespace App\Models;

use App\Classes\File;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
   * PNG-изображение в формате base64
   *
   * @return string
   * @throws Exception
   */
  public function getPNGBase64Logo(): string {
    $media = $this->getMedia('logo')->first();
    $image_content = '';

    if ($media && file_exists($media->getPath())) {
      $file = new File(['path' => $media->getPath(), 'use_binary' => true]);
      $image_content = 'data:image/png;base64,' . $file->getBase64Content();
    }

    return $image_content;
  }
}
