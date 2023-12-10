@extends('layouts.app')

@section('title', $user->name)

@section ('content')

<section id="profile">
    <p>Profile!</p>
    <section id="user">
        @include('partials.profile', ['user' => $user])
    </section>
    
    @if (!$me)
    <form action="{{ route('send.friend.request', ['id' => $user->id]) }}" method="post">
    @csrf
    <button type="submit">Send Friend Request</button>
    </form>
    @else
    <!-- Display friend requests link or any other information -->
    <section id="friendrequests">
    <a href="{{ route('friendrequests.index') }}">Friend Requests</a>
    </section>

    @endif

    @foreach ($post as $post)
    <header>
        <h2>{{ $user->name}}</h2>
    </header> 
       {{ $post->content }}
    @endforeach
</section>


@endsection