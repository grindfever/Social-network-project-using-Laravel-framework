@extends('layouts.app')  

@section('content')
    <div>
        <h2>Chats</h2>
        <ul>
            @foreach($messages as $message)
                @if ($message->sender == $user_id){
                    <li>{{ $message->receiver }}</li>                
                }
                @else
                    <li>{{ $message->sender }}</li>                
                @endif
            @endforeach
        </ul>
    </div>
@endsection
