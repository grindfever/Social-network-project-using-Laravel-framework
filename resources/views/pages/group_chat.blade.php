@extends('layouts.app')  

@section('content')
<div id="chatpage">
<div id="chat">
    <div class="message-container sent-messages">
            <ul class="messages">
            @foreach($sent_messages as $sentMessage)
                <li>{{ $sentMessage->content }}</li>
            @endforeach
        </ul>
    </div>
    <div class="message-container received-messages">
        <ul class="messages">
            @foreach($received_messages as $receivedMessage)
                <li>{{ $receivedMessage->content }}</li>
            @endforeach
        </ul>
    </div>
</div>

<article class="message" data-id="{{$group->id}}">
        <form class="new_group_message" method="POST" action="/groups/{{ $group->id }}/chat">
            @csrf
            <input type="text" name="content" placeholder="new message">
            <button type="submit">Send</button>
        </form>
</article>
</div>

@endsection