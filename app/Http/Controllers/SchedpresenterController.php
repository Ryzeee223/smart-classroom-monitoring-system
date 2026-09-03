<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Schedule;
use App\Models\Room; // Standard PascalCase

class SchedpresenterController extends Controller
{
    public function showSchedules()
    {
        $now = Carbon::now();
        $today = $now->format('l'); // Full day name (e.g. "Monday")
        $currentTime = $now->toTimeString();  

        // 1. Fetch all schedules
        $schedules = Schedule::with(['user', 'room', 'course'])->get();

        // 2. Fetch ongoing classes for the current day and time
        $ongoingClass = Schedule::where('day', $today)
            ->whereTime('start_time', '<=', $currentTime)
            ->whereTime('end_time', '>=', $currentTime)
            ->get();

        // 3. Return view ONCE with all data
        return view('dashboard', compact('schedules', 'ongoingClass'));
    }
    
    public function RtSchedchecker(Request $request)
    {
        $now = Carbon::now();
        $today = $now->format('l');
        $currentTime = $now->toTimeString();  
        $rfid = strtoupper(trim((string) $request->input('rfid')));

        if (!$rfid) {
            return response()->json(['message' => 'RFID is required.'], 400);
        }

        // Search schedule matched with user's RFID code for today
        $schedule = Schedule::whereHas('user', function ($query) use ($rfid) {
                $query->whereRaw('UPPER(TRIM(RFID_code)) = ?', [$rfid]);
            })
            ->where('day', $today)
            ->first();

        if (!$schedule) {
            return response()->json(['message' => 'Invalid RFID scan or no schedule found for today.'], 404);
        }

        // Compare current time with class schedule
        if ($currentTime >= $schedule->start_time && $currentTime <= $schedule->end_time) {
            $startTime = Carbon::parse($schedule->start_time);
            
            // Late grace period: 5 minutes after start time
            if ($now->greaterThan($startTime->copy()->addMinutes(5))) {
                return response()->json(['message' => 'Late. Attendance logged.', 'status' => 'late']);
            }

            return response()->json(['message' => 'Present.', 'status' => 'attended']);
        } 
        
        if ($currentTime < $schedule->start_time) {
            return response()->json(['message' => 'Early scan. Class has not started yet.'], 422);
        } 

        return response()->json(['message' => 'Faculty is absent, schedule window has passed.'], 422);
    }
}