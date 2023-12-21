@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Create a Group</h1>

        <form method="post" action="/create-group">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Group Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Group Description:</label>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Select Group Members (at least two):</label><br>
                @foreach ($users as $user)
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="members[]" value="{{ $user->id }}" class="form-check-input">
                        <label class="form-check-label">{{ $user->name }}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Create Group</button>
        </form>
    </div>
@endsection

