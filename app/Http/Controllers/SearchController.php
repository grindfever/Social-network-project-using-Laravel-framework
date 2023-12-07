<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Post;

class SearchController extends Controller {
    

    public function show() {
        return view('pages.search');
    }
    public function search(Request $request) {

        //if ($request->has("query")) {
        $query = $request->input('query');
        $users = User::where('name', 'like', '%' . $query . '%')->get();

        $posts = Post::where('content','like', '%' . $query . '%')->get();

        return response()->json($users);        
    }
}