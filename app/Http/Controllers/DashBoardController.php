<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Post;
use App\Models\PostLike;


class DashBoardController extends Controller
{
    
     /**
     * Show the post for a given id.
     */
     public function show(string $id): View 
     {   
        // Get the post.
        $post = Post::findOrFail($id);
      
        // Use the pages.post template to display the post.
        return view('pages.post', [
            'post' => $post, 
        ]);
    }

    /**
     * Shows all posts.
     */
    public function list()
    {
        $posts = Post::orderBy('date', 'desc')->get();
        
        // Use the pages.dashboard template to display all posts.
        return view('pages.dashboard', [
            'posts' => $posts
        ]);
    }
    

    /**
     * Create a new post
     */
    public function create(Request $request)
    {
        // Create a blank new Post.
        $post = new Post();
        
        // Check if the current user is authorized to create this card.
        $this->authorize('create', $post);

        // Set post details
        $post->title = $request->input('title');
        $post->content = $request->input('content'); 
        $post->user_id = Auth::user()->id;

        // Save the card and return it as JSON.
        $post->save();

        $post->load('user:id,name');

        return response()->json($post);
    }

    /**
     * Updates the state of an individual post.
     */
    public function update(Request $request, $id)
    {
        // Find the post.
        $post = Post::find($id);

        // Check if the current user is authorized to update this post.
        $this->authorize('update', $post);

        // Update the content property of the post.
        $post->content = $request->input('content');

        // Save the post and return it as JSON.
        $post->save();
        return response()->json($post);
    }

    /**
     * Delete a post.
     */
    public function delete(Request $request, $id)
    {
        // Find the post.
        $post = Post::find($id);
        
        // Check if the current user is authorized to delete this post.
        $this->authorize('delete',$post);

        // Delete the post and return it as JSON.
        $post->delete();
        return response()->json($post);
    }
    
    public function like($id){
        $liker = auth()->user();
        $post = Post::find($id);
    
        if (!$liker->likes()->where('post_id', $post->id)->exists()) {
            $liker->likes()->attach($post);
        }
    
        $likeCount = $post->likes()->count(); // Calculate likes count if needed
        //return redirect()->route('DashBoard')->with('success', 'Post liked');
        return response()->json(['message' => 'Post liked','isLiked' => True, 'likeCount' => $likeCount, 'postId' => $id], 200);
    }
    
    
    public function unlike($id){
        $liker = auth()->user();
        $post = Post::find($id);
    
        if ($liker->likes()->where('post_id', $post->id)->exists()) {
            $liker->likes()->detach($post);
        }
    
        $likeCount = $post->likes()->count(); // Calculate likes count if needed
        //return redirect()->route('DashBoard')->with('success', 'Post unliked');
        return response()->json(['message' => 'Post unliked','isLiked' => False, 'likeCount' => $likeCount,'postId' => $id], 200);
    }
    
    
}
