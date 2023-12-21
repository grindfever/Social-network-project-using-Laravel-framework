@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Edit Group: {{ $group->name }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="post" action="/groups/{{ $group->id }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">Group Name:</label>
                <input type="text" name="name" value="{{ old('name', $group->name) }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Group Description:</label>
                <textarea name="description" class="form-control">{{ old('description', $group->description) }}</textarea>
            </div>

            @if (auth()->user()->id === $group->owner)
                <button type="submit" class="btn btn-primary">Update Group</button>
            @endif
        </form>

        <h2 class="mt-4">Add Members</h2>

        <form method="post" action="/groups/{{ $group->id }}/add-members">
            @csrf

            <div class="mb-3">
                @if(isset($noUsersLeftMessage))
                    <p class="alert alert-info">{{ $noUsersLeftMessage }}</p>
                @else
                    <label class="form-label">Select members to Add:</label>
                    @foreach($users as $user)
                        <div class="form-check">
                            <input type="checkbox" name="members[]" value="{{ $user->id }}" class="form-check-input">
                            <label class="form-check-label">{{ $user->name }}</label>
                        </div>
                    @endforeach
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Add Members</button>
        </form>
    </div>
@endsection

