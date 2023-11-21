@extends('layouts.app')  

@section('content')
    <div>
        <h2>Sent Messages</h2>
        <ul>
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
@endsection
