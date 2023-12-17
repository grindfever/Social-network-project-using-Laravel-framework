<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function show()
    {
        $userid=Auth::user()->id;

        $friends = Auth::user()->friends();
    
        return view('pages.friendlist', ['friends'=>$friends,'userid'=>$userid]);
    }

    public function removeFriend(Request $request, $id)
    {
        // Get the authenticated user
        $authUser = Auth::user();

        // Remove the friend relationship in both directions
        Friend::where('user_id1', $authUser->id)->where('user_id2', $id)->delete();
        Friend::where('user_id1', $id)->where('user_id2', $authUser->id)->delete();

        return response()->json([
            'message' => 'Friend removed successfully',
            'friend_id' => $id
        ]);
    }
    public function delete(Request $request, $id)
    {
        // Find the post.
        $post = Post::find($id);
        
        // Check if the current user is authorized to delete this post.
        $this->authorize('delete',$post);

        // Delete the post and return it as JSON.
        $post->delete();
        return response()->json($post);
    }

    public function test($id){
        $userId = auth()->user()->id; 
    
        $friend = Friend::where('userid1', $userId)->where('userid2', $id)
                        ->orWhere('userid2', $userId)->where('userid1', $id)
                        ->first();

        dd($friend) ;               
    }
    
}
