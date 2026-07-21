<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Cache;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/rfid-scan', [ApiController::class, 'handleScan']);
Route::get('/check-latest-scan', function () {
    // Pull the latest scan from cache (returns null if no card was scanned recently)
    $latestScan = Cache::get('latest_rfid_scan');
    
    return response()->json([
        'uid' => $latestScan
    ]);
});

