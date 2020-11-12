<?php

namespace App\Models;

use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = ['title', 'slug', 'description'];

    public function slugify(): string
    {
        return 'title';
    }
}
