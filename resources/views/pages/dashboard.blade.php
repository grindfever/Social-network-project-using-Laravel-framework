@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')

    <section id="post">
        @each('partials.post', $post, 'post')
        @if ( $guest == 0)
        {<article class="post">
            <form class="new_post" method="POST" action="/dashboard">
                @csrf
                <input type="text" name="content" placeholder="new post">
                <button type="submit">Create Post</button>
            </form>
        </article>} @endif
    </section>
    
@endsection
   