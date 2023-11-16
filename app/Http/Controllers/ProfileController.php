<?php
 
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\View\View;


class ProfileController extends Controller {
    //Display Profile page
    public function showProfilePage(): View {
        return view('pages.profile');
    }
}


