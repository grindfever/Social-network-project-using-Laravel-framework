@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')
    @include('partials.search')
    <section id="post">
        @each('partials.post', $post, 'post')
        @guest
        <article class="post">
            <p> Please <a href="{{ url('/login') }}">login</a> to create a post </p>
        @endguest
        @auth
        <article class="post">
            <form class="new_post" method="POST" action="/dashboard">
                @csrf
                <input type="text" name="content" placeholder="new post">
                <button type="submit">Create Post</button>
            </form>
        </article>
        @endauth
    </section>

    @auth
    <form class="new_post" method="POST" action="/dashboard" enctype="multipart/form-data">
        @csrf
        <div class="input-group">
            <input type="text" class="form-control" name="title" placeholder="Title">
        </div>
        <div class="input-group">
            <textarea class="form-control" rows="4" cols="40" name="content" placeholder="What's on your mind?"></textarea>
        </div>
        <div class="input-group">
            <input type="file" class="form-control" name="media" accept="image/*, video/*">
        </div>
        <button type="submit" class="btn btn-dark">Create Post</button>
    </form>
    
    @endauth

    <section id="post" class="dashboard">
        @foreach($posts as $post)
            @include('partials.post', ['post' => $post])
        @endforeach
    </section>
@endsection


