@extends('layouts.app')

@section('content')
    <h1>Group Chat</h1>

    <div id="chat">
        @foreach ($group_messages as $message)
            <p><strong>{{ $message->sender }}:</strong> {{ $message->content }}</p>
        @endforeach
    </div>

    <form method="post" action="/groups/{{$group->id}}/chat">
        @csrf
        <input type="text" name="message" placeholder="Type your message">
        <button type="submit">Send</button>
    </form>
@endsection
