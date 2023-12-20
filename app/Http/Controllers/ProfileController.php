<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Post;
use App\Models\FriendRequest;

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

    public function sendFriendRequest($id)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }
    
        $senderId = Auth::user()->id;
        $receiverId = $id;
    
        // Check if a friend request already exists
        $existingRequest = FriendRequest::where('sender', $senderId)
            ->where('receiver', $receiverId)
            ->first();
    
        if (!$existingRequest) {
            // Create a new friend request
            FriendRequest::create([
                'sender' => $senderId,
                'receiver' => $receiverId,
                'accepted' => false, // Assuming default is not accepted
                'request_date' => now(),
            ]);
    
            // Return success as JSON response with sender and receiver
            return response()->json([
                'success' => true,
                'message' => 'Friend request sent successfully',
                'sender' => $senderId,
                'receiver' => $receiverId,
            ]);
        }
    
        // Return info as JSON response
        return response()->json(['success' => false, 'message' => 'Friend request already sent']);
    }
}


