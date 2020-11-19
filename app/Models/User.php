<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * One to Many relation to Provider model
     */
    public function providers()
    {
        return $this->hasMany(Provider::class);
    }

    /**
     * One to Many relation to Post model
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Many to Many relation to Post model using PostBookmark pivot model
     */
    public function bookmarks()
    {
        return $this->belongsToMany(Post::class, 'post_bookmark', 'user_id', 'post_id');
    }
}
