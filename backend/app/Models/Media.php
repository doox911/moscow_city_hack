<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends \Spatie\MediaLibrary\MediaCollections\Models\Media {
  use SoftDeletes;
}