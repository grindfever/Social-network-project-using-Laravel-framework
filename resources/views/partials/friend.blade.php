<div class="friend">
    <div class="name redirecting to profile">
        {{ $friend->friendUser->name }}
    </div>
    <div class="profile-picture">
        <img src="{{ asset('lbaw23102/images/' . $friend->friendUser->img) }}" alt="Friend Picture">
    </div>
    <div class="removefriend">
        <form action="{{ route('friends.remove', ['friendId' => $friend->id]) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit">Remove Friend</button>
        </form>
    </div>
</div>
