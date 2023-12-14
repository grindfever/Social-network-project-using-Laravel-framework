<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Group extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $table = 'groups';

    protected $fillable = ['owner', 'name', 'description', 'members'];

   
    public function members()
    {
    return $this->belongsToMany(User::class, 'memberships', 'group_id', 'member');
    }

    protected $casts = [
        'members' => 'json',
    ];
}
