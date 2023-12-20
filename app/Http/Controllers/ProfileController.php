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
        if(Auth::guard('admin')->check()){
            return redirect('/admin');    
        }
        if(Auth::guest()){
            return redirect('/login');
        }
        $user = Auth::user();
        return redirect('/profile/'.$user->id);
    }
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        
        $post = Post::where('user_id','=',$id)->orderBy('id')->get();
        
        if (Auth::guard('admin')->check()){
            return view('pages.profile', ['user'=> $user,'post'=>$post]);
        }

        if(Auth::guest()){
            if($user->priv == TRUE) return redirect('/dashboard');
            else return view('pages.profile', ['user'=> $user]);
        }
        
        else return view('pages.profile', ['user'=> $user,'post'=>$post]);
        
    }
}


