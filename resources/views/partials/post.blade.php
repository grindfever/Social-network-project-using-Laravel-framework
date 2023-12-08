<div class="card border-dark mb-3" style="max-width: 20rem;">
    

<article class="post" data-id="{{ $post->id }}">
    <div class="card-header"><a href="/post/{{ $post->id }}">{{ $post->user->name }}</a></div>
    <div class="card-body">
        <p class="card-text"><div class="content">{{$post->content}}</div></p>
    </div>
   
    @auth
        <button class="btn btn-primary" id="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
        <div class="like-post">
            @if (Auth::user()->likesPost($post))
                <button type="submit" class="like-count liked"> <span class="fas fa-heart me-1">
                    </span> {{ $post->likes()->count() }} </button>
            @else
                
                <button type="submit" class="like-count"> <span class="far fa-heart me-1">
                    </span> {{ $post->likes()->count() }} </button>
            @endif
        </div>
    @endauth

    @guest
    <a href='{{route('login')}}' class="fw-light nav-link fs-6"> <span class="far fa-heart me-1">
    </span> {{ $post->likes()->count() }} </a>
    @endguest
    {{-- comments --}}
    <div>
        
         @foreach ($post->comments as $comment)
        <p class="fw-light fs-6"> {{ $comment->user->name }}: {{ $comment->content }} </p>
         @endforeach
        <form action="{{ route('post.comment.store', $post->id)}}" method="POST">
            @csrf
            <div class="mb-3">
                <textarea class="fs-6 form-control" name="content" rows="1" placeholder="Whats on your mind?"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm"> Post Comment </button>
        </form>
    </div>
</article>
</div>

