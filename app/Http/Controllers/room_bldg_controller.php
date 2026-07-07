<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\room;
use App\Models\bldg;
class room_bldg_controller extends Controller
{
    public function show()
    {
        if (!session('logged_in')) {
            return redirect('/');
        }
   
    }

    public function storeRoom(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'room_name' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'bldg_id' => 'required|exists:bldg,id',
        ]);

        room::create([
            'room_name' => $request->input('room_name'),
            'room_type' => $request->input('room_type'),
            'bldg_id' => $request->input('bldg_id'),
        ]);

        return redirect()->back()->with('success', 'Room created successfully.');
    }

    public function storeBldg(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $request->validate([
            'bldg_name' => 'required|string|max:255',
            'bldg_abbr' => 'required|string|max:255',
        ]);

        bldg::create([
            'bldg_name' => $request->input('bldg_name'),
            'bldg_abbr' => $request->input('bldg_abbr'),
        ]);

        return redirect()->back()->with('success', 'Building created successfully.');
    }
}
