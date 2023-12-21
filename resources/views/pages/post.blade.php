@extends('layouts.app')

@section('title', 'Random')

@section('content')
  <section id="post_page">
    @include('partials.post', ['post' => $post])
    @if ((Auth::check() && Auth::user()->id === $post->user_id) || Auth::guard('admin')->check() || Auth::user()->isModerator())
      <div class="button-container">
        <button class="btn btn-dark" id="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
        <button id="edit-post" class="btn btn-dark" data-post-id="{{ $post->id }}" type="submit">Edit</button>
        <button id="save-post" class="btn btn-dark" style="display: none;">Save</button>
      </div>
    @endif

    @include('partials.comments', ['post' => $post])
  </section>
@endsection
                
        
