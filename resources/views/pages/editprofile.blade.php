@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <section class="editprofile">
        <h1>Edit Profile</h1>
        <form action="{{ route('profile.update', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
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
            <div id="image">
                <label for="image">Profile picture:</label>
                <input type="file" id="image" name="file">
                <input name="id" type="number" value="{{ $user->id }}" hidden>
                <input name="type" type="text" value="profile" hidden>            
            </div>
            <div id="privacy">
                <label for="privacy">Privacy:</label>
                <select id="privacy" name="privacy">
                    <option value="false">Public</option>
                    <option value="true">Private</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </section>
@endsection
