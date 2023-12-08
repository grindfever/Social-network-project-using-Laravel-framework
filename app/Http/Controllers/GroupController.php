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

        DB::table('memberships')->insert([
                'possible_member' => auth()->user()->id,
                'group_id' => $group->id,
                'accepted' => true, // Assuming memberships are accepted by default
                'requested' => false,
                'accept_date' => now(),
                'req_or_inv_date' => now(),
                'member' => auth()->user()->id,
            ]);

        // Create memberships for each selected member
        $members = $request->input('members');
        foreach ($members as $memberId) {
        // Create a membership record
            DB::table('memberships')->insert([
                'possible_member' => $memberId,
                'group_id' => $group->id,
                'accepted' => true, // Assuming memberships are accepted by default
                'requested' => false,
                'accept_date' => now(),
                'req_or_inv_date' => now(),
                'member' => auth()->user()->id,
            ]);
        }

        // Redirect to the group list
        return redirect('/groups');
    }

 
    public function showGroupCreationForm()
    {
    // Get all users except the authenticated user
    $users = User::where('id', '!=', auth()->user()->id)->get();

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

    // GroupController.php

    public function edit(Group $group)
    {
    /* // Check if the logged-in user is the owner of the group
    if (auth()->user()->id !== $group->owner) {
        abort(403, 'This action is unauthorized.');
    } */

    // Retrieve the list of users who are not members of the group
    $existingMembers = DB::table('memberships')->where('group_id', $group->id)->pluck('possible_member');
    $users = DB::table('users')->whereNotIn('id', $existingMembers)->get();

    // Check if there are no users left to add
    if ($users->isEmpty()) {
        $noUsersLeftMessage = "Your group is so popular! There is no one left to add!";
    } else {
        $noUsersLeftMessage = null;
    }

    // Rest of the method
    return view('pages.groupedit', compact('group', 'users', 'noUsersLeftMessage'));
    }


    public function update(Request $request, Group $group)
    {
    // Check if the user is the owner of the group
    // $this->authorize('update', $group);

    // Validate and update group details (name, description)
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:1024',
    ]);

    $group->update([
        'name' => $request->input('name'),
        'description' => $request->input('description'),
    ]);

    return redirect("/groups/{$group->id}")->with('success', 'Group details updated successfully.');
    }

    public function addMembers(Request $request, Group $group)
    {
    // Check if the user is the owner of the group
    //$this->authorize('update', $group);

    // Validate and attach selected members to the group
    $request->validate([
        'members' => 'array',
    ]);

    $members = $request->input('members');
    foreach ($members as $memberId) {
        // Create a membership record
        DB::table('memberships')->insert([
            'possible_member' => $memberId,
            'group_id' => $group->id,
            'accepted' => true, // Assuming memberships are accepted by default
            'requested' => false,
            'accept_date' => now(),
            'req_or_inv_date' => now(),
            'member' => auth()->user()->id,
        ]);
    }

    return redirect("/groups/{$group->id}/edit")->with('success', 'Members added successfully.');
    }

    public function destroy(Group $group)
    {
    // Check if the user is the owner of the group
    //$this->authorize('delete', $group);

    // Delete the group and associated memberships
    DB::table('memberships')->where('group_id', $group->id)->delete();
    DB::table('groups')->where('id', $group->id)->delete();

    return redirect("/groups")->with('success', 'Group deleted successfully.');
    }

}
