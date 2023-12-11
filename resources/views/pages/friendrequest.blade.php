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
                @else
                    Sender: User ID {{ $request->sender }} (Unknown)
                @endif
                <br>
                Request Date: {{ $request->request_date }}
               
                <form action="{{ route('friendrequests.accept', ['id' => $request->id]) }}" method="post">
                @csrf
                <button type="submit">Accept</button>
                </form>

                <form action="{{ route('friendrequests.reject', ['id' => $request]) }}" method="post">
                @csrf
                <button type="submit">Reject</button>
                </form>
            </div>
        @endforeach

</section>
@endsection
