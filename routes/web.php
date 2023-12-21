<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupMessageController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FriendRequestController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\FeedController;

use App\Http\Controllers\ModeratorController;


use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\DB;

use App\Mail\MyEmail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/dashboard')->name('home');

// Dashboard
Route::controller(DashBoardController::class)->group(function () {
    Route::get('/dashboard','list')->name('DashBoard');
    Route::get('/post/{id}','show');
});

Route::get('/feed', FeedController::class )->middleware('auth')->name('feed');


/*
 *    API
 */ 
 Route::post('/dashboard/search', [DashBoardController::class, 'search']);
 Route::post('/dashboard/create', [DashBoardController::class, 'create']);
 Route::delete('api/post/{post_id}', [DashBoardController::class, 'delete']);
 Route::put('api/post/{post_id}', [DashBoardController::class, 'update']);
 Route::post('api/post/{post_id}/like', [DashBoardController::class, 'like'])->middleware('auth')->name('post.like');
 Route::delete('api/post/{post_id}/unlike', [DashBoardController::class, 'unlike'])->middleware('auth')->name('post.unlike');

 // Comments
 Route::post('api/post/{post_id}/comment', [CommentController::class, 'store'])->middleware('auth')->name('post.comment.store');
 Route::delete('api/comment/{comment_id}', [CommentController::class, 'delete'])->middleware('auth')->name('post.comment.destroy');
 Route::put('api/comment/{comment_id}', [CommentController::class, 'edit'])->middleware('auth')->name('post.comment.update');
 Route::post('api/comment/{comment_id}/like', [CommentController::class, 'like'])->middleware('auth')->name('post.comment.like');
 Route::delete('api/comment/{comment_id}/unlike', [CommentController::class, 'unlike'])->middleware('auth')->name('post.comment.unlike');
// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});


Route::get('/forgot-password-form', [ForgotPasswordController::class,'showForgotPasswordForm'])->middleware('guest')->name('password.form');

Route::post('/forgot-password', [ForgotPasswordController::class,'sendEmail'])->name('password.email');


Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class,'resetPassword'])->middleware('guest')->name('password.update');


// Register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});


//Profile
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'myProfile');
    Route::get('/profile/{id}', 'show')->name('profile.show');
    Route::get('/friendrequest', 'showfriendrequest');
    Route::post('/profile/{id}/send-friend-request', 'sendFriendRequest')->name('send.friend.request');
    Route::get('/profile/{id}/edit', 'editProfile')->name('profile.edit');
    Route::put('/profile/update/{id}', 'updateProfile')->name('profile.update');
    Route::delete('/profile/delete/{id}', 'ProfileController@delete')->name('profile.delete');
});



// Messages
Route::controller(MessageController::class)->group(function () {
    Route::get('/messages', 'list_chats');
    Route::get('/messages/{id}','chat');
    Route::post('/messages/{id}', 'create');
});

// Groups
Route::controller(GroupController::class)->group(function (){
    Route::get('/groups', 'showGroups');
    Route::get('/groups/{group}', 'showGroup');
    Route::get('/create-group', 'showGroupCreationForm');
    Route::post('/create-group', 'createGroup');
    Route::get('/groups/{group}/edit', 'edit');
    Route::patch('/groups/{group}', 'update');
    Route::post('/groups/{group}/add-members', 'addMembers');
    Route::delete('/groups/{group}', 'destroy');
    Route::post('/groups/{group}/kick-member', 'kickMember');
    Route::post('/groups/{group}/leave', 'leaveGroup');

});

// GroupChat
Route::controller(GroupMessageController::class)->group(function (){
    Route::get('/groups/{groupId}/chat', 'showChat');
    Route::post('/groups/{groupId}/chat', 'sendMessage');
});

//Moderator
Route::controller(ModeratorController::class)->group(function () {
    Route::get('/reports','reportList');
    Route::post('/moderator/create/{id}','create');
    Route::delete('/moderator/remove/{id}','remove'); 
    Route::post('/moderator/ban/{id}','ban');
    Route::delete('/moderator/unban/{id}','unban');
    Route::get('/teste','teste');
});


Route::get('/about', function () {
    return view('pages.about_us');
});

Route::get('/contact', function () {
    return view('pages.contact');
});

Route::get('/features', function () {
    return view('pages.mainfeatures');
});

//Friendrequest
Route::controller(FriendRequestController::class)->group(function () {
    Route::get('/friendrequests', 'index')->name('friendrequests.index');
    Route::post('/friendrequests/accept/{sender}/{receiver}', 'accept')->name('friendrequests.accept');
    Route::post('/friendrequests/reject/{sender}/{receiver}', 'reject')->name('friendrequests.reject');
});

//Friends
Route::controller(FriendController::class)->group(function () {
    Route::get('/friends', 'show')->name('friends.show');
    Route::delete('/friends/{id}/remove', 'removeFriend')->name('friends.remove');
});


//Admin
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin','showDashboard');  
    Route::get('/admin/groups','groups');
    Route::get('/admin/users','users');
    Route::get('/admin/posts','posts');  
    Route::get('/admin/moderators','moderators');  
});
