<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\Post;
use App\Models\User;
use App\Models\Group;
use App\Models\Moderator;
use App\Models\Message;
use App\Models\GroupMessage;
use App\Models\Comment;


class AdminController extends Controller
{

    public function showDashboard(){
        if (!Auth::guard('admin')->check()){
            return redirect('/login');
        }
        else { 

                $total_weekly_posts = Post::where('date', '>', Carbon::now()->subDays(7))->count();
                $total_monthly_posts = Post::where('date', '>', Carbon::now()->subDays(30))->count();
                $total_daily_posts = Post::where('date', '>', Carbon::now()->subDays(1))->count();
                $total_yearly_posts = Post::where('date', '>', Carbon::now()->subDays(365))->count();
                $total_posts = Post::count();
            
                $total_weekly_messages = Message::where('date', '>', Carbon::now()->subDays(7))->count();
                $total_monthly_messages = Message::where('date', '>', Carbon::now()->subDays(30))->count();
                $total_daily_messages = Message::where('date', '>', Carbon::now()->subDays(1))->count();
                $total_yearly_messages = Message::where('date', '>', Carbon::now()->subDays(365))->count();
                $total_messages = Message::count();
            
                $total_weekly_group_messages = GroupMessage::where('date', '>', Carbon::now()->subDays(7))->count();
                $total_monthly_group_messages = GroupMessage::where('date', '>', Carbon::now()->subDays(30))->count();
                $total_daily_group_messages = GroupMessage::where('date', '>', Carbon::now()->subDays(1))->count();
                $total_yearly_group_messages = GroupMessage::where('date', '>', Carbon::now()->subDays(365))->count();
                $total_group_messages = GroupMessage::count();
            
                $total_weekly_comments = Comment::where('date', '>', Carbon::now()->subDays(7))->count();
                $total_monthly_comments = Comment::where('date', '>', Carbon::now()->subDays(30))->count();
                $total_daily_comments = Comment::where('date', '>', Carbon::now()->subDays(1))->count();
                $total_yearly_comments = Comment::where('date', '>', Carbon::now()->subDays(365))->count();
                $total_comments = Comment::count();
                $total_users = User::count();
                $total_moderators = Moderator::count();
                $total_groups = Group::count();

                
                return view('pages.admin.dashboard', [
                    'daily_posts' => $total_daily_posts,
                    'weekly_posts' => $total_weekly_posts,
                    'monthly_posts' => $total_monthly_posts,
                    'yearly_posts' => $total_yearly_posts,
                    'total_posts' => $total_posts,
                    'daily_messages' => $total_daily_messages,
                    'weekly_messages' => $total_weekly_messages,
                    'monthly_messages' => $total_monthly_messages,
                    'yearly_messages' => $total_yearly_messages,
                    'total_messages' => $total_messages,
                    'daily_group_messages' => $total_daily_group_messages,
                    'weekly_group_messages' => $total_weekly_group_messages,
                    'monthly_group_messages' => $total_monthly_group_messages,
                    'yearly_group_messages' => $total_yearly_group_messages,
                    'total_group_messages' => $total_group_messages,
                    'daily_comments' => $total_daily_comments,
                    'weekly_comments' => $total_weekly_comments,
                    'monthly_comments' => $total_monthly_comments,
                    'yearly_comments' => $total_yearly_comments,
                    'total_comments' => $total_comments,
                    'total_users' => $total_users,
                    'total_moderators' => $total_moderators,
                    'total_groups' => $total_groups,
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
