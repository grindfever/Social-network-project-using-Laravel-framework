<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2><a href="/post/{{ $post->id }}">{{ $post->user->name }}</a></h2>
    </header>
    @auth('web')
    @if(!Auth::user()->isModerator())
        <button class="report-post" data-post-id="{{ $post->id }}" type="submit">Report</button>
    @endif
    @endauth
    <div class="content">{{$post->content}}</div>
    @auth('admin')
    <button class="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
    @endauth
</article>


