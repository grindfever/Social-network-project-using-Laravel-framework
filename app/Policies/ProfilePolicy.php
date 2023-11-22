<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Card;

use Illuminate\Support\Facades\Auth;

class ProfilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function show(User $user): bool
    {
        if(Auth::check()) return true;
        else return !$user->priv;
    }
}