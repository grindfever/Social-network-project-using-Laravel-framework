@extends('layouts.app')  

@section('content')
    <div id = "chatmenu">
        <h1>Chats :</h1>
        <ul>
            @foreach ($users as $user)
                <li><a href="/messages/{{$user->id}}">{{$user->name}}</a></li>
            @endforeach
        </ul>
    </div>
@endsection
