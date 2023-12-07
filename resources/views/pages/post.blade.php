@extends('layouts.app')

@section('title', 'Random')

@section('content')
    <div class="postpage">
    <section id="post">
        @include('partials.post', ['post' => $post])
    </section>
    @auth
    <button class="edit-post" data-post-id="{{ $post->id }}" type="submit">Edit</button>
    @endauth
    </div>
@endsection