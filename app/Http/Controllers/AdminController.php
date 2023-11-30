<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function showDashboard(){
        if (!Auth::guard('admin')->check()){
            return redirect('/login');
        }
        else return view('admin.dashboard');
    }

}
