<div class="card border-dark mb-3" style="max-width: 20rem;" id="{{ $post->id}}">
    <article class="post" data-id="{{ $post->id }}">
        <h1>{{ $post->title }}</h1>
        <div class="card-header"><a href="/profile/{{ $post->user->id }}">{{ $post->user->name }}</a></div>
            <div class="card-body">
                <p class="card-text"><div class="content">{{$post->content}}</div></p>
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
    <div class="comments" data-id="{{ $post->id }}">
         @foreach ($post->comments as $comment)
        <p class="fw-light fs-6"> {{ $comment->user->name }}: {{ $comment->content }} </p>
         @endforeach
        <form class="new_comment" action="{{ route('post.comment.store', $post->id)}}" method="POST">
            @csrf
            <div class="form-group">
                <textarea placeholder="Post your comment" class="form-control" id="exampleTextarea" rows="3" data-lt-tmp-id="lt-205407" spellcheck="false" data-gramm="false" style="line-height: 24px;"></textarea>
            </div>
            <button id="submit_comment" type="submit" class="btn btn-dark"> Post Comment </button>
        </form>
    </div>
</article>
</div>

