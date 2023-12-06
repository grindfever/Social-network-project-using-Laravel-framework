@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')

    <section id="post">
        
        @guest
        <article class="post">
            <p> Please <a href="{{ url('/login') }}">login</a> to create a post </p>
        @endguest
        @auth
        <article class="post">
            <form class="new_post" method="POST" action="/dashboard">
                @csrf
                <input type="text" name="content" placeholder="What's on your mind">
                <button type="submit">Create Post</button>
            </form>
        </article>
        @endauth
        @each('partials.post', $post, 'post')
    </section>
    
@endsection
   