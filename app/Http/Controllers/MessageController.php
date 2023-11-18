<?php

namespace App\Http\Controllers;


use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class MessageController extends Controller
{

    /**
     * Shows all cards.
     */
    public function list()
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Get sent messages for user ordered by id.
            $sentMessages = Auth::user()->sentMessages()->orderBy('id')->get();
            // Get sent messages for user ordered by id.
            $receivedMessages = Auth::user()->receivedMessages()->orderBy('id')->get();
            // Check if the current user can list the cards.
            //$this->authorize('list', Card::class);

            // The current user is authorized to list cards.

            // Use the pages.cards template to display all cards.
            return view('pages.message', [
                'sentMessages' => $sentMessages,
                'receivedMessages' => $receivedMessages
            ]);
        }
    }

    /**
     * Shows all cards.
     */
    public function list_chats()
    {
        // Check if the user is logged in.
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
            // The user is logged in.

            // Get sent messages for user ordered by id.
            $messages = Auth::user()->messages()->orderBy('id')->get();
            $user_id = Auth::user()->id;
            // Check if the current user can list the cards.
            //$this->authorize('list', Card::class);

            // The current user is authorized to list cards.

            // Use the pages.cards template to display all cards.
            return view('pages.messages', [
                'messages' => $messages,
                'user_id' => $user_id
            ]);
        }
    }
}
