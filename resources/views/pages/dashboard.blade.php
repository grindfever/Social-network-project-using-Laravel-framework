@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')
    
    @guest('admin')
    @guest()
    <article class="post">
        <p> Please <a href="{{ url('/login') }}">login</a> to create a post </p>
    @endguest
    @endguest


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
            <input type="file" class="form-control" name="file">
            <input  name="type" type="text" value="post" hidden>
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


