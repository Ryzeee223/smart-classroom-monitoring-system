<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // Added to help you debug locally!
use App\Models\Schedule;
use App\Models\User;
use App\Models\report;

class ApiController extends Controller
{
    public function handleScan(Request $request)
    {
        // 1. Validate incoming UID data from the ESP8266
        $request->validate([
            'uid' => 'required|string'
        ]);

        $scannedUid = strtoupper($request->input('uid')); // Forces uppercase consistency

        // 2. Write the scan to the Cache for 2 minutes (120 seconds)
        Cache::put('latest_rfid_scan', $scannedUid, 120);

        // 3. Spy on the incoming request in storage/logs/laravel.log
        Log::info("RFID Hardware Scan Received: {$scannedUid}");

        // 4. Return a success response to the ESP8266
        return response()->json([
            'status' => 'success',
            'message' => 'RFID processed successfully',
            'uid' => $scannedUid
        ], 200);
    }

    public function handleAttendanceScan(Request $request)
    {
        $validated = $request->validate([
            'uid' => ['required', 'string', 'max:50'],
        ]);

        $scannedUid = strtoupper(trim($validated['uid']));
        $now = Carbon::now();
        $today = $now->translatedFormat('D');

        $schedules = Schedule::with(['user', 'course', 'room'])
            ->where('day', $today)
            ->whereTime('start_time', '<=', $now->format('H:i:s'))
            ->whereTime('end_time', '>=', $now->format('H:i:s'))
            ->get();

        $schedule = $schedules->first(function ($item) use ($scannedUid) {
            return strtoupper(trim((string) $item->user?->RFID_code)) === $scannedUid;
        });

        if (!$schedule || !$schedule->user) {
            Log::warning("Rejected RFID attendance scan: {$scannedUid}");

            return response()->json([
                'status' => 'denied',
                'message' => 'RFID does not match a faculty member scheduled for this room and time.',
            ], 403);
        }

        $user = $schedule->user;
        $start = Carbon::today()->setTimeFromTimeString($schedule->start_time);
        $attendance = report::where('user_id', $user->id)
            ->where('schedule_id', $schedule->id)
            ->whereDate('attendance_date', $now->toDateString())
            ->first();

        if (!$attendance) {
            $attendance = report::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'attendance_date' => $now,
            ]);
        }

        $accountStatus = strtolower(str_replace(['-', '_'], ' ', trim((string) $user->acc_status)));
        $isOnLeave = in_array($accountStatus, ['sick leave', 'on leave', 'leave', 'sick'], true);

        if ($isOnLeave) {
            $attendance->update(['status_in' => 'on_leave']);
            $user->update(['acc_status' => 'On Leave']);
            $status = 'on_leave';
        } else {
            $status = $now->greaterThan($start->copy()->addMinutes(5)) ? 'late' : 'attended';
            $attendance->update([
                'time_in' => $attendance->time_in ?? $now->format('H:i:s'),
                'status_in' => $attendance->status_in === 'attended' ? 'attended' : $status,
            ]);
            $user->update(['acc_status' => ucfirst($status)]);
            $status = $attendance->status_in;
        }

        Cache::put('latest_rfid_scan', $scannedUid, 120);
        Log::info("Accepted RFID attendance scan: {$scannedUid}, status: {$status}");

        return response()->json([
            'status' => 'accepted',
            'attendance_status' => $status,
            'message' => ucfirst(str_replace('_', ' ', $status)),
            'uid' => $scannedUid,
        ]);
    }

    
   
}