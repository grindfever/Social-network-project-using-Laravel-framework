@extends('layouts.app')

@section('content')
    <h1>Groups</h1>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Group Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groups as $group)
                <tr class="table-active">
                    <td>{{ $group->id }}</td>
                    <td>{{ $group->name }}</td>
                    <td>
                        <form class="remove-group" method="post" action="/groups/{{ $group->id }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
