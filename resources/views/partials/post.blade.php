<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2><a href="/post/{{ $post->id }}">Nome do user</a></h2>
        {{$post->content}}
    </header>
    <button class="delete-post" data-post-id="{{ $post->id }}" type="submit">Delete</button>
</article>


