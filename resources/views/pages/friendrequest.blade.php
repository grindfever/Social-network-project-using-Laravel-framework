@extends('layouts.app')

@section('title', 'friendrequest')

@section('content')

<section id="friendrequests">   
    <h2>Friend Requests</h2>

    @foreach ($friendRequests as $request)
    <div class="friend-request-item" data-sender="{{ $request->sender }}" data-receiver="{{ $request->receiver }}">
        @if($request->sender)
            @php
                $sender = \App\Models\User::find($request->sender);
            @endphp

            Sender: {{ $sender->name ?? '(Name not available)' }}
            <br>
            Request Date: {{ $request->request_date }}
            <br>

            <form onsubmit="event.preventDefault(); sendAcceptFriendRequest({{ $request->sender }}, {{ $request->receiver }});">
                @csrf
                <button type="submit">Accept</button>
            </form>

            <form onsubmit="event.preventDefault(); sendRejectFriendRequest({{ $request->sender }}, {{ $request->receiver }});">
                @csrf
                <button type="submit">Reject</button>
            </form>
        @endif
    </div>
@endforeach

</section>
@endsection
