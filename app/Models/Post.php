<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'slug', 'body', 'status'];

    /**
     * Get description of content
     */
    public function getShortTitleAttribute()
    {
        return Str::words($this->attributes['title'], 10, '...');
    }

    /**
     * Get description of content
     */
    public function getDescAttribute()
    {
        return Str::limit(strip_tags($this->attributes['body']), 100, '...');
    }

    /**
     * Format date
     */
    public function getDateAttribute()
    {
        return $this->created_at->format('d, M Y');
    }

    /**
     * One to Many inverse relation to User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * One to Many relation to Clap model
     */
    public function claps()
    {
        return $this->hasMany(Clap::class);
    }

    /**
     * Total clap attribute
     */
    public function getTotalClapAttribute()
    {
        return $this->hasMany(Clap::class)->sum('total');
    }

    /**
     * One to Many relation to Comment model
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Many to Many relation to Tag model
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    /**
     * Many to Many relation to User model with Post Bookmark pivot
     */
    public function bookmarks()
    {
        return $this->belongsToMany(User::class, 'post_bookmark', 'post_id', 'user_id');
    }
}
