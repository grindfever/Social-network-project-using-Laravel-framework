<div class="user">


    <div class="user-info">
        <div class="name">
            <h2>{{ $user->name }}</h2>
            
        </div>
        <div class="profile-picture">
    
             <img src="{{ public_path('lbaw23102/images/' . $user->img) }}" alt="Profile Picture">
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
