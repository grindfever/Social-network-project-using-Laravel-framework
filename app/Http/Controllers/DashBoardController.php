<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Post;
use App\Models\PostLike;

use App\Http\Controllers\FileController;

use Illuminate\Support\Facades\Log;


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

        // Validate the request.
        $request->validate([
            'title' => 'required|max:128',
            'content' => 'required|max:512',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp3,mp4',
        ]);

        // Create a blank new Post.
        $post = new Post();
        
        // Set post details
        $post->title = strip_tags($request->input('title'));
        $post->content = strip_tags($request->input('content')); 
        $post->user_id = Auth::user()->id;
        $post->save();
        // Check if the current user is authorized to create this card.
        $this->authorize('create', $post);
 
        // Call the upload function from FileController

        $request->merge(['id' => $post->id]);
        $fileController = new FileController();
        $uploadResult = $fileController->upload($request);

        if ($uploadResult === null) {
            // Handle error, maybe return a response indicating the upload failed
            return response()->json(['post' => $post, 'file' => 'error']);
        }

        $post->load('user:id,name,img');
        
        $type = $request->input('type');
        
        return response()->json(['post' => $post, 'file' => $uploadResult]);
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


    public function search(Request $request) {

        $query = $request->input('query');
        
        $users = User::where('username','=', $query)->take(5)->get();
        $posts = Post::whereRaw("search @@ to_tsquery('english', ?)", [$query])->get();

        
        return response()->json(['users' => $users, 'posts' => $posts]);        
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
