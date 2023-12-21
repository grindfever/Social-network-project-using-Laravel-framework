@extends('layouts.app')

@section('title', $user->name)

@section ('content')


@auth('admin')
    <section id="set_moderator">
        <article class="set_moderator" data-id="{{$user->id}}">
            @if($user->isModerator())
                <button class="remove_moderator" data-id="{{$user->id}}" type='submit'>
                    Remove Moderator Role
                </button>
            @else
                <button class="add_moderator" data-id="{{$user->id}}" type='submit'>
                    Assign Moderator Role
                </button>
            @endif
        </article>
    </section>
@endauth

<section id="profile">
    <p>Profile!</p>
    <section id="user">
        @include('partials.profile', ['user' => $user])
    </section>
    
    @if (!$areFriends)
    @auth('web')
    <div class="friend-request-item" data-sender="{{ auth()->id() }}" data-receiver="{{ $user->id }}">
        <form id="friendRequestForm" data-sender="{{ auth()->id() }}" data-receiver="{{ $user->id }}">
            @csrf
            <button type="submit">Send Friend Request</button>
        </form>
    </div>
    @endauth
    @else
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