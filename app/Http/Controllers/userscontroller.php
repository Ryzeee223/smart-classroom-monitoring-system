<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Courses;
use Illuminate\Http\Request;

class userscontroller extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $courses = Courses::query()->from('Colleges')->get();

        return view('users.edit', compact('user', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'college_code' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'course' => $request->college_code, // store BSIT/BSCS (Colleges.course_code)
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }
}

