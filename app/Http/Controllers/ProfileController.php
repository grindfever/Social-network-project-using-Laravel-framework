<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Friend;
use App\Models\User;
use App\Models\Post;
use App\Models\FriendRequest;
use App\Http\Controllers\FileController;

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
            return view('pages.profile', ['user'=> $user,'areFriends' => false, 'post'=>$post]);
        }

        if (Auth::guest()) {
            if ($user->priv == TRUE) {
                return redirect('/dashboard');
            } else {
                return view('pages.profile', ['user' => $user, 'areFriends' => false, 'post' => $post]);
            }
        } 
        else {
            $me = Auth::user()->id == $id;
            $areFriends = $this->areFriends(Auth::user()->id, $id);
            $hasFriendRequest = $this->hasFriendRequest(Auth::user()->id, $id);
            return view('pages.profile', ['user' => $user, 'areFriends' => $areFriends,'hasFriendRequest' => $hasFriendRequest, 'post' => $post, 'me' => $me]);
        }        

    
    }
    protected function hasFriendRequest($senderId, $receiverId)
    {   
        return FriendRequest::where('sender', $senderId)
        ->where('receiver', $receiverId)
        ->exists();
    }

    protected function areFriends($user1Id, $user2Id)
    {
        return Friend::where(function ($query) use ($user1Id, $user2Id) {
            $query->where('user_id1', $user1Id)->where('user_id2', $user2Id);
        })->orWhere(function ($query) use ($user1Id, $user2Id) {
            $query->where('user_id1', $user2Id)->where('user_id2', $user1Id);
        })->exists();
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
    
        $existingRequest = FriendRequest::where('sender', $senderId)
            ->where('receiver', $receiverId)
            ->first();
    
        if (!$existingRequest) {
            FriendRequest::create([
                'sender' => $senderId,
                'receiver' => $receiverId,
                'accepted' => false,
                'request_date' => now(),
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Friend request sent successfully',
                'sender' => $senderId,
                'receiver' => $receiverId,
            ]);
        }
    
        return response()->json(['success' => false, 'message' => 'Friend request already sent']);
    }
    public function editProfile() {
        if (Auth::guest()) {
            return redirect("/dashboard");
        }
    
        $user = Auth::user();
        return view('pages.editprofile', ['user' => $user]);
    }
    public function updateProfile(Request $request, $id)
    {
        
        // Validate the form data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'age' => 'required|integer|min:18',
            'bio' => 'nullable|string|max:255',
        
        ]);
 
        // Find the user
        $user = User::findOrFail($id);
        
        $fileController = new FileController();
        $fileController->upload($request);
    


        // Update the user's profile
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),    
            'age' => $request->input('age'),
            'bio' => $request->input('bio'),
            'priv' => $request->input('privacy'),
        ]);



    
        // Redirect the user to their profile page or a success page
        return redirect()->route('profile.show', ['id' => $id])
            ->with('success', 'Profile updated successfully');
    }
    
    public function delete($id)
{
    // Find the user
    $user = User::findOrFail($id);

    $user->forceDelete();

    // Log out the user
    Auth::logout();

    // Redirect to the login page with a success message
    return redirect()->route('login')->with('success', 'Account deleted successfully. We hope to see you again!');
}
}

