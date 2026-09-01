<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programs;
use App\Models\User;

class ProgramController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $userId = session('user_id');
        $user = User::find($userId);

        if (in_array((int) session('user_role'), [2, 3], true) && $user?->college_id) {
            $programs = Programs::where('college_id', $user->college_id)->get();
        } else {
            $programs = Programs::all();
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
            'program_abbr' => 'required|string|max:100|unique:programs,program_abbr',
            'program_name' => 'required|string|max:150|unique:programs,program_name',
            'description' => 'nullable|string|max:255',
            'college_id' => 'required|integer|exists:college,id',
        ]);

        $collegeId = (int) $request->input('college_id');
        if (in_array($role, [2, 3], true)) {
            $collegeId = (int) ($user?->college_id ?? 0);
            if (!$collegeId) {
                abort(403, 'No assigned college for your account.');
            }
        }

        Programs::create([
            'college_id' => $collegeId,
            'program_abbr' => $request->program_abbr,
            'program_name' => $request->program_name,
            'description' => $request->description ?? '',
        ]);

        return back()->with('success', 'Program saved successfully!');
    }

    public function edit($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $program = Programs::findOrFail($id);
        return view('program.edit', compact('program'));
    }

    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $program = Programs::findOrFail($id);

        $request->validate([
            'program_abbr' => 'required|string|max:100|unique:programs,program_abbr,' . $program->id,
            'program_name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        $program->update([
            'program_abbr' => $request->program_abbr,
            'program_name' => $request->program_name,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('programs')->with('success', 'Program updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $program = Programs::findOrFail($id);
        $program->delete();

        return redirect()->route('programs')->with('success', 'Program deleted successfully!');
    }
}

