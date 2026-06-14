<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\college;

use App\Models\Course;
use Illuminate\Http\Request;

class userscontroller extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Fill dropdown from college table
        $courses = college::query()->select(['id','college_name','abbreviation','description'])->get();

        return view('users.edit', compact('user', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'college' => 'nullable|string|max:255',
        ]);


        $user = User::findOrFail($id);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'college' => $request->college,

        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }
}

