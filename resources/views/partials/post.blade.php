

<div class="post-border"  id="{{ $post->id}}">
    <article class="post" data-id="{{ $post->id }}">
        <div class="card-header">
            <a href="/profile/{{ $post->user->id }}">
                <img src="{{ $post->user->getProfileImage() }}" class="avatar" alt="avatar">
            {{ $post->user->name }}
            </a>
            <span class="float-end">{{ \Carbon\Carbon::parse($post->date)->diffForHumans() }}</span>
        </div>
        <h1>{{ $post->title }}</h1>
        <div class="card-body">
            <p class="card-text">
                <div class="content">{{$post->content}}</div>
                @if ($post->img != null)
                    @php
                        $extension = pathinfo($post->img, PATHINFO_EXTENSION);
                    @endphp
                    @if (in_array($extension, ['mp4', 'webm', 'ogg']))
                        <video controls controlsList="nodownload">
                            <source id="videoSource" src="{{ $post->getPostImage() }}" type="video/{{ $extension }}" alt="post-video">
                            Your browser does not support the video tag.
                        </video>
                    @elseif (in_array($extension, ['mp3', 'wav', 'ogg']))
                        <audio controls controlsList="nodownload">
                            <source id="audioSource" src="{{ $post->getPostImage() }}" type="audio/{{ $extension }}" alt="post-audio">
                            Your browser does not support the audio element.
                        </audio>
                    @else
                        <img src="{{ $post->getPostImage() }}" class="post-image" alt="post-image">
                    @endif
                @endif
            </p>
        </div>
        @auth
            <div class="like-post">
                @if (Auth::user()->likesPost($post))
                    <button type="submit" class="like-count liked" data-post-id="{{$post->id}}" >
                        <span class="fas fa-heart me-1"></span>{{ $post->likes()->count() }}
                    </button>
                @else
                    <button type="submit" class="like-count" data-post-id="{{$post->id}}">
                        <span class="far fa-heart me-1"></span>{{ $post->likes()->count() }}
                    </button>
                @endif
                <span class="far fa-comment me-1 fa-lg"></span> {{ $post->comments()->count() }}
            </div>
        @endauth

    @guest
    <a href='{{route('login')}}' class="fw-light nav-link fs-6"> 
        <span class="far fa-heart me-1"></span> {{ $post->likes()->count() }} </a>
    <span class="fw-light nav-link fs-6"> 
        <span class="far fa-comment me-1"></span> {{ $post->comments()->count() }} </span>
    @endguest
</article>
</div>

