<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\college;
use Illuminate\Http\Request;

class userscontroller extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Fill dropdown from college table (hide the first-created college: id=1)
        $colleges = college::query()->select(['id', 'college_name', 'abbreviation'])->where('id', '!=', 1)->get();

        return view('users.edit', compact('user', 'colleges'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'role' => 'required|integer|in:2,3,4,5',
            'acc_status' => 'nullable|string',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'role' => $request->role,
            'acc_status' => $request->acc_status,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }
     public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        User::findOrFail($id)->delete();
    
        return back()->with('success', 'User deleted successfully!');
    }
}
