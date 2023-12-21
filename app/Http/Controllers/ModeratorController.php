<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Moderator;
use App\Models\ReportUser;
use App\Models\ReportPost;
use App\Models\ReportGroup;
use App\Models\Ban;
use App\Models\User;



class ModeratorController extends Controller
{
    public function reportList(){
        if (!Auth::guard('admin')->check()){
            if (Auth::guest()){
                return redirect('/');
            }
            $user = Auth::user();

            if (!$user->isModerator()){
                return redirect('/');
            }
        }

        $userReports = ReportUser::orderBy('date','desc')->get();
        $postReports = ReportPost::orderBy('date','desc')->get();
        $groupReports = ReportGroup::orderBy('date','desc')->get();

        return view('pages.reports', ['userReports'=>$userReports,'postReports'=>$postReports,'groupReports'=>$groupReports]);
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

    public function ban(Request $request, $id)
    {
        $user = User::find($id);

        if(Auth::guard('admin')->check()){
            DB::table('bans')->insert([
                'admin' => Auth::guard('admin')->id(),
                'user_id' => $id,
            ]);
        }
        else {
            if ($user->isModerator()){
                return response()->json();
            }
            DB::table('bans')->insert([
                'moderator' => Auth::user()->id,
                'user_id' => $id,
            ]);
        }
        DB::table('users')->where('id', $id)->update(['remember_token' => null]);

        return response()->json($id);
    }

    public function unban(Request $request, $id)
    {
        Ban::where('user_id','=',$id)->delete();

        return response()->json($id);
    }
}
