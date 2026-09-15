<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/rfid-scan', [ApiController::class, 'handleScan']);
Route::post('/attendance-scan', [ApiController::class, 'handleAttendanceScan']);
Route::get('/check-latest-assignment', [ApiController::class, 'checkLatestAssignmentScan']);
Route::get('/check-latest-attendance', [ApiController::class, 'checkLatestAttendanceScan']);

