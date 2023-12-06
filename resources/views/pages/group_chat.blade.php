@extends('layouts.app')
<!-- Display form for creating a group -->
<form method="post" action="{{ route('create.group') }}">
    @csrf
    <label for="name">Group Name:</label>
    <input type="text" name="name" required><br>

    <label for="description">Group Description:</label>
    <textarea name="description"></textarea><br>

    <label for="members">Select Group Members (at least three):</label>
    <select name="members[]" multiple required>
        @foreach ($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select><br>

    <button type="submit">Create Group</button>
</form>
*/