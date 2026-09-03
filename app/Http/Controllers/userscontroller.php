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
        $courses = College::query()->select(['id','college_name','abbreviation','description'])->where('id', '!=', 1)->get();

        return view('users.edit', compact('user', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'college_code' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'college' => $request->college_code,

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
