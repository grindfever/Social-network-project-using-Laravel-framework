@extends('layouts.app')  {{-- Assuming you have a layout file --}}

@section('content')
    <h1>Moderators</h1>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($moderators as $moderator)
                <tr class="table-active">
                    <td>{{ $moderator->user->id }}</td>
                    <td>{{ $moderator->user->name }}</td>
                    <td><a href="{{ url('/profile/' . $moderator->id) }}">See profile</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
