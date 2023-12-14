<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Post;


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
            'post' => $post
        ]);
    }

    /**
     * Shows all posts.
     */
    public function list()
    {
        
        $post = Post::all();

        // Use the pages.dashboard template to display all posts.
        return view('pages.dashboard', [
            'post' => $post,
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
        // Find the item.
        $post = Post::find($id);

        // Check if the current user is authorized to update this item.
        $this->authorize('update', $post);

        // Update the done property of the item.
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
        $users = User::where('name', 'like', '%' . $query . '%')->take(5)->get();

        $posts = Post::where('content','like', '%' . $query . '%')->take(5)->get();

        //dd(response()->json(['users' => $users, 'posts' => $posts]));

        return response()->json(['users' => $users, 'posts' => $posts]);        
    }
}
