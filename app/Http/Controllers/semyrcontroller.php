<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\semyr;

class semyrcontroller extends Controller
{
    public function index()
    {
        return view('settings');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|string|max:10',
            'school_year' => 'required|string|max:20',
        ]);

        // Only store if it doesn't exist; otherwise replace/update.
        $existing = semyr::query()
            ->where('semester', $validated['semester'])
            ->first();

        if ($existing) {
            // Replace the existing record's school year (semester stays the same)
            $existing->update([
                'school_year' => $validated['school_year'],
                'semester' => $validated['semester'],
            ]);
        } else {
            semyr::create([
                'semester' => $validated['semester'],
                'school_year' => $validated['school_year'],
            ]);
        }

        return redirect()->route('settings')->with('success', $existing ? 'School Year updated successfully.' : 'School Year saved successfully.');
    }

    
    public function schoolYearSettings()
    {
        $semyr = semyr::all();
        return view('partials.school-year-settings', compact('semyr'));
    
    }

}
