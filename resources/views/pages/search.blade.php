
@extends('layouts.app')

@section('content')
    <div class="search-class">
        <form action="/search" method="GET">
            <input type="text" name="query" placeholder="Search">
            <button type="submit">Search</button>
        </form>
    </div>
    @if(isset($results))
        <h2>Search Results</h2>
        <ul>
            @if ($empty)
                @foreach($results as $result)
                    <li>{{ $result->title }}</li>
                @endforeach    
        
            @else
                <p>No results found.</p>            
            @endif
            
        </ul>
    @endif
@endsection
