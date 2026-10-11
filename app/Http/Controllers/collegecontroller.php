<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\college;
use Illuminate\Http\Request;

class collegecontroller extends Controller
{

    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $college = college::all();
        return view('college', compact('college'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $validated = $request->validate([
            'college_name' => 'required|string|max:128|unique:college,college_name',
            'abbreviation' => 'required|string|max:30|unique:college,abbreviation',
            'description' => 'nullable|string|max:255',
        ],[], 
        [
            'college_name' => 'College Name',
            'abbreviation' => 'College Abbreviation',
            'description' => 'Description',
        ]);

        college::create([
            'college_name' => $validated['college_name'],
            'abbreviation' => $validated['abbreviation'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'College saved successfully!');
    }

    public function edit(College $college)
    {
        if (!session('logged_in')) {
            return redirect('/');  
        }

        // Reuse the same page; form can be wired later.
        return view('college', ['college' =>college::all(), 'editing' => $college]);
    }

    public function update(Request $request, College $college)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $validated = $request->validate([
            'college_name' => 'required|string|max:128|unique:college,college_name,' . $college->id,
            'abbreviation' => 'required|string|max:30|unique:college,abbreviation,' . $college->id,
            'description' => 'nullable|string|max:255',
        ], [], [
            'college_name' => 'College Name',
            'abbreviation' => 'College Abbreviation',
            'description' => 'Description',
        ]);

        $college->update([
            'college_name' => $validated['college_name'],
            'abbreviation' => $validated['abbreviation'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'College updated successfully!');
    }

    public function destroy(College $college)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $college->delete();

        return back()->with('success', 'College deleted successfully!');
    }
}

