<?php

use App\Http\Controllers\CalendarEntryController;
use App\Http\Controllers\ReferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/calendar-entries', CalendarEntryController::class);
Route::apiResource('/references', ReferenceController::class);
