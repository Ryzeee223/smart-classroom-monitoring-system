<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log; // Added to help you debug locally!

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

    
   
}