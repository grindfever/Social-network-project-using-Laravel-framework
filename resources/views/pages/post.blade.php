@extends('layouts.app')

@section('title', $post->name)

@section('content')
    <section id="posts">
        @include('partials.post', ['post' => $post])
    </section>
@endsection