@extends('layouts.app')

@section('title', $user->name)

@section ('content')

<section id="profile">
    <p>Profile!</p>
    <section id="user">
        @include('partials.profile', ['user' => $user])
    </section>
    
    @if (!$me)
    <div class="friend-request-item" data-sender="{{ auth()->id() }}" data-receiver="{{ $user->id }}">
        <form id="friendRequestForm" data-sender="{{ auth()->id() }}" data-receiver="{{ $user->id }}">
            @csrf
            <button type="submit">Send Friend Request</button>
        </form>
    </div>
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
       {{ $post->content }}
    @endforeach
</section>


@endsection