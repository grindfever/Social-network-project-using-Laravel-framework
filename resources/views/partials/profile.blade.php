<div class="user">
    @if($user->profile_picture)
        <div class="profile-picture">
            <img src="{{ asset('#') }}" alt="Profile Picture">  //por caminho para as imagens
        </div>
    @endif

    <div class="user-info">
        <div class="name">
            <h2>{{ $user->name }}</h2>
        </div>

        <div class="email">
            <p>Email: {{ $user->email }}</p>
        </div>

        <div class="age">
            <p>Age: {{ $user->age }}</p>
        </div>

        <div class="bio">
            <p>Description: {{ $user->bio }}</p>
        </div>
        
        <label>
            <input type="checkbox" {{ $user->priv?'checked':''}}>
            <span>Private Account</span>
        </label>
    </div>
</div>
