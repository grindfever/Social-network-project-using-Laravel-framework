<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Moderator;




class ModeratorController extends Controller
{
    public function reportList(){
        if (Auth::guest()){
            return redirect('/');
        }
        $user = Auth::user();

        if (!$user->isModerator()){
            return redirect('/');
        }
        return view('pages.reports');
    }
    

    public function create(Request $request, $id)
    {
        DB::table('moderators')->insert([
            'id' => $id,
        ]);

        return response()->json();
    }

    public function remove(Request $request, $id)
    {
        $moderator = Moderator::find($id);
        $moderator->delete();
        return response()->json($moderator);
    }

    public function teste(){
        $moderators = Moderator::all();
        dd($moderators);
    }
}
