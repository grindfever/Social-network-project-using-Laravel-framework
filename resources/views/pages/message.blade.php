@extends('layouts.app')  

@section('content')
    <div>
        <h2>Sent Messages</h2>
        <ul class="messages">
            @foreach($sentMessages as $sentMessage)
                <li>{{ $sentMessage->content }}</li>
            @endforeach
        </ul>
    </div>

    <div>
        <h2>Received Messages</h2>
        <ul>
            @foreach($receivedMessages as $receivedMessage)
                <li>{{ $receivedMessage->content }}</li>
            @endforeach
        </ul>
    </div>
    <div>
        <article class="message" data-id="{{$id}}">
            <form class="new_message" method="POST" action="/messages/{{$id}}">
                @csrf
                <input type="text" name="content" placeholder="new message">
                <button type="submit">Send</button>
            </form>
        </article>
    </div>
@endsection
