@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')

    @auth
    <form class="new_post" method="POST" action="/dashboard">
        @csrf
        <textarea rows="4" cols="40" name="content" placeholder="What's on your mind?"></textarea>
        <button type="submit" class="btn btn-dark">Create Post</button>
    </form>

    @endauth

    <section id="post" class="dashboard">
        @foreach($posts as $post)
            @include('partials.post', ['post' => $post])
        @endforeach
    </section>
@endsection

