<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;



use Illuminate\View\View;

use App\Models\Admin;


class LoginController extends Controller
{

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        } else {
            return view('auth.login');
        }
    }

    
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        
        $email = $credentials['email'];
        $exist = Admin::where('email','=',$email)->exists();

        if ($exist) {
            if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {            
                $request->session()->regenerate();
                return redirect()->intended('/admin')->with('success','Logged in.');
            }
            else return back()->withErrors([
                'email' => 'The provided credentials do not match our records of admins.',
            ])->onlyInput('email');
        }
        else {
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $request->session()->regenerate();
                if(Auth::user()->isBanned()){
                    Auth::logout();
                    return redirect('/login')->with('banned', 'You have been banned.');
                }
                return redirect()->intended('/dashboard');
            }
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()){
            Auth::guard('admin')->logout();
        }
        else {
            Auth::logout();
            return redirect()->route('login')
                ->withSuccess('You have logged out successfully!');
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    } 
}