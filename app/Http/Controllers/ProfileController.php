<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;


class ProfileController extends Controller {
    //Display Profile page

    public function myProfile(){
        $user = Auth::user();
        return redirect('/profile/'.$user->id);
    }
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        
        
        if(Auth::guest()){
            if($user->priv == TRUE) return redirect('/cards');
            else return view('pages.profile', ['user'=> $user]);
        }
        
        else return view('pages.profile', ['user'=> $user]);

        //policy ainda n funciona
        /* 
        if ($this->authorize('show', $user)) return view('pages.profile', ['user'=> $user]);
        else return redirect('/cards');
        */
        //usar policy 
    }
}


