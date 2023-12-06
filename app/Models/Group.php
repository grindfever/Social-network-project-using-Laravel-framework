<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $table = 'groups';

    protected $fillable = ['owner', 'name', 'description', 'members'];

    // Define relationships
    public function memberships()
    {
    return $this->hasMany(Membership::class, 'group_id');
    }

    // Define casts for array columns
    protected $casts = [
        'members' => 'json',
    ];
}
