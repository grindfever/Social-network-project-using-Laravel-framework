<article class="friend" data-id="{{ $friend->id }}">

    <div class="profile-picture">
        <img src="{{ $friend->getProfileImage()}}" class="avatar" alt="Friend Picture">
    </div>

    <div class="name-redirect">
            <a href="/profile/{{$friend->id}}" >{{$friend->name}}</a>
    </div>

    <div class="removefriend">
        <button class="btn btn-dark" id="delete-friend" data-friend-id="{{$friend->id}}" type="submit">Remove Friend</button>
    </div>
</article>
