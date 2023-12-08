<!-- resources/views/groups/edit.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Edit Group: {{ $group->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="post" action="/groups/{{ $group->id }}">
        @csrf
        @method('patch')

        <div>
            <label for="name">Group Name:</label>
            <input type="text" name="name" value="{{ old('name', $group->name) }}" required>
        </div>

        <div>
            <label for="description">Group Description:</label>
            <textarea name="description">{{ old('description', $group->description) }}</textarea>
        </div>

        @if (auth()->user()->id === $group->owner)
    <button type="submit">Update Group</button>
        @endif
    </form>

    <h2>Add Members</h2>

    <form method="post" action="/groups/{{ $group->id }}/add-members">
        @csrf

        <div>
            @if(isset($noUsersLeftMessage))
            <p>{{ $noUsersLeftMessage }}</p>
            @else
            <label>Select Members to Add:</label>
            @foreach($users as $user)
                <div>
                    <input type="checkbox" name="members[]" value="{{ $user->id }}">
                    {{ $user->name }}
                </div>
            @endforeach
            @endif
        </div>

        <button type="submit">Add Members</button>
    </form>
@endsection
