@extends('layouts.app')

@section('title', 'Random')

@section('content')
    <section id="post">
        @include('partials.post', ['post' => $post])
    </section>
@endsection