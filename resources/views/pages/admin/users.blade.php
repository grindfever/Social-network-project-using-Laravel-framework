@extends('layouts.app')

@section('content')
    <h1>Users</h1>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Name</th>
                <th scope="col">Username</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="table-active" data-id="{{ $user->id }}">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        <a href="{{ url('/profile/' . $user->id) }}">See profile</a>
                    </td>
                    <td>
                        @if($user->isBanned())
                            @csrf
                            <button type="submit" id="unban" class="btn btn-dark" data-id="{{ $user->id }}">Unban</button>
                        @else
                            @csrf
                            <button type="submit" id="ban" class="btn btn-danger" data-id="{{ $user->id }}">Ban</button>
                        @endif
                    </td>
                    @auth('admin')
                    <td>
                        <section id="set_moderator">
                            <article class="set_moderator" data-id="{{$user->id}}">
                                @if($user->isModerator())
                                    <button class="remove_moderator" data-id="{{$user->id}}" type='submit'>
                                        Remove Moderator Role
                                    </button>
                                @else
                                    <button class="add_moderator" data-id="{{$user->id}}" type='submit'>
                                        Assign Moderator Role
                                    </button>
                                @endif
                            </article>
                        </section>
                    </td>
                    @endauth
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
