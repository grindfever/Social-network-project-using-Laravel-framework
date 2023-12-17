<div class="card border-dark mb-3"  id="{{ $post->id}}">
    <article class="post" data-id="{{ $post->id }}">
        <div class="card-header">
            <a href="/profile/{{ $post->user->id }}">
                <img src="{{ $post->user->getProfileImage() }}" class="avatar">
            {{ $post->user->name }}
            </a>
            <span class="float-end">{{ \Carbon\Carbon::parse($post->date)->diffForHumans() }}</span>
        </div>
        <h1>{{ $post->title }}</h1>
        <div class="card-body">
            <p class="card-text">
                <div class="content">{{$post->content}}</div>
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
            </div>
        @endauth

    @guest
    <a href='{{route('login')}}' class="fw-light nav-link fs-6"> <span class="far fa-heart me-1">
    </span> {{ $post->likes()->count() }} </a>
    @endguest
    {{-- comments --}}
    @include('partials.comments', ['post' => $post])
</article>
</div>

