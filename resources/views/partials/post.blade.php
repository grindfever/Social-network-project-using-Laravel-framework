<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2><a href="/post/{{ $post->id }}">{{ $post->user->name }}</a></h2>
    </header>
    <div class="content">{{$post->content}}</div>
   
    @auth
        <button class="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
        <div class ="like-post">
            @if (Auth::user()->likesPost($post))
            <form action="{{ route('post.unlike', $post->id)}}" method="POST">
                @csrf
                <button type="submit" class="like-count"> <span class="fas fa-heart me-1">
                    </span> {{ $post->likes()->count() }} </button>
            </form>
            @else
            <form action="{{ route('post.like', $post->id)}}" method="POST">
                @csrf
                <button type="submit" class="like-count"> <span class="far fa-heart me-1">
                    </span> {{ $post->likes()->count() }} </button>
            </form>
            @endif
        </div>
    @endauth
    @guest
    <a href='{{route('login')}}' class="fw-light nav-link fs-6"> <span class="far fa-heart me-1">
    </span> {{ $post->likes()->count() }} </a>
    @endguest
</article>
