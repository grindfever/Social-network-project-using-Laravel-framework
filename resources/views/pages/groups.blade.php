@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>Your Groups</h1>

        @forelse ($groups as $group)
            <a href="/groups/{{ $group->id }}" class="text-dark text-decoration-none">
                <div class="card my-3 p-3" style="cursor: pointer; border: 1px solid #ccc;">
                    <h5 class="card-title">
                        {{ $group->name }}
                    </h5>
                    <div class="d-flex justify-content-between">
                        @if ($group->owner != auth()->user()->id)
                            <form method="post" action="/groups/{{ $group->id }}/leave">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Leave</button>
                            </form>
                        @endif
                        @if ($group->owner == auth()->user()->id)
                            <form method="post" action="/groups/{{ $group->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <p>No groups available.</p>
        @endforelse

        <p>
            <a href="/create-group" class="btn btn-primary">Create a New Group</a>
        </p>
    </div>
@endsection

