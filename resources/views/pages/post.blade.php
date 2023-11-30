@extends('layouts.app')

@section('title', 'Random')

@section('content')
    <section id="post">
        @include('partials.post', ['post' => $post, 'count_likes' => $count_likes])
    </section>
    @auth
    <button class="edit-post" data-post-id="{{ $post->id }}" type="submit">Edit</button>
    @endauth
    
@endsection