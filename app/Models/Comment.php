<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    public $timestamps  = false;


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}