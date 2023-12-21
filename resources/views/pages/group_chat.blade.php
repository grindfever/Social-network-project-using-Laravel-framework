@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div id="chatpage">
        <div id="chat">
            <div class="message-container sent-messages">
                <ul class="list-group messages">
                    @foreach($sent_messages as $sentMessage)
                        @php
                            $sender = \App\Models\User::find($sentMessage->sender);
                        @endphp
                        <li class="list-group-item">
                            <span class="message-info">{{ optional($sender)->name }}:</span>
                            {{ $sentMessage->content }}
                            <span class="float-end small-date">{{ \Carbon\Carbon::parse($sentMessage->date)->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="message-container received-messages">
                <ul class="list-group messages">
                    @foreach($received_messages as $receivedMessage)
                        @php
                            $sender = \App\Models\User::find($receivedMessage->sender);
                        @endphp
                        <li class="list-group-item">
                            <span class="message-info">{{ optional($sender)->name }}:</span>
                            {{ $receivedMessage->content }}
                            <span class="float-end small-date">{{ \Carbon\Carbon::parse($receivedMessage->date)->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <article class="message" data-id="{{$group->id}}">
            <form class="new_group_message" method="POST" action="/groups/{{ $group->id }}/chat">
                @csrf
                <div class="mb-3">
                    <input type="text" name="content" class="form-control" placeholder="New message" required>
                </div>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </article>
    </div>
</div>
@endsection

