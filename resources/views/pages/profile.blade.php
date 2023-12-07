@extends('layouts.app')

@section('title', $user->name)

@section ('content')

<section id="profile">
    <p>Profile!</p>
    <section id="user">
        @include('partials.profile', ['user' => $user])
    </section>
    @if (!$me)
    <button class="send-friendrequest"  type="submit">Send Friend Request</button>    
    @else
    <section id="friend requests">

    <a href="/friendrequest">Friend Requests</a>
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