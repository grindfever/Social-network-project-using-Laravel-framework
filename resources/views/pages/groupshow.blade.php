@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1>{{ $group->name }}</h1>
        <p>Description: {{ $group->description }}</p>

        @if (auth()->user()->id === $group->owner)
            <p><a href="/groups/{{ $group->id }}/edit" class="btn btn-primary">Edit Group</a></p>
        @endif

        <h2>Members:</h2>
        <ul class="list-group">
            @php
                $memberships = DB::table('memberships')->where('group_id', $group->id)->get();
            @endphp

            @forelse ($memberships as $membership)
                @php
                    $possibleMember = DB::table('users')->where('id', $membership->possible_member)->first();
                @endphp

                @if ($possibleMember)
                    <li class="list-group-item">
                        @if ($membership->possible_member === $group->owner)
                            <a href="/profile/{{$possibleMember->id}}" class="fw-bold no-decoration">{{ $possibleMember->name }} (Owner)</a>
                        @else
                            <a href="/profile/{{$possibleMember->id}}" class="no-decoration">{{ $possibleMember->name }}</a>
                            @if (auth()->user()->id === $group->owner)
                                <form method="post" action="/groups/{{$group->id}}/kick-member" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="member_id" value="{{ $possibleMember->id }}">
                                    <button type="submit" class="btn btn-danger btn-sm">Kick</button>
                                </form>
                            @endif
                        @endif
                    </li>
                @endif
            @empty
                <li class="list-group-item">No members in this group.</li>
            @endforelse
        </ul>

        <a href="/groups/{{ $group->id }}/chat" class="btn btn-primary mt-3">Enter Group Chat</a>
    </div>


        

@endsection

