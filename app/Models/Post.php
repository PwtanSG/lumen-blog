<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    public function getUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
        //return $this->belongsTo(User::class);
    }
}
