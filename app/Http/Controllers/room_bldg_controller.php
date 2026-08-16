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
            'room_name' => 'required|string|max:170',
            'room_type' => 'required|string|max:40',
            'bldg_id'   => 'nullable|exists:building,id',

        ]);

        room::create([
            'room_name' => $request->input('room_name'),
            'room_type' => $request->input('room_type'),
            'bldg_id'   => $request->input('bldg_id') ?? null,
        ]);

        return redirect()->back()->with('success', 'Room created successfully.');
    }

    public function explode($id)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

        $building = bldg::find($id);
        if (!$building) {
            return redirect()->back()->with('error', 'building not found');
        }

        $building->delete();
        return redirect()->back()->with('success', 'Building deleted');
    }

    public function destroy($id)
    {
        if (!session ('logged_in')){
            return redirect('/');
        }
        $roomModel = room::find($id);
        if (!$roomModel) {
            return redirect()->back()->with('error', 'room not found');
        }

        $roomModel->delete();
        return redirect()->back()->with('success', 'room deleted');

    }


    public function storeBldg(Request $request)
    {
        if (!session('logged_in')) {
            return redirect('/');
        }

       
        $request->validate([
            'bldg_name' => 'required|string|max:150',
            'bldg_abbr' => 'required|string|max:100|unique:building,bldg_abbr',
            'college_id' => 'nullable|exists:college,id',
        ]);

        bldg::create([
            'bldg_name' => $request->input('bldg_name'),
            'bldg_abbr' => $request->input('bldg_abbr'),
            'college_id'=> $request->input('college_id') ?? null,
        ]);
        return redirect()->route('rooms.index')->with('success', 'Building created successfully.');
    }

    public function showrm(Request $request)
    {
        $roomnm = room::with('room_name')->get();
        return view('rooms', compact('rooms'));
    }

    public function show()
    {
        $bldgModel = bldg::all();
        $colleges = college::all();

        // Existing rooms to display in the UI
        $rooms = room::query()
            ->with('building')
            ->get();

        return view('rooms', compact('bldgModel', 'colleges', 'rooms'));
    }

    public function display()
    {
        $buildings = bldg::all();

        // Load rooms with their related buildings so the dashboard can render by clicking a building
        $rooms = room::query()
            ->with('building')
            ->get();

        return view('dashboard', compact('buildings', 'rooms'));
    }

}
