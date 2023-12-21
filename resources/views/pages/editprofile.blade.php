@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <section class="editprofile">
    <h2>Edit Profile</h2>
    <form action="{{ route('profile.update', ['id' => $user->id]) }}" method="post">
    @method('PUT')
    @csrf
    <div id="name">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="{{ $user->name }}" required>
    </div>
    <div id="email">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="{{ $user->email }}" required>
    </div>  
    <div id="age">
    <label for="age">Age:</label>
    <input type="number" id="age" name="age" value="{{ $user->age }}" required>
    </div>
    <div id="bio">
    <label for="bio">Bio:</label>
    <textarea id="bio" name="bio">{{ $user->bio }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Profile</button>
</form>
</section>
@endsection