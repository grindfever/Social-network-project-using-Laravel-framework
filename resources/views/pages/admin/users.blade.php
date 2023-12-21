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
                        @auth('web')
                        @if($user->isBanned())
                            @csrf
                            <button type="submit" id="unban" class="btn btn-dark" data-id="{{ $user->id }}">Unban</button>
                        @else
                            @csrf
                            <button type="submit" id="ban" class="btn btn-danger" data-id="{{ $user->id }}">Ban</button>
                        @endif
                        @endauth
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
