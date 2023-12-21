<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Ban;
use App\Models\Moderator;
use App\Http\Controllers\FileController;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps  = false;

    
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'priv',
        'bio',
        'age'
    ];

    protected $hidden = [
        'email',
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    

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

    public function isModerator(){
        return Moderator::where('id','=',$this->id)->exists();
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'post_likes');
    }

    public function likesPost(Post $post)
    {
        return $this->likes()->where('post_id', $post->id)->exists();
    }

    public function commentsLikes()
    {
        return $this->belongsToMany(Comment::class, 'comment_likes');
    }

    public function likesComment(Comment $comment)
    {
        return $this->commentsLikes()->where('comment_id', $comment->id)->exists();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
    
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'memberships', 'possible_member', 'group_id');
    }

    

    public function friendships()
    {
        return $this->hasMany(Friend::class, 'user_id1');
    }

    public function friends()
    {
        $friends1 = $this->hasMany(Friend::class, 'user_id1')->get()->pluck('user_id2');
        $friends2 = $this->hasMany(Friend::class, 'user_id2')->get()->pluck('user_id1');

        $friends = $friends1->concat($friends2)->unique();

        return User::whereIn('id', $friends)->get();
    }
    
    public function getProfileImage() {
        return FileController::get('profile', $this->id);
    }


    public function isBanned() {
        return Ban::where('user_id', $this->id)->exists(); 
    }
}
