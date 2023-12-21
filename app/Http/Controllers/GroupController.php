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

        $members = $request->input('members');

        if (count($members) < 2) {
            return redirect()->back()->with('error', 'A group must have at least three members including you.');
        }


        $group = new Group();
        $group->owner = auth()->user()->id;
        $group->name = $request->input('name');
        $group->description = $request->input('description');
        $group->save();

        DB::table('memberships')->insert([
                'possible_member' => auth()->user()->id,
                'group_id' => $group->id,
                'accepted' => true,
                'requested' => false,
                'accept_date' => now(),
                'req_or_inv_date' => now(),
                'member' => auth()->user()->id,
            ]);

        $members = $request->input('members');
        foreach ($members as $memberId) {
            DB::table('memberships')->insert([
                'possible_member' => $memberId,
                'group_id' => $group->id,
                'accepted' => true,
                'requested' => false,
                'accept_date' => now(),
                'req_or_inv_date' => now(),
                'member' => auth()->user()->id,
            ]);
        }

        return redirect('/groups');
    }

 
    public function showGroupCreationForm()
    {
    $users = User::where('id', '!=', auth()->user()->id)->get();

    return view('pages.create_group', compact('users'));
    }

    
    public function showGroups()
    {
        if (Auth::guard('admin')->check()){
            return redirect('/admin/groups');
        }
        if (!Auth::check()) {
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


    public function edit(Group $group)
    {

    $existingMembers = DB::table('memberships')->where('group_id', $group->id)->pluck('possible_member');
    $users = DB::table('users')->whereNotIn('id', $existingMembers)->get();

    if ($users->isEmpty()) {
        $noUsersLeftMessage = "Your group is so popular! There is no one left to add!";
    } else {
        $noUsersLeftMessage = null;
    }

    return view('pages.groupedit', compact('group', 'users', 'noUsersLeftMessage'));
    }


    public function update(Request $request, Group $group)
    {

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
    $request->validate([
        'members' => 'array',
    ]);

    $members = $request->input('members');
    foreach ($members as $memberId) {
        DB::table('memberships')->insert([
            'possible_member' => $memberId,
            'group_id' => $group->id,
            'accepted' => true,
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
    
    DB::table('memberships')->where('group_id', $group->id)->delete();
    DB::table('groups')->where('id', $group->id)->delete();

    return redirect("/groups")->with('success', 'Group deleted successfully.');
    }

    public function kickMember(Request $request, Group $group)
    {
    // Check if the user is the owner of the group
    //$this->authorize('update', $group);

    // Validate the request
    $request->validate([
        'member_id' => 'required|exists:users,id',
    ]);

    // Delete the membership record
    DB::table('memberships')
        ->where('group_id', $group->id)
        ->where('possible_member', $request->input('member_id'))
        ->delete();

    return redirect("/groups/{$group->id}")->with('success', 'Member kicked successfully.');
    }

    public function leaveGroup(Group $group)
    {
    // Check if the user is a member of the group
    $membership = DB::table('memberships')
        ->where('group_id', $group->id)
        ->where('possible_member', auth()->user()->id)
        ->first();

    if ($membership) {
        // Remove the user from the group
        DB::table('memberships')
            ->where('group_id', $group->id)
            ->where('possible_member', auth()->user()->id)
            ->delete();

        return redirect("/groups")->with('success', 'Left the group successfully.');
    }

    return redirect("/groups")->with('error', 'You are not a member of this group.');
    }   


}
