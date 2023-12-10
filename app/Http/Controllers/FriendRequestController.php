<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FriendRequest;

class FriendRequestController extends Controller
{
  
    public function index()
    {
        // Retrieve and display friend requests
        $friendRequestsQuery = FriendRequest::with('sender')->where('receiver', auth()->id());
    
        $friendRequests = $friendRequestsQuery->get();
    
        return view('pages.friendrequest', ['friendRequests' => $friendRequests]);
    }
    public function accept($id)
    {
        $friendRequest = FriendRequest::find($id);
        $friendRequest->accept();

        return redirect()->route('friendrequests.index');
    }

    public function reject($id)
    {
        $friendRequest = FriendRequest::find($id);
        $friendRequest->reject();

        return redirect()->route('friendrequests.index');
    }
}
