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
        
        // Handle form submission logic here
        $request->validate([
            'semester' => 'required|string|max:10',
            'school_year' => 'required|string|max:20',
        ]);
        semyr::create($request->all());

        return redirect()->route('settings')->with('success', 'Semester and School Year created successfully.');
    }
    
    public function schoolYearSettings()
    {
        $semyr = semyr::all();
        return view('partials.school-year-settings', compact('semyr'));
    
    }

}
