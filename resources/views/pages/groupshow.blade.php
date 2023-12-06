@extends('layouts.app')

@section('content')
    <h1>{{ $group->name }}</h1>
    <p>Description: {{ $group->description }}</p>

    <h2>Members:</h2>
    <ul>
        @php
            $memberships = DB::table('memberships')->where('group_id', $group->id)->get();
        @endphp

        @forelse ($memberships as $membership)
            @php
                $member = DB::table('users')->where('id', $membership->member)->first();
            @endphp

            <li>{{ $member->name }}</li>
        @empty
            <li>No members in this group.</li>
        @endforelse
    </ul>

    <a href="/groups/{{ $group->id }}/chat" class="button">Enter Group Chat</a>
@endsection
