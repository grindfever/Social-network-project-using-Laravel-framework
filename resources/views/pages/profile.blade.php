@extends('layouts.app')

@section('title', $user->name)

@section ('content')

<section id="profile">
   
    <section class="user">
        @include('partials.profile', ['user' => $user])
    </section>
    <h3>User Timeline:</h3>
    <section class="profiletimeline">
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