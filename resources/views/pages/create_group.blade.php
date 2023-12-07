<!-- resources/views/create_group.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Create a Group</h1>

    <form method="post" action="/create-group">
    @csrf
    <label for="name">Group Name:</label>
    <input type="text" name="name" required><br>

    <label for="description">Group Description:</label>
    <textarea name="description"></textarea><br>

    <label for="members">Select Group Members (at least two):</label><br>
    @foreach ($users as $user)
        <label>
            <input type="checkbox" name="members[]" value="{{ $user->id }}">
            {{ $user->name }}
        </label><br>
    @endforeach

    <button type="submit">Create Group</button>
</form>


@endsection
