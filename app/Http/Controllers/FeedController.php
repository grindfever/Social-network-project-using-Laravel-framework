<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Post;

class FeedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        
        $friends = Auth::user()->friends()->pluck('id');
     
        $posts = Post::whereIn('user_id',$friends)->get();

        return view('pages.dashboard', [
            'posts' => $posts
        ]);
  
    }
}
