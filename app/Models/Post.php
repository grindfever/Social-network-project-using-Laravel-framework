<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\FileController;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function likes() 
    {
        return $this->belongsToMany(User::class, 'post_likes');
    }
  
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function getPostImage() {
        return FileController::get('post', $this->id);
    }
}
