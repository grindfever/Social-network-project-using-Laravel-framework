@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<section id="overall-info">
    <h1>ABOUT "Y"</h1>
    <p>Y is a social media platform that allows users to share their thoughts, photos, videos, and more with their friends and the world. It is a place to express yourself and discover what is going on around you.</p>
    <p>With Y we want you to able to do all you need to never feel alone and always be able to comunicate and discuss topics with your friends or make new friendships!</p>
    <p>This is a website developed by a group of 3rd Year Computer Engineering students from FEUP for the Curricular Unit "LBAW" (Databases and Web Applications Lab). </p>
</section>

<section id="team-members" class="row">
    <div class="col-md-6">
        <div class="card bg-secondary mb-3" style="max-width: 20rem;">
            <div class="card-body">
                <img src="{{ asset('aboutus/202006721.jpg') }}" alt="Team Member 1">
                <h2>Afonso Dias</h2>
                <p>up202006721@edu.fe.up.pt</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-secondary mb-3" style="max-width: 20rem;">
            <div class="card-body">
                <img src="{{ asset('aboutus/202005304.jpg') }}" alt="Team Member 1">
                <h2>Diogo Leandro</h2>
                <p>up202005304@edu.fe.up.pt</p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card bg-secondary mb-3" style="max-width: 20rem;">
            <div class="card-body">
                <img src="{{ asset('aboutus/201706105.jpg') }}" alt="Team Member 1">
                <h2>Miguel Figueiredo</h2>
                <p>up201706105@edu.fe.up.pt</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-secondary mb-3" style="max-width: 20rem;">
            <div class="card-body">
                <img src="{{ asset('aboutus/202005091.jpg') }}" alt="Team Member 1">
                <h2>Ricardo Vieira</h2>
                <p>up202005091@edu.fe.up.pt</p>
            </div>
        </div>
    </div>
</section>
@endsection
