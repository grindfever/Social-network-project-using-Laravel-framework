<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

class SearchController extends Controller {
    
    
    public function search(Request $request) {    
        $results = User::where('name', 'like', '%' . $request->input('query') . '%')->get();

        return view('pages.search', ['results'=> $results, 'empty' => $results->empty()]);
    }
}