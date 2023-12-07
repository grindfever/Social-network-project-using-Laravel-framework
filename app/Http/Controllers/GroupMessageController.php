<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\GroupMessage;

class GroupMessageController extends Controller
{
    public function showChat($groupId)
    {
        // Retrieve group chat messages
        $group_messages = GroupMessage::where('group_id', $groupId)->get();
        $group = Group::find($groupId);

        return view('pages.group_chat', compact('group_messages', 'group'));
    }

    public function sendMessage(Request $request, string $groupId)   
    {
        $group_message = new GroupMessage();

        //$this->authorize('create', $card);

        $group_message->content = $request->input('message');
        $group_message->sender = Auth::user()->id;
        $group_message->group_id = $groupId;  

        $group_message->save();
        return redirect("/groups/{$groupId}/chat");
    }
}
