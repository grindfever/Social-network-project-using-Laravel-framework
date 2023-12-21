<article class="friend" data-id="{{ $friend->id }}">
    <div class="profile-picture-name">
    <div class="profile-picture">
        <img src="{{ $friend->getProfileImage()}}" class="avatar" alt="Friend Picture">
    </div>

    <div class="name redirecting to profile">
            <a href="/profile/{{$friend->id}}" >{{$friend->name}}</a>
    </div>
</div>
    <div class="removefriend">
        <button class="btn btn-dark" id="delete-friend" data-friend-id="{{$friend->id}}" type="submit">Remove Friend</button>
    </div>
</article>
