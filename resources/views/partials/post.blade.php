<div class="card border-dark mb-3" style="max-width: 20rem;" id="{{ $post->id}}">
    <article class="post" data-id="{{ $post->id }}">
        <div class="card-header"><a href="/post/{{ $post->id }}">{{ $post->user->name }}</a></div>
            <div class="card-body">
                <p class="card-text"><div class="content">{{$post->content}}</div></p>
            </div>
        
        @auth
            <button class="btn btn-primary" id="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
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
                <lt-mirror contenteditable="false" style="display: none;" data-lt-linked="1"><lt-highlighter contenteditable="false" style="display: none;"><lt-div spellcheck="false" class="lt-highlighter__wrapper" style="width: 634px !important; height: 101px !important; transform: none !important; transform-origin: 318px 51.5px 0px !important; margin-top: 54px !important; margin-left: 1px !important;"><lt-div class="lt-highlighter__scroll-element" style="top: 0px !important; left: 0px !important; width: 634px !important; height: 101px !important;"></lt-div></lt-div></lt-highlighter><lt-div spellcheck="false" class="lt-mirror__wrapper notranslate" style="border: 1px solid rgb(222, 226, 230) !important; border-radius: 0px !important; direction: ltr !important; font: 16px / 24px &quot;Open Sans&quot;, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot; !important; font-synthesis: weight style small-caps position !important; hyphens: manual !important; letter-spacing: normal !important; line-break: auto !important; margin: 53px 0px 0px !important; padding: 6px 8px !important; text-align: start !important; text-decoration: rgb(34, 34, 34) !important; text-indent: 0px !important; text-rendering: optimizelegibility !important; text-transform: none !important; transform: none !important; transform-origin: 318px 51.5px 0px !important; unicode-bidi: normal !important; white-space: pre-wrap !important; word-spacing: 0px !important; overflow-wrap: break-word !important; writing-mode: horizontal-tb !important; width: 618px !important; height: 89px !important;" data-lt-scroll-top="0" data-lt-scroll-left="0" data-lt-scroll-top-scaled="0" data-lt-scroll-left-scaled="0" data-lt-scroll-top-scaled-and-zoomed="0" data-lt-scroll-left-scaled-and-zoomed="0"><lt-div class="lt-mirror__canvas" style="margin-top: 0px !important; margin-left: 0px !important; width: 618px !important; height: 89px !important;"></lt-div></lt-div><lt-div class="lt-mirror__measurer"></lt-div></lt-mirror>
                <textarea placeholder="Post your comment" class="form-control" id="exampleTextarea" rows="3" data-lt-tmp-id="lt-205407" spellcheck="false" data-gramm="false" style="line-height: 24px;"></textarea>
            </div>
            <button id="submit_comment" type="submit" class="btn btn-primary btn-sm"> Post Comment </button>
        </form>
    </div>
</article>
</div>

