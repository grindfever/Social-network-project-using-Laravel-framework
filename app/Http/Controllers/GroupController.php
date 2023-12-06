<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    

    public function createGroup(Request $request)
    {
        // Validate input

        $members = $request->input('members');

        // Ensure at least three members are selected
        if (count($members) < 2) {
            return redirect()->back()->with('error', 'A group must have at least three members including you.');
        }

        // Create the group

        $group = new Group();
        $group->owner = auth()->user()->id;
        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->save();

        // Create memberships for each selected member
        $members = $request->input('members');
        foreach ($members as $memberId) {
        // Create a membership record
            DB::table('memberships')->insert([
                'possible_member' => auth()->user()->id,
                'group_id' => $group->id,
                'accepted' => true, // Assuming memberships are accepted by default
                'requested' => false,
                'accept_date' => now(),
                'req_or_inv_date' => now(),
                'member' => $memberId,
            ]);
        }

        // Redirect to the group list
        return redirect('/groups');
    }

 
    public function showGroupCreationForm()
    {
    $users = User::all(); 
    return view('pages.create_group', compact('users'));
    }

    
    public function showGroups()
    {
        if (!Auth::check()) {
            // Not logged in, redirect to login.
            return redirect('/login');

        } else {
    $user = auth()->user();
    $groups = $user->groups; 

    return view('pages.groups', ['groups' => $groups]);
    }
    }

    public function showGroup(Group $group)
    {   
    return view('pages.groupshow', compact('group'));
    }
}
