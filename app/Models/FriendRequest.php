<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class FriendRequest extends Model
{
    protected $table = 'friend_requests';
      
    protected $fillable = [
        'sender', 'receiver', 'accepted', 'request_date', 'accept_date'
    ];


    // Map Eloquent timestamps to custom columns
    const CREATED_AT = 'request_date';
    const UPDATED_AT = 'accept_date';

 
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender');
    }
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver');
    }
 
}