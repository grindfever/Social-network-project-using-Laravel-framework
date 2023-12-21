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
        $group = Group::find($groupId);
        $sent_messages = GroupMessage::where('sender', Auth::user()->id)->where('group_id', $groupId)->get();
        $received_messages = GroupMessage::where('group_id', $groupId)->where('sender', '<>', Auth::user()->id)->get();
        return view('pages.group_chat', compact('group', 'sent_messages', 'received_messages'));
    }

    public function sendMessage(Request $request, string $groupId)
    {
        DB::table('group_messages')->insert([
            'group_id' => $groupId,
            'sender' => Auth::user()->id,
            'content' => $request->input('content'),
        ]);

        $user = Auth::user()->name;

        return response()->json([
            'content' => $request->input('content'),
            'user' => $user,
        ]);
    }
}