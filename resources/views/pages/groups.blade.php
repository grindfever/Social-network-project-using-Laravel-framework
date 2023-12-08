@extends('layouts.app')

@section('content')
    <h1>Your Groups</h1>

    @forelse ($groups as $group)
        <div>
            <a href="/groups/{{ $group->id }}">
                {{ $group->name }}
            </a>

            @if ($group->owner == auth()->user()->id)
                <form method="post" action="/groups/{{ $group->id }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            @endif
        </div>
    @empty
        <p>No groups available.</p>
    @endforelse

    <p>
        <a href="/create-group">Create a New Group</a>
    </p>
@endsection


