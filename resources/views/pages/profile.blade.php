@extends('layouts.app')

@section('title', $user->name)

@section ('content')

<section id="profile">
   
    <section class="user">
        @include('partials.profile', ['user' => $user])
    </section>
    <h3>User Timeline:</h3>
    <section class="profiletimeline">
    
    @if (!$me)
    <form action="{{ route('send.friend.request', ['id' => $user->id]) }}" method="post">
    @csrf
    <button type="submit">Send Friend Request</button>
    </form>
    @else
    <!-- Display friend requests link or any other information -->
    <section id="friends">
    <a href="{{ route('friends.show') }}">Friend List  </a>
    <a href="{{ route('friendrequests.index') }}">Friend Requests</a>
    </section>

    @endif

    @foreach ($post as $post)
    <header>
        <h2>{{ $user->name}}</h2>
    </header> 
    <div class="content">
       {{ $post->content }}
    
    </div>
    @endforeach
    </section>
</section>


@endsection