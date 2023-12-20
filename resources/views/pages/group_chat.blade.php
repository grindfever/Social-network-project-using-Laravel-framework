@extends('layouts.app')

@section('content')
    <h1>Group Chat</h1>

    <div id="chat">
        @foreach ($group_messages as $message)
            @php
                // Fetch the sender's name from the users table using the sender ID
                $sender = \App\Models\User::find($message->sender);
            @endphp
            <p><strong>{{ $sender->name }}:</strong> {{ $message->content }}</p>
        @endforeach
    </div>

    <form method="post" action="/groups/{{$group->id}}/chat">
        @csrf
        <input type="text" name="message" placeholder="Type your message">
        <button type="submit">Send</button>
    </form>
@endsection
