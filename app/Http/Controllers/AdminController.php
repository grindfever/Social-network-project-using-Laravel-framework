<?php

namespace App\Http\Controllers;

use App\Models\Moderator;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;


class AdminController extends Controller
{

    public function showDashboard(){
        if (!Auth::guard('admin')->check()){
            return redirect('/login');
        }
        else { 

            $total_weekly_posts = Post::where('date','>',Carbon::now()->subDays(7))->get()->count();
            $total_monthly_posts = Post::where('date','>', Carbon::now()->subDays(30))->get()->count();
            $total_daily_posts = Post::where('date','>', Carbon::now()->subDays(1))->get()->count();
            $total_yearly_posts = Post::where('date','>', Carbon::now()->subDays(365))->get()->count();
            $total_posts = Post::all()->count();

            return view('pages.admin.dashboard', 
                        [
                        'daily_posts'=> $total_daily_posts,
                        'weekly_posts'=> $total_weekly_posts, 
                        'monthly_posts'=> $total_monthly_posts, 
                        'yearly_posts'=> $total_yearly_posts, 
                        'total_posts'=> $total_posts
                        ]); 
        }
    }

    public function moderators(){
        if (!Auth::guard('admin')->check()){
            return redirect('/');
        }
        
        $moderators = Moderator::with('user')->get();

        return view('pages.admin.moderators',['moderators' => $moderators]);
    }

    public function users(){
        if (!Auth::guard('admin')->check() && !Auth::user()->isModerator()){
            return redirect('/');
        }
        
        $users = User::all();

        return view('pages.admin.users',['users' => $users]);
    }

    public function groups(){
        if (!Auth::guard('admin')->check() && !Auth::user()->isModerator()){
            return redirect('/');
        }
        
        $groups = Group::all();

        return view('pages.admin.groups',['groups' => $groups]);
    }

    public function posts(){
        if (!Auth::guard('admin')->check() && !Auth::user()->isModerator()){
            return redirect('/');
        }
        
        $posts = Post::all();

        return view('pages.admin.posts',['posts' => $posts]);
    }
}
