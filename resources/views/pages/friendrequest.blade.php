@extends('layouts.app')

@section('title', 'friendrequest')

@section('content')

    <section id="friendrequests">   
        <h2>Friend Requests</h2>

        @forelse ($friendRequests as $request)
    <div class="friend-request-item">
        <!-- Display information about the friend request -->
        @if($request->sender)
            Sender: User ID {{ $request->sender }}
        @else
            Sender: User ID {{ $request->sender->name }} (Unknown)
        @endif
        <br>
        Request Date: {{ $request->request_date }}
    </div>
@empty
    <p>No friend requests</p>
@endforelse

    </section>
@endsection
