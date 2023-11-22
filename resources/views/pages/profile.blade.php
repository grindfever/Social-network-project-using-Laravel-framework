@extends('layouts.app')

@section('title', $user->name)

@section ('content')

<section id="profile">
    <p>Profile!</p>
    <section id="user">
        @include('partials.profile', ['user' => $user])
    </section>
</section>


@endsection