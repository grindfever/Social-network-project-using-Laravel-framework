@extends('layouts.app')

@section('content')
    <header class="card-header text-center mb-5">
        <h1 class="main-features-header">Main Features<h1>
    </header>
    <section class="p-5">

        <article class="card mb-5">
            <h3 class="card-header">As a guest, you can:</h3>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Login/Logout</li>
                <li class="list-group-item">Register</li>
                <li class="list-group-item">Recover password</li>
                <li class="list-group-item">View public timeline</li>
                <li class="list-group-item">View public profiles</li>
            </ul>
        </article> 

        <article class="card mb-5">
            <h3 class="card-header">As an authenticated user, you can:</h3>
            <ul class="list-group list-group-flush"> 
                <li class="list-group-item">View profile</li>
                <li class="list-group-item">Edit profile</li>
                <li class="list-group-item">Support profile picture</li>
                <li class="list-group-item">View timeline</li>
                <li class="list-group-item">Search for public Users</li>
                <li class="list-group-item">Full-text search</li>
                <li class="list-group-item">Send friend requests</li>
                <li class="list-group-item">Search for posts, comments, groups and users</li>
                <li class="list-group-item">Manage received follow requests</li>
                <li class="list-group-item">Create post</li>
                <li class="list-group-item">Comment on posts</li>
                <li class="list-group-item">Like posts</li>
                <li class="list-group-item">Comment posts</li>
                <li class="list-group-item">Reply to comments</li>
                <li class="list-group-item">Like comments</li>
                <li class="list-group-item">Create groups</li>
                <li class="list-group-item">View users' feed</li>
            </ul> 
            
        </article>
        <article class="card mb-5">
            <h3 class="card-header">As the author of a post, you can:</h3>
            <ul class="list-group list-group-flush"> 
                <li class="list-group-item">Edit post</li>
                <li class="list-group-item">Delete post</li>
            </ul>
        </article>
        <article class="card mb-5">
            <h3 class="card-header">As the author of a comment, you can:</h3>
            <ul class="list-group list-group-flush"> 
                <li class="list-group-item">Edit comment</li>
                <li class="list-group-item">Delete comment</li>
            </ul>
        </article>
        <article class="card mb-5">
            <h3 class="card-header">As a member of a group, you can:</h3>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">View group information</li>
                <li class="list-group-item">Message group</li>
                <li class="list-group-item">Leave group</li>
            </ul>
        </article>
        <article class="card mb-5">
            <h3 class="card-header">As an owner of a group, you can:</h3>
            <ul class="list-group list-group-flush"> 
                <li class="list-group-item">Edit group information</li>
                <li class="list-group-item">Remove member</li>
                <li class="list-group-item">Add to group</li>
                <li class="list-group-item">Delete group</li>
            </ul>
        </article>
        <article class="card mb-5">
            <h3 class="card-header">The admins are able to do the following actions:</h3>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Administer user accounts and roles</li>
                <li class="list-group-item">Block and unblock user accounts</li>
                <li class="list-group-item">Delete user account</li>
                <li class="list-group-item">Access an admin page with statistics</li>
                <li class="list-group-item">Everything moderators can also do</li>
            </ul>
        </article>
        <article class="card mb-5">
            <h3 class="card-header">The moderators are able to do the following actions:</h3>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Remove posts</li>
                <li class="list-group-item">Delete groups</li>
                <li class="list-group-item">Ban users</li>
            </ul>
        </article>
        <article class="card mb-5">
            <h3 class="card-header">Features included to help you:</h3>
            <ul class="list-group list-group-flush"> 
                <li class="list-group-item">Text in form inputs</li>
                <li class="list-group-item">Contextual help</li>
                <li class="list-group-item">About us/Contacts</li>
                <li class="list-group-item">Main features</li>
            </ul>
        </article>
    </section>
@endsection