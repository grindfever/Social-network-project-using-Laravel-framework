<div class="comments" data-id="{{ $post->id }}">
    <form class="new_comment" action="{{ route('post.comment.store', $post->id)}}" method="POST">
        @csrf
        <div class="form-group">
            <textarea placeholder="Post your comment" class="form-control" id="exampleTextarea" rows="3" data-lt-tmp-id="lt-205407" spellcheck="false" data-gramm="false" style="line-height: 24px;"></textarea>
        </div>
        <button id="submit_comment" type="submit" class="btn btn-dark"> Post Comment </button>
    </form>

    <h4>Comments</h4>
    
    <ul class="list-group list-group-flush">
            @foreach ($post->comments as $comment)
            <div class="comment-container" style="border: 2px solid #000; border-radius: 10px; padding: 15px; margin-bottom: 10px">
                <a href="/profile/{{ $comment->user->id }}" class="profile_avatar">
                    <img src="{{ $comment->user->getProfileImage() }}" class="avatar">{{ $comment->user->name }}
                </a>
                <span class="float-end">{{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }}</span>
                <li class="list-group-item" >{{ $comment->content }}</li>
                @if ($comment->user_id === auth()->id())
                    <div class="float-end" style="padding-top: 10px;">
                        <button class="btn btn-sm btn-primary">Edit</button>
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </div>
                @endif
            </div>
            @endforeach
    </ul>
</div>