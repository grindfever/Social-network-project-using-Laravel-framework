<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


use App\Models\Message;


class MessageController extends Controller
{

    /**
     * Shows all cards.
     */
    public function chat(string $id)
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Get sent messages for user ordered by id.
            $sentMessages = Auth::user()->sentMessages()->where('messages.receiver','=', $id)->get();
            // Get sent messages for user ordered by id.
            $receivedMessages = Auth::user()->receivedMessages()->where('messages.sender','=', $id)->get();
            // Check if the current user can list the cards.
            //$this->authorize('list', Card::class);

            // The current user is authorized to list cards.

            // Use the pages.cards template to display all cards.
            return view('pages.message', [
                'sentMessages' => $sentMessages,
                'receivedMessages' => $receivedMessages,
                'id' => $id
            ]);
        }
    }

    /**
     * Shows all chats.
     */
    public function list_chats()
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.
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
