@extends('layouts.app')  

@section('content')
    <div>
        <h2>Chats</h2>
        <ul>
            @foreach ($users as $user)
                <li><a href="/messages/{{$user->id}}">{{$user->name}}</a></li>
            @endforeach
        </ul>
    </div>
@endsection
