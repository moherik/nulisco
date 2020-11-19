<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * One to Many inverse relation to self,
     * Comment has reply comment
     */
    public function hasComments()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * One to Many inverse relation to User model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
