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
    public $incrementing = false; 
    protected $fillable = [
        'sender', 'receiver', 'accepted', 'request_date', 'accept_date'
    ];
 
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

      
        DB::table('friends')->insert([
            'user_id1' => $this->sender,
            'user_id2' => $this->receiver,
        ]);

        DB::table('friends')->insert([
            'user_id1' => $this->receiver,
            'user_id2' => $this->sender,
        ]);
        
        static::where('sender', $this->sender)
        ->where('receiver', $this->receiver)
        ->delete();
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
    
        $attributes = $model->getAttributes();
        unset($attributes['id']);
    

        static::insert([$attributes]);
    
        return $model;
    }

}