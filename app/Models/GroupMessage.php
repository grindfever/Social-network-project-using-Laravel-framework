<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupMessage extends Model
{
    protected $fillable = ['group_id', 'sender', 'content', 'date', 'viewed', 'img'];
    public $timestamps = false;

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender', 'id');
    }
}
