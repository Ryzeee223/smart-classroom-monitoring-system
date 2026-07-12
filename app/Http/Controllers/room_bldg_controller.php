<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\room;
use App\Models\bldg;
use App\Models\college;
class room_bldg_controller extends Controller
{
   


    public function storeRoom(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'room_name' => 'required|string|max:30',
            'room_type' => 'required|string|max:30',
            'bldg_id' => 'nullable|exists:bldg,id',
        ]);

        room::create([
            'room_name' => $request->input('room_name'),
            'room_type' => $request->input('room_type'),
            'bldg_id'   => $request->input('bldg_id'),
        ]);

        return redirect()->back()->with('success', 'Room created successfully.');
    }

    public function showrm(Request $request)
    {
        $rooms = room::with('room_name')->get();
        return view('rooms', compact('rooms'));
    }


    public function storeBldg(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

$request->validate([
    'bldg_name' => 'required|string|max:50',
    'bldg_abbr' => 'required|string|max:10',
    'college_id' => 'nullable|exists:college,id', 
]);

bldg::create([
    'bldg_name' => $request->input('bldg_name'),
    'bldg_abbr' => $request->input('bldg_abbr'),
    'college_id' => $request->input('college_id'), 
]);

        return redirect()->back()->with('success', 'Building created successfully.');
    }
    

    public function show()
    {
        $bldgModel = bldg::with('bldg_name')->first();
        $colleges = college::all();

        return view('rooms', compact('bldgModel', 'colleges'));
    }


}
