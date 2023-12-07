<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

use App\Models\Post;

class AdminController extends Controller
{

    public function showDashboard(){
        if (!Auth::guard('admin')->check()){
            return redirect('/login');
        }
        else { 


            /*
            $weekly_posts = array();
            for ($i = 0; $i < 7 ; $i++) {
                 $weekly_posts[] = Post::where('date','>',Carbon::now()->subDays($i))->get()->count();
            }
            */

            $total_weekly_posts = Post::where('date','>',Carbon::now()->subDays(7))->get()->count();
            $total_monthly_posts = Post::where('date','>', Carbon::now()->subDays(30))->get()->count();
            $total_daily_posts = Post::where('date','>', Carbon::now()->subDays(1))->get()->count();
            $total_yearly_posts = Post::where('date','>', Carbon::now()->subDays(365))->get()->count();
            $total_posts = Post::all()->count();

            return view('admin.dashboard', 
                        [
                        'daily_posts'=> $total_daily_posts,
                        'weekly_posts'=> $total_weekly_posts, 
                        'monthly_posts'=> $total_monthly_posts, 
                        'yearly_posts'=> $total_yearly_posts, 
                        'total_posts'=> $total_posts
                        ]); 
        }
    }

}
