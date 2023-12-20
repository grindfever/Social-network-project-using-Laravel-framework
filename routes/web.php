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


/*
 *    API
 */ 
 Route::post('/dashboard/search', [DashBoardController::class, 'search']);
 Route::post('/dashboard/create', [DashBoardController::class, 'create']);
 Route::delete('api/post/{post_id}', [DashBoardController::class, 'delete']);
 Route::put('api/post/{post_id}', [DashBoardController::class, 'update']);
 Route::post('api/post/{post_id}/like', [DashBoardController::class, 'like'])->middleware('auth')->name('post.like');
 Route::delete('api/post/{post_id}/unlike', [DashBoardController::class, 'unlike'])->middleware('auth')->name('post.unlike');

 //Route::post('/file/upload', [FileController::class, 'upload']);


 // Commentspassword.request
 Route::post('api/post/{post_id}/comment', [CommentController::class, 'store'])->middleware('auth')->name('post.comment.store');
 Route::delete('api/comment/{comment_id}', [CommentController::class, 'delete'])->middleware('auth')->name('post.comment.destroy');
 Route::put('api/comment/{comment_id}', [CommentController::class, 'edit'])->middleware('auth')->name('post.comment.update');

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
    Route::get('/profile/{id}', 'show');
    Route::get('/friendrequest', 'showfriendrequest');
    
    // Correct the route for sending friend requests
    Route::post('/profile/{id}/send-friend-request', 'sendFriendRequest')->name('send.friend.request');
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
});


Route::get('/about', function () {
    return view('pages.about_us');
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
});
