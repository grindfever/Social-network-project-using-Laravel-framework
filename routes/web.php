<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\MessageController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\ProfileController;


use App\Http\Controllers\DashBoardController;

use App\Http\Controllers\SearchController;
use App\Http\Controllers\FriendRequestController;
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
Route::redirect('/', '/dashboard');

// Dashboard
Route::controller(DashBoardController::class)->group(function () {
    Route::get('/dashboard','list')->name('DashBoard');
    Route::get('/post/{id}','show');
});


/*
 *    API
 */ 

 Route::post('/dashboard', [DashBoardController::class, 'create']);
 Route::delete('api/post/{post_id}', [DashBoardController::class, 'delete']);
 Route::put('api/post/{post_id}', [DashBoardController::class, 'update']);
 

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

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


//Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search','search');
});

//Friendrequest
Route::controller(FriendRequestController::class)->group(function () {
    Route::get('/friendrequests', 'index')->name('friendrequests.index');
    Route::post('/friendrequests/accept/{id}', 'accept')->name('friendrequests.accept');
    Route::post('/friendrequests/reject/{id}', 'reject')->name('friendrequests.reject');
});
