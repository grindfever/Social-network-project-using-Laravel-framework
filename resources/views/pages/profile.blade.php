@extends('layouts.app')

@section('title', $user->name)

@section ('content')


@auth('admin')
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
@endauth

<section id="profile">
    <section id="user">
        @include('partials.profile', ['user' => $user])
    </section>
    
    @if (!$me && !$areFriends && !$hasFriendRequest)
    @auth('web')
    <div class="friend-request-item" data-sender="{{ auth()->id() }}" data-receiver="{{ $user->id }}">
        <form id="friendRequestForm" data-sender="{{ auth()->id() }}" data-receiver="{{ $user->id }}">
            @csrf
            <button type="submit" class="btn btn-dark">Send Friend Request</button>
        </form>
    </div>
    @endauth
    @else
    <section id="myfriends">
    @if ($me)

    <a href="{{ route('profile.edit', ['id' => $user->id]) }}" class="btn btn-primary">Edit Profile</a>

    <a href="{{ route('friends.show') }}" class="btn btn-primary" >Friend List  </a>
    <a href="{{ route('friendrequests.index') }}" class="btn btn-primary">Friend Requests</a>
    <form action="{{ route('profile.delete', ['id' => $user->id]) }}" method="post">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn btn-danger" onclick="confirmDelete()">Delete Account</button>
    </form>    
    <!--<script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete your account?')) {
            document.getElementById('deleteForm').submit();
        }
    }
    </script>-->
    @endif
  
   
    
    
    </section>


    @endif
    <hr>
    <section class="profile-posts">
    <h2>{{ $user->name }}'s posts: </h2>
    @foreach($post as $post)
            @include('partials.post', ['post' => $post])
        @endforeach
    </section>
</section>


@endsection