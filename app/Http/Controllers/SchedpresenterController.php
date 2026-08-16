<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Schedule;
use App\Models\room;



class SchedpresenterController extends Controller
{
    public function showSchedules()
    {
        $currentDate = \Carbon\Carbon::now()->toDateString();
        $currentTime = \Carbon\Carbon::now()->toTimeString();  

        $schedules = Schedule::all();
        return view('dashboard', 'myschedule', compact('schedules'));
        
        // declare the schedules for the day base from the carbon date and time
        $ongoingClass = Schedule::whereDate('date', $currentDate)
            ->whereTime('start_time', '<=', $currentTime)
            ->whereTime('end_time', '>=', $currentTime)
            ->get();
    }
    
    // scan detector 
    public function RtSchedchecker()
    {
    //use carbon for real-time checking of date and time for attendance
        $currentDate = \Carbon\Carbon::now()->toDateString();
        $currentTime = \Carbon\Carbon::now()->toTimeString();  


    // declaration of objects
        $schedules = Schedule::all();
        $rooms = room::all();
        return view('dashboard', compact('schedules', 'rooms'));

    // validate RFID scan from the user and check if the scanned RFID matches any schedule in the database
        $rfid = request('rfid');
        $schedule = Schedule::where('rfid', $rfid)
            ->whereDate('date', $currentDate)
            ->whereTime('start_time', '<=', $currentTime)
            ->whereTime('end_time', '>=', $currentTime)
            ->first();

        if ($schedule) {
            return response()->json(['message' => 'Present.']);
        } 
        elseif ($schedule && $currentTime > $schedule->end_time) {
            return response()->json(['message' => 'late.']);
        }
        elseif ($schedule && $currentTime < $schedule->start_time) {
            return response()->json(['message' => 'Early']);
        }
        elseif ($schedule && $currentTime > $schedule->end_time) {
            return response()->json(['message' => 'Faculty is absent, room is vacant.']);
        }
        else 
        {
            return response()->json(['message' => 'Invalid RFID scan or outside schedule time.'], 400);
        }
         
    }
}
