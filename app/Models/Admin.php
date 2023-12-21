<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Admin extends Model implements Authenticatable
{
    use HasFactory, Notifiable;

    function getRememberToken(){
        return $this->remember_token;
    }

    function getRememberTokenName(){
        return $this->remember_token_name;
    }

    function setRememberToken($value){
        $this->remember_token = $value;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }    

    private function who() {
        return User::where('id','=', $this->id)->get();
    }
}

