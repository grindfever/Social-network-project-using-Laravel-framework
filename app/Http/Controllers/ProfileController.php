<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Post;


class ProfileController extends Controller {
    //Display Profile page

    public function myProfile(){
        $user = Auth::user();
        return redirect('/profile/'.$user->id);
    }
    public function show(string $id)
    {
        $user = User::findOrFail($id);
       // Get posts for user ordered by id.
       $post = Auth::user()->posts()->orderBy('id')->get();

        
        if(Auth::guest()){
            if($user->priv == TRUE) return redirect('/cards');
            else return view('pages.profile', ['user'=> $user]);
        }
        
        else return view('pages.profile', ['user'=> $user,'post'=>$post]);
        //policy ainda n funciona
        /* 
        if ($this->authorize('show', $user)) return view('pages.profile', ['user'=> $user]);
        else return redirect('/cards');
        */
        //usar policy 
    }
}


