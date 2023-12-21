<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


use App\Models\Message;


class MessageController extends Controller
{

    public function chat(string $id)
    {
        if (!Auth::check()) {
            return redirect('/login');

        } else {

            $sentMessages = Message::where('sender', Auth::user()->id)->where('receiver', $id)->get();
            $receivedMessages = Message::where('sender', $id)->where('receiver', Auth::user()->id)->get();

            $allMessages = $sentMessages->merge($receivedMessages)->sortBy('date');


            return view('pages.message', [
                'allMessages' => $allMessages,
                'id' => $id,
            ]);
        }
    }

    
    public function list_chats()
    {
        if (!Auth::check()) {
            return redirect('/login');

        } else {
            $currentUserId = Auth::user()->id;

            $users = Auth::user()->join('messages', function ($join) use ($currentUserId) {
                $join->on('users.id', '=', 'messages.sender')
                    ->where('messages.receiver', '=', $currentUserId)
                    ->orWhere(function ($query) use ($currentUserId) {
                        $query->on('users.id', '=', 'messages.receiver')
                            ->where('messages.sender', '=', $currentUserId);
                    });
            })
            ->distinct()
            ->where('users.id', '!=', $currentUserId)
            ->select('users.id', 'users.name')
            ->get();

            return view('pages.messages', [
                'users' => $users                
            ]);
        }
    }


    /**
     * Creates a new message.
     */
    public function create(Request $request, string $id)   
    {
        $message = new Message();

        //$this->authorize('create', $card);

        $message->content = $request->input('content');
        $message->sender = Auth::user()->id;
        $message->receiver = $id;  

        $message->save();
        return response()->json($message);
    }

}
