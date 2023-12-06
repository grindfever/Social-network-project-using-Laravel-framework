@extends('layouts.app')

@section('content')
    <h1>Your Groups</h1>

    @forelse ($groups as $group)
        <p>
            <a href="/groups/{{ $group->id }}">
                {{ $group->name }}
            </a>
        </p>
    @empty
        <p>No groups available.</p>
    @endforelse

    <p>
        <a href="/create-group">Create a New Group</a>
    </p>
@endsection

