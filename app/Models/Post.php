<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    public $timestamps  = false;

     /**
     * Get the user that owns the post.
     */
    public  function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }

}
