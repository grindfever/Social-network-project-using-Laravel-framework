@extends('layouts.app')

@section('content')
<div id="chatpage">
    <div id="chat">
        <div class="message-container">
            <ul class="messages">
                @foreach($allMessages->sortBy('date') as $message)
                    <li class="{{ $message->sender == Auth::user()->id ? 'sent-message' : 'received-message' }}">
                        {{ $message->content }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <article class="message" data-id="{{$id}}">
        <form class="new_message" method="POST" action="/messages/{{$id}}">
            @csrf
            <input type="text" name="content" placeholder="new message">
            <button type="submit">Send</button>
        </form>
    </article>
</div>
@endsection

