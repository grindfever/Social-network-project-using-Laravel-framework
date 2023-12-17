@extends('layouts.app')

@section('title', 'Random')

@section('content')
    <section id="post_page">
        @include('partials.post', ['post' => $post])
        {{-- comments --}}
        @include('partials.comments', ['post' => $post])
    </section>
    
    @if(Auth::check() && Auth::user()->id === $post->user_id)
    <div class="button-container">
        <button class="btn btn-dark" id="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
        <button id="edit-post" class="btn btn-dark" data-post-id="{{ $post->id }}" type="submit">Edit</button>
    </div>
    @endif
    
    
@endsection