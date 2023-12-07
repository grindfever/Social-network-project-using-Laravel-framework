@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')

    <section id="post">
        @each('partials.post', $post, 'post')
        @guest('admin')
        @guest('web')
        <article class="post">
            <p> Please <a href="{{ url('/login') }}">login</a> to create a post </p>
        @endguest
        @endguest
        @auth ('web')
        <article class="post">
            <form class="new_post" method="POST" action="/dashboard">
                @csrf
                <input type="text" name="content" placeholder="new post">
                <button type="submit">Create Post</button>
            </form>
        </article>
        @endauth
    </section>
    
@endsection
   