<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FriendRequest;

class FriendRequestController extends Controller
{
  
    public function index()
    {
        $friendRequests = FriendRequest::with('sender')->where('receiver', auth()->id())->get();
        
        return view('pages.friendrequest', ['friendRequests' => $friendRequests]);
    }
    
    public function accept($sender, $receiver)
    {
        $friendRequest = FriendRequest::where('sender', $sender)
            ->where('receiver', $receiver)
            ->firstOrFail();
    
        $friendRequest->accept();
    
        return response()->json([
            'message' => 'Friend request accepted successfully',
            'sender' => $sender,
            'receiver' => $receiver,
        ]);
    }
    
    public function reject($sender, $receiver)
    {
        $friendRequest = FriendRequest::where('sender', $sender)
            ->where('receiver', $receiver)
            ->firstOrFail();
    
        $friendRequest->reject();
    
        return response()->json([
            'message' => 'Friend request rejected successfully',
            'sender' => $sender,
            'receiver' => $receiver,
        ]);
    }
}
