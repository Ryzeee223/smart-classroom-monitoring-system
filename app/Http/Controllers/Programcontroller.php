<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Programs;


class ProgramController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $Programs = Programs::all();

        // Needed by resources/views/programs.blade.php
        $college = \App\Models\college::all();

        return view('programs', compact('Programs', 'college'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'Program_id' => 'required|string|max:255|unique:programs,program_abbr',
            'Program_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Programs::create([
            'college_id' => $request->college_abbr,
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

        $Program = Programs::findOrFail($id);
        return view('program.edit', compact('Program'));
    }


    public function update(Request $request, $id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $Program = Programs::findOrFail($id);

        $request->validate([
            'program_abbr' => 'required|string|max:255|unique:programs,program_abbr,' . $Program->id,
            'program_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $Program->update([
            'program_abbr' => $request->program_abbr,
            'program_name' => $request->program_name,
            'description' => $request->description ?? '',
        ]);

        return redirect()->route('programs')->with('success', 'Course updated successfully!');
    }

    public function destroy($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $Program = Programs::findOrFail($id);
        $Program->delete();

        return redirect()->route('programs')->with('success', 'Program deleted successfully!');
    }
}

