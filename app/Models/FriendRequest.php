<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class FriendRequest extends Model
{
    protected $table = 'friend_requests';

    protected $casts = [
        'sender' => 'integer',
        // Add other cast definitions as needed
    ];
      
    protected $fillable = [
        'sender', 'receiver', 'accepted', 'request_date', 'accept_date'
    ];

    // Disable default timestamps
    public $timestamps = false;

    // Map Eloquent timestamps to custom columns
    const CREATED_AT = 'request_date';
    const UPDATED_AT = 'accept_date';

    // Override the create method to handle composite primary key
    public static function create(array $attributes = [])
    {
        $model = new static($attributes);
    
        // Get the array of attributes and unset any 'id' key
        $attributes = $model->getAttributes();
        unset($attributes['id']);
    
        // Insert the record into the database
        static::insert([$attributes]);
    
        return $model;
    }
  
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver');
    }

    public function accept()
    {
        $this->update([
            'accepted' => true,
            'accept_date' => now(),
        ]);
    }

    public function reject()
    {
        $this->delete();
        
    }

}