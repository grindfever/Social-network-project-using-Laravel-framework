<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Post;

class DashBoardController extends Controller
{
    
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
        $post->body = $request->input('body');
        $post->user_id = Auth::user()->id;

        // Save the card and return it as JSON.
        $card->save();
        return response()->json($post);
    }

 
    /**
     * Show the post for a given id.
     */

    public function showPost(string $id): View {
        
        // Get the card.
        $post = Card::findOrFail($id);

        // Check if the current user can see (show) the card.
        $this->authorize('show', $post);  

        // Use the pages.dashboard template to display the card.
        return view('pages.dashboard', [
            'post' => $post
        ]);

    }

    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
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
}
