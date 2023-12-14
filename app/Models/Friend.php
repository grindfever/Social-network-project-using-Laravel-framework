<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    use HasFactory;

    protected $table = 'friends';

    protected $primaryKey = ['userid1', 'userid2'];

    public $incrementing = false; // Disable auto-incrementing for composite primary key

    protected $fillable = [
        'userid1', 'userid2',
    ];

    public function friendUser()
    {
        return $this->belongsTo(User::class, 'userid1');
    }
 
}
