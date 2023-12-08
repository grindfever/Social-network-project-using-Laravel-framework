@extends('layouts.app')

@section('content')
    <h1>{{ $group->name }}</h1>
    <p>Description: {{ $group->description }}</p>

    @if (auth()->user()->id === $group->owner)
        <p><a href="/groups/{{ $group->id }}/edit" class=button>Edit Group</a></p>
    @endif

    <h2>Members:</h2>
    <ul>
        @php
            $memberships = DB::table('memberships')->where('group_id', $group->id)->get();
        @endphp

        @forelse ($memberships as $membership)
            @php
                $possibleMember = DB::table('users')->where('id', $membership->possible_member)->first();
            @endphp

            @if ($possibleMember)
                <li>
                    @if ($membership->possible_member === $group->owner)
                        <a href="/profile/{{$possibleMember->id}}">{{ $possibleMember->name }} (Owner)</a>
                    @else
                        <a href="/profile/{{$possibleMember->id}}">{{ $possibleMember->name }}</a>
                        @if (auth()->user()->id === $group->owner)
                            <form method="post" action="/groups/{{$group->id}}/kick-member">
                                @csrf
                                <input type="hidden" name="member_id" value="{{ $possibleMember->id }}">
                                <button type="submit">Kick</button>
                            </form>
                        @endif
                    @endif
                </li>
            @endif
        @empty
            <li>No members in this group.</li>
        @endforelse
    </ul>

    <a href="/groups/{{ $group->id }}/chat" class="button">Enter Group Chat</a>
@endsection
