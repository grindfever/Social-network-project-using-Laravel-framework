<article class="post" data-id="{{ $post->id }}">
    <header>
        <h2><a href="/post/{{ $post->id }}">Title</a></h2>
        {{ $post->content}}
        <a href="#" class="delete">&#10761;</a>
    </header>

</article>