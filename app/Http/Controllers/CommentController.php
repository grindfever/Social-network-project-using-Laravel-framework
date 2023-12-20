<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Comment;
use App\Models\CommentLike;

use Illuminate\Support\Facades\Log;


class CommentController extends Controller
{
    public function store(Request $request, $post_id)
    {
        
        $request->validate([
            'content' => 'required|max:255',
        ]);

        $comment = new Comment();
        $comment->content = strip_tags($request->input('content'));
        $comment->user_id = Auth::id();
        $comment->post_id = $post_id;
        $comment->date = now();
        
        $comment->save();

        $user = Auth::user(); // Get the user who created the post
        
        return response()->json([
            'comment' => $comment,
            'user' => $user,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|max:255',
        ]);
        $comment = Comment::findOrFail($id);
        $comment->content = strip_tags($request->input('content'));
        $comment->save();

        return response()->json([
            'comment' => $comment,
        ]);
    }

    public function delete(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

        return response()->json([
            'message' => 'Comment deleted successfully',
            'id' => $id,
        ]);
    }

    public function like($id)
    {
        $liker = auth()->user();
        $comment = Comment::findOrFail($id);
        
        if(!$liker->commentsLikes()->where('comment_id', $comment->id)->exists()) {
            $liker->commentsLikes()->attach($comment);
        }

        $likeCount = $comment->likes()->count();

        return response()->json([
            'message' => 'Comment liked successfully',
            'isLiked' => True,
            'likeCount' => $likeCount,
            'id' => $id,
        ],200);
    }

    public function unlike($id){
        $liker = Auth::user();
        $comment = Comment::find($id);
    
        if ($liker->commentsLikes()->where('comment_id', $comment->id)->exists()) {
            $liker->commentsLikes()->detach($comment);
        }
    
        $likeCount = $comment->likes()->count(); // Calculate likes count if needed
        
        return response()->json(['message' => 'Comment unliked','isLiked' => False, 'likeCount' => $likeCount,'id' => $id], 200);
    }

}
