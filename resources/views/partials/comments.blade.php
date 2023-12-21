<div class="comments" data-id="{{ $post->id }}">
    @auth('web')
    <form class="new_comment" action="{{ route('post.comment.store', $post->id)}}" method="POST">
        @csrf
        <div class="form-group">
            <textarea placeholder="Post your comment" class="form-control" id="exampleTextarea" rows="3" data-lt-tmp-id="lt-205407" spellcheck="false" data-gramm="false" style="line-height: 24px;"></textarea>
        </div>
        <button id="submit_comment" type="submit" class="btn btn-dark"> Post Comment </button>
    </form>
    @endauth

    <h4>Comments</h4>
    
    <ul class="list-group list-group-flush">
            @foreach ($post->comments as $comment)
            <div class="comment-container" data-id="{{$comment->id}}">
                <a href="/profile/{{ $comment->user->id }}" class="profile_avatar">
                    <img src="{{ $comment->user->getProfileImage() }}" class="avatar">{{ $comment->user->name }}
                </a>
                <span class="float-end">{{ \Carbon\Carbon::parse($comment->date)->diffForHumans() }}</span>
                <li class="list-group-item" >{{ $comment->content }}</li>
                <div class="like-comment">
                    @if (!Auth::guard('admin')->check() && Auth::user()->likesComment($comment))
                        <button type="submit" class="like-comment liked" data-comment-id="{{$comment->id}}" >
                            <span class="fas fa-heart me-1"></span>{{ $comment->likes()->count() }}
                        </button>
                    @else
                        <button type="submit" class="like-comment" data-comment-id="{{$comment->id}}">
                            <span class="far fa-heart me-1"></span>{{ $comment->likes()->count() }}
                        </button>
                    @endif
                </div>
                
                @if ($comment->user_id === auth()->id())
                    <div class="float-end" style="padding-top: 10px;">
                        <button id="edit-comment" data-id="{{$comment->id}}" class="btn btn-sm btn-primary" onClick="editComment({{$comment->id}})">Edit</button>
                        <button id="delete-comment" data-id="{{$comment->id}}" class="btn btn-sm btn-danger" onClick="sendDeleteCommentRequest({{$comment->id}})">Delete</button>
                    </div>
                @endif
            </div>
            @endforeach
    </ul>
</div>