@extends('layouts.app')

@section('title', 'Random')

@section('content')
    <section id="post">
        @include('partials.post', ['post' => $post])
    </section>
    <button class="edit-post" data-post-id="{{ $post->id }}" type="submit">Edit</button>
    
@endsection