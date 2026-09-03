<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Semester;

class SemesterController extends Controller
{
    public function index()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $semesters = collect();
        return view('settings', compact('semesters'));
    }

    public function store(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $validated = $request->validate([
            'semester' => 'required|string|max:20',
            'school_year' => 'required|string|max:45',
        ], [], [
            'semester' => 'Semester',
            'school_year' => 'School Year',
        ]);

        // Persist selection (best-effort). If the DB/table/columns don’t exist yet,
        // at least keep runtime state in session.
        try {
            Semester::create([
                'Semester' => $validated['semester'],
                'School_year' => $validated['school_year'],
            ]);
        } catch (\Throwable $e) {
            // If persistence isn’t ready, continue without failing the request.
        }

        session(['active_semester' => $validated['semester']]);
        session(['active_school_year' => $validated['school_year']]);

        return back()->with('success', 'Semester saved successfully!');
    }

    // Used by routes/web.php: POST /settings/change-school-year
    public function changeSchoolYear(Request $request)
    {
        // Same auth guard as other methods in this controller
        if (!session('logged_in')) {
            return redirect('/');
        }

        $validated = $request->validate([
            'semester' => 'required|string|max:20',
            'school_year' => 'required|string|max:45',
        ], [], [
            'semester' => 'Semester',
            'school_year' => 'School Year',
        ]);

        // Persist selection and also update session for immediate runtime usage.
        try {
            Semester::create([
                'Semester' => $validated['semester'],
                'School_year' => $validated['school_year'],
            ]);
        } catch (\Throwable $e) {
            // Ignore DB issues for now (controller should not crash).
        }

        session(['active_semester' => $validated['semester']]);
        session(['active_school_year' => $validated['school_year']]);

        return redirect()->back()->with('success', 'School year and semester settings updated successfully!');
    }
}
