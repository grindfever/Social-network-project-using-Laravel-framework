<div class="comments" data-id="{{ $post->id }}">
    <h4>Comments</h4>
        <ul class="list-group list-group-flush">
                @foreach ($post->comments as $comment)
                    <a href="/profile/{{ $post->user->id }}">
                        <img src="{{ $comment->user->getProfileImage() }}" class="avatar">{{ $comment->user->name }}
                    </a>
                    <span class="float-end">{{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }}</span>
                    <li class="list-group-item">{{ $comment->content }}</li>
                @endforeach
        </ul>
    <form class="new_comment" action="{{ route('post.comment.store', $post->id)}}" method="POST">
        @csrf
        <div class="form-group">
            <textarea placeholder="Post your comment" class="form-control" id="exampleTextarea" rows="3" data-lt-tmp-id="lt-205407" spellcheck="false" data-gramm="false" style="line-height: 24px;"></textarea>
        </div>
        <button id="submit_comment" type="submit" class="btn btn-dark"> Post Comment </button>
    </form>
</div>