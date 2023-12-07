
@extends('layouts.app')

@section('content')
    <article class="search-article">
        <form class="search-form" action="/search" method="POST">
            @csrf
            <input type="text" name="query" placeholder="search">
            <button type="submit">Search</button>
        </form>

        <ul class="results"></ul>
    </article>
    

@endsection


