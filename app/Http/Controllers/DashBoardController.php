<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Post;
use App\Models\Group;
use App\Models\PostLike;

use App\Http\Controllers\FileController;

use Illuminate\Support\Facades\Log;


class DashBoardController extends Controller
{
    
     public function show(string $id): View 
     {   
        $post = Post::findOrFail($id);

        return view('pages.post', [
            'post' => $post, 
        ]);
    }

    public function list()
    {   
        $posts = Post::orderBy('date', 'desc')->get();
        
        return view('pages.dashboard', [
            'posts' => $posts
        ]);
    }
    

    public function create(Request $request)
    {

        $request->validate([
            'title' => 'required|max:128',
            'content' => 'required|max:512',
            'file' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp3,mp4',
        ]);

        $post = new Post();
        
        $post->title = strip_tags($request->input('title'));
        $post->content = strip_tags($request->input('content')); 
        $post->user_id = Auth::user()->id;
        $post->save();

        $this->authorize('create', $post);
 

        $request->merge(['id' => $post->id]);
        $fileController = new FileController();
        $uploadResult = $fileController->upload($request);

        if ($uploadResult === null) {
            return response()->json(['post' => $post, 'file' => 'error']);
        }

        $post->load('user:id,name,img');
        
        $type = $request->input('type');
        
        return response()->json(['post' => $post, 'file' => $uploadResult]);
    }
    public function update(Request $request, $id)
    {
        $post = Post::find($id);

        $this->authorize('update', $post);

        $post->content = $request->input('content');

        $post->save();
        return response()->json($post);
    }

    
    public function delete(Request $request, $id)
    {
        $post = Post::find($id);
        
        //$this->authorize('delete',$post);

        if (Auth::guard('admin')->check()){
            $post->delete();
            return response()->json($post);
        }

        $user = Auth::user();
        if ($user->id === $post->user_id || $user->isModerator()){
            $post->delete();
            return response()->json($post);
        }
    }


    public function search(Request $request) {

        $query = $request->input('query');
        
        $users = User::where('username','=', $query)->take(5)->get();
        $posts = Post::whereRaw("search @@ to_tsquery('english', ?)", [$query])->get();
        $groups = Group::whereRaw("search @@ to_tsquery('english', ?)", [$query])->get();

        
        return response()->json(['users' => $users, 'posts' => $posts, 'groups' => $groups]);        
    }

    
    public function like($id){
        $liker = auth()->user();
        $post = Post::find($id);
    
        if (!$liker->likes()->where('post_id', $post->id)->exists()) {
            $liker->likes()->attach($post);
        }
    
        $likeCount = $post->likes()->count(); 
        
        return response()->json(['message' => 'Post liked','isLiked' => True, 'likeCount' => $likeCount, 'postId' => $id], 200);
    }
    
    
    public function unlike($id){
        $liker = auth()->user();
        $post = Post::find($id);
    
        if ($liker->likes()->where('post_id', $post->id)->exists()) {
            $liker->likes()->detach($post);
        }
    
        $likeCount = $post->likes()->count(); // Calculate likes count if needed
        
        return response()->json(['message' => 'Post unliked','isLiked' => False, 'likeCount' => $likeCount,'postId' => $id], 200);
    }
    
}
