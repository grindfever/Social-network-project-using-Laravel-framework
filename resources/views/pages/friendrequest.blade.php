@extends('layouts.app')

@section('title', 'friendrequest')

@section('content')

<section id="friendrequests">   
    <h2>Friend Requests</h2>

    @foreach ($friendRequests as $request)
    <div class="friend-request-item">
        @if($request->sender)
            @php
                $sender = \App\Models\User::find($request->sender);
            @endphp

            Sender: {{ $sender->name ?? '(Name not available)' }}
            <br>
            Request Date: {{ $request->request_date }}
            <br>

           
            <form action="{{ route('friendrequests.accept', ['sender' => $request->sender, 'receiver' => $request->receiver]) }}" method="post">
                @csrf
                <button type="submit">Accept</button>
            </form>
            <form action="{{ route('friendrequests.reject', ['sender' => $request->sender, 'receiver' => $request->receiver]) }}" method="post">
                @csrf
                <button type="submit">Reject</button>
            </form>
        @endif
    </div>
@endforeach

</section>
@endsection
