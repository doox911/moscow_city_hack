<?php

namespace App\Classes;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

/**
 * Переопределение путей хранения файлов прикреплённых к моделям
 */
class CustomPathGenerator extends DefaultPathGenerator {

  /**
   * @param Media $media
   * @return string
   */
  public function getPath(Media $media): string {
    return $media->model->getDirectory();
  }

  /**
   * @param Media $media
   * @return string
   */
  public function getPathForConversions(Media $media): string {
    return $this->getPath($media) . 'conversions/';
  }

  /**
   * @param Media $media
   * @return string
   */
  public function getPathForResponsiveImages(Media $media): string {
    return $this->getPath($media) . 'responsive/';
  }

}
