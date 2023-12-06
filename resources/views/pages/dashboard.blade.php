@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')
    <section id="post">
        @foreach($posts as $post)
            @include('partials.post', ['post' => $post])
        @endforeach
        
        @guest
            <article class="post">
                <p> Please <a href="{{ url('/login') }}">login</a> to create a post </p>
            </article>
        @endguest
        
        @auth
            <article class="post">
                <form class="new_post" method="POST" action="/dashboard">
                    @csrf
                    <input type="text" name="content" placeholder="new post">
                    <button type="submit" class="btn btn-dark">Create Post</button>
                </form>
            </article>
        @endauth
    </section>
@endsection
