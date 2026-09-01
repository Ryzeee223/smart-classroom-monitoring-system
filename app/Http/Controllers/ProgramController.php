<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\programs;
use App\Models\User;


class ProgramController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        // Dean/Assistant Dean should only see programs within their own college
        $userId = session('user_id');
        $user = User::find($userId);

        if (in_array((int) session('user_role'), [2, 3], true) && $user?->college_id) {
            $programs = programs::where('college_id', $user->college_id)->get();
        } else {
            $programs = programs::all();
        }

        return view('programs', compact('programs'));


    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $userId = session('user_id');
        $user = User::find($userId);

        $role = (int) session('user_role');
        if (!in_array($role, [1, 2, 3], true)) {
            abort(403);
        }

        $request->validate([
            'program_abbr' => 'required|string|max:100|unique:programs,Program_abbr',
            'program_name' => 'required|string|max:150|unique:programs,Program_name',
            'description' => 'nullable|string|max:255',
            'college_id' => 'required|integer|exists:college,id',
        ]);


        // Prevent forging: dean/assistant dean can only create inside their assigned college
        $collegeId = (int) $request->input('college_id');
        if (in_array($role, [2, 3], true)) {
            $collegeId = (int) ($user?->college_id ?? 0);
            if (!$collegeId) {
                abort(403, 'No assigned college for your account.');
            }
        }

        programs::create([
            'college_id' => $collegeId,
            'Program_abbr' => $request->program_abbr,
            'Program_name' => $request->program_name,
            'description' => $request->description ?? '',            
        ]);

        return back()->with('success', 'Program saved successfully!');
    }



    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $Program = programs::findOrFail($id);
        return view('program.edit', compact('Program'));
    }

    


    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $Program = programs::findOrFail($id);

        $request->validate([
            'program_abbr' => 'required|string|max:100|unique:programs,program_abbr,' . $Program->id,
            'program_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        $Program->update([
            'program_abbr' => $request->program_abbr,
            'program_name' => $request->program_name,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('program')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $Program = programs::findOrFail($id);
        $Program->delete();

        return redirect()->route('program')->with('success', 'Program deleted successfully!');
    }
}

