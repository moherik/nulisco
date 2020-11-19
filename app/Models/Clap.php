<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clap extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'total'];

    /**
     * Define max clap each post per user
     */
    public const MAX_USER_CLAP = 20;

    /**
     * One to Many inverse relation to User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
