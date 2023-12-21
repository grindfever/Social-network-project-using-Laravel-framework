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
                <tr class="table-active">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td>
                        <a href="{{ url('/profile/' . $user->id) }}">See profile</a>
                    </td>
                    <td>
                        <form class="ban" action="{{url('/ban/'.$user->id)}}">
                            @csrf
                            <button type="submit" class="btn btn-danger">Ban</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
