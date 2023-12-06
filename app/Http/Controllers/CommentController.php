<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function store(Request $request, $post_id)
    {
        dump($request->all());
        $request->validate([
            'content' => 'required|max:255',
        ]);

        $comment = new Comment();
        $comment->content = $request->input('content');
        $comment->user_id = Auth::id();
        $comment->post_id = $post_id;
        $comment->date = now();
        $comment->save();

        return redirect()->route('home', ['id' => $post_id])->with('success', 'Comment created successfully.');
    }

    public function show(string $id)
    {
        // Get the post.
        $post = Post::findOrFail($id);

        // Use the pages.post template to display the post.
        return view('pages.post', [
            'post' => $post,
        ]);
    }
}