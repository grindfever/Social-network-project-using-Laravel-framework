<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Mail\MyEmail;
use App\Http\Controllers\MailController;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function showForgotPasswordForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        } else {
            return view('auth.forgot-password');
        }
    }

    public function sendEmail(Request $request){
        $name = $request->name;
        $email = $request->email;
       
        Mail::to($email)->send(new MyEmail($name));
        return redirect()->route('home')->with('status', __('Email has been sent'));
    }

     public function resetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        // Find the user by email
        $user = DB::table('users')->where('email', $request->email)->first();
    
        if (!$user) {
            return back()->withErrors(['email' => [__('User does not exist')]]);
        }
    
        // Update the user's password
        DB::table('users')
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);
    
        return redirect()->route('home')->with('status', __('Password has been reset'));
    }
     
}
