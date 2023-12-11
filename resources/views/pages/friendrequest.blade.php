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
               
        
            </div>
        @endforeach

</section>
@endsection
