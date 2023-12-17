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

        $friends = auth()->user()->friends($userid);
        return view('pages.friendlist', ['friends'=>$friends,'userid'=>$userid]);
    }

    public function removeFriend($request, $id)
    {
        $userId = auth()->user()->id; 
    
        $friend = Friend::where('userid1', $userId)->where('userid2', $id)
                        ->orWhere('userid2', $userId)->where('userid1', $id)
                        ->first();

         $friend->delete();
        return response()->json($friend);
    
    }
    public function test($id){
        $userId = auth()->user()->id; 
    
        $friend = Friend::where('userid1', $userId)->where('userid2', $id)
                        ->orWhere('userid2', $userId)->where('userid1', $id)
                        ->first();

        dd($friend) ;               
    }
    
}
