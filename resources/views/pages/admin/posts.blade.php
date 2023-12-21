@extends('layouts.app')

@section('content')
    <h1>Users</h1>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Group Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr class="table-active" data-post-id="{{ $post->id }}">
                <td>{{ $post->id }}</td>
                <td>{{ $post->title }}</td>
                <td>
                    @csrf
                    <button class="btn btn-danger" id="remove-post" data-post-id="{{ $post->id }}" type="button">Remove</button>
                </td>
            </tr>            
            @endforeach
        </tbody>
    </table>
@endsection
