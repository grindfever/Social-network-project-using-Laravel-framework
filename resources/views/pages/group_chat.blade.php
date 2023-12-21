@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div id="chatpage">
        <div id="chat">
            <div class="message-container all-messages">
                <ul class="messages">
                    @php
                        $all_messages = $sent_messages->merge($received_messages)->sortBy('id');
                    @endphp

                    @foreach($all_messages as $message)
                        @php
                            $sender = \App\Models\User::find($message->sender);
                        @endphp
                        <li class="{{ $message->sender == Auth::user()->id ? 'sent-message' : 'received-message' }}">
                            <span class="message-info">{{ optional($sender)->name }}:</span>
                            {{ $message->content }}
                            <span class="float-end small-date">{{ \Carbon\Carbon::parse($message->date)->diffForHumans() }}</span>
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
