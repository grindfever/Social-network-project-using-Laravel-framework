@extends('layouts.app')

@section('content')
    <h1>Group Chat</h1>

    <div id="chatpage">
        <div id="chat" class="message-container">
            <ul class="messages">
                @foreach ($group_messages as $message)
                    @php
                        // Fetch the sender's name from the users table using the sender ID
                        $sender = \App\Models\User::find($message->sender);
                        $isCurrentUser = $message->sender == auth()->user()->id;
                    @endphp

                    <li class="{{ $isCurrentUser ? 'sent-messages' : 'received-messages' }}">
                        <strong>{{ $isCurrentUser ? 'You' : $sender->name }}:</strong> {{ $message->content }}
                    </li>
                @endforeach
            </ul>
        </div>

        <article class="message" data-id="{{ $group->id }}">
            <form class="new_message" method="POST" action="/groups/{{ $group->id }}/chat">
                @csrf
                <input type="text" name="content" placeholder="Type your message">
                <button type="submit">Send</button>
            </form>
        </article>
    </div>
@endsection