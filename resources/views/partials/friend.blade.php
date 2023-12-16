<article class="friend" data-id="{{ $friend->id }}">
    <div class="name redirecting to profile">

            @php
            if($userid==$friend->userid1){
                $userfriend = \App\Models\User::find($friend->userid2);
            }else{
                $userfriend = \App\Models\User::find($friend->userid1);
            }
            @endphp
          <a href="/profile/{{$userfriend->id}}" >{{$userfriend->name}}</a>
    </div>
    <div class="profile-picture">
        <img src="{{ asset('lbaw23102/images/' . $userfriend->img) }}" alt="Friend Picture">
    </div>
    <div class="removefriend">
        
            @csrf
            <button class="delete-friend" data-friend-id="{{$friend->id}}" type="submit">Remove Friend</button>


       
    </div>
</article>
