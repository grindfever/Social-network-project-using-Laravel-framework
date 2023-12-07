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
        if(Auth::guest()){
            return redirect('/login');
        }
        $user = Auth::user();
        return redirect('/profile/'.$user->id);
    }
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        // Get posts for user ordered by id.
        $post = Post::where('user_id','=',$id)->orderBy('id')->get();

        
        if(Auth::guest()){
            if($user->priv == TRUE) return redirect('/dashboard');
            else return view('pages.profile', ['user'=> $user]);
        }
      
        else {
            $me = Auth::user()->id == $id;
            return view('pages.profile', ['user'=> $user,'post'=>$post,'me'=>$me]);}
        //policy ainda n funciona
        /* 
        if ($this->authorize('show', $user)) return view('pages.profile', ['user'=> $user]);
        else return redirect('/cards');
        */
        //usar policy 
    }
    public function showfriendrequest(){
        if (Auth::guest()){ return redirect("/dashboard") ; }
        $user = Auth::user();
        return view('pages.friendrequest');
    }
}


