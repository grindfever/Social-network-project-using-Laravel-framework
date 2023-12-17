<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class FriendRequest extends Model
{
    protected $table = 'friend_requests';
    protected $primaryKey = ['sender', 'receiver'];
    public $incrementing = false; // Disable auto-incrementing for composite primary key

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
    public function accept()
    {
      // Update the friend request status
      $this->accepted = true;
      $this->accept_date = now();

      static::where('sender', $this->sender)
      ->where('receiver', $this->receiver)
      ->update([
          'accepted' => true,
          'accept_date' => now(),
      ]);
        // Create a friendship entry in the 'friends' table
        DB::table('friends')->insert([
            'user_id1' => $this->sender,
            'user_id2' => $this->receiver,
        ]);

        DB::table('friends')->insert([
            'user_id1' => $this->receiver,
            'user_id2' => $this->sender,
        ]);
    }
    public function reject()
    {
        static::where('sender', $this->sender)
               ->where('receiver', $this->receiver)
               ->delete();
    }
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

}