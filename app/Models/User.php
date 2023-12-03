<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'email',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the cards for a user.
     */

    public function messages()
    {
        $sentMessages = $this->sentMessages();
        $receivedMessages = $this->receivedMessages();
        $messages = array();
        foreach ($sentMessages as $message) {   
            $messages[] = $message;
        }
        foreach ($receivedMessages as $message) {   
            $messages[] = $message;
        }
        return $messages;
    } 
    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender');  
    }

    public function receivedMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'receiver');  
    }

    public function chats(){
        return $this->hasMany(Message::class,'sender', 'receiver');
    }
    

    public  function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'post_likes');
    }

    public function likesPost(Post $post)
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }
    
}
