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
    
    @foreach ($post as $post)
    <header>
        <h2>{{ $user->name}}</h2>
    </header> 
       {{ $post->content }}
    @endforeach
</section>


@endsection