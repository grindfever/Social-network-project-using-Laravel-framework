@extends('layouts.app')

@section('title', 'DashBoard')

@section('content')

<section id="friendlist">
    <h1>Friend List</h1>

    @if(count($friends) > 0)
    @foreach($friends as $friend)
        @include('partials._friend', ['friend' => $friend])
    @endforeach
    @else
    <p>No friends yet.</p>
    @endif
</section>
    
@endsection
