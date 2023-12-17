<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Friend extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id1',
        'user_id2',
    ];
    
   public function first()
   {
       return $this->belongsTo(User::class, 'user_id1');
   }

   public function second()
   {
       return $this->belongsTo(User::class, 'user_id2');
   }
 
}
