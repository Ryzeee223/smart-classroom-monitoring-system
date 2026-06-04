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
            'college_name' => 'required|string|max:50|unique:college,college_name',
            'college_code' => 'required|string|max:10|unique:college,abbreviation',
            'description' => 'nullable|string',
        ]);

        college::create([
            'college_name' => $validated['college_name'],
            'abbreviation' => $validated['college_code'],
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('success', 'College saved successfully!');

    }
}


