<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

trait Sluggable
{
    abstract public function slugify(): string;

    public static function bootSluggable()
    {
        static::saving(function ($model) {
            $model->slug = Str::slug($model->{$model->slugify()});
            return true;
        });
    }
}
