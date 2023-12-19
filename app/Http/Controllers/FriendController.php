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

        $authUser = Auth::user();

        Friend::where('user_id1', $authUser->id)->where('user_id2', $id)->delete();
        Friend::where('user_id1', $id)->where('user_id2', $authUser->id)->delete();

        return response()->json([
            'message' => 'Friend removed successfully',
            'friend_id' => $id
        ]);
    }
    public function delete(Request $request, $id)
    {
    
        $post = Post::find($id);
        
        
        $this->authorize('delete',$post);

        
        $post->delete();
        return response()->json($post);
    }


    
}
