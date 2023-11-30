<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2><a href="/post/{{ $post->id }}">{{ $post->user->name }}</a></h2>
    </header>
    <div class="content">{{$post->content}}</div>
    <button class="like-post" data-post-id="{{ $post->id }}" type="submit">Like {{ $post->like }}</button>
    @auth
    <button class="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
    @endauth
</article>


