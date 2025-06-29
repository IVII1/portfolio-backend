<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarEntryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ReferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('/calendar-entries', CalendarEntryController::class)->only('index', 'show');
Route::apiResource('/references', ReferenceController::class)->only('index', 'show');
Route::apiResource('/messages', MessageController::class)->only('store');  
Route::apiResource('/categories', CategoryController::class)->only('index', 'show');


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    

    Route::apiResource('/calendar-entries', CalendarEntryController::class)->except('index', 'show');
    Route::apiResource('/references', ReferenceController::class)->except('index', 'show');
    Route::apiResource('/categories', CategoryController::class)->except('index', 'show');
    
 
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount']);
    Route::put('/messages/read-all', [MessageController::class, 'readAll']);  // Changed from PUT to PATCH
    Route::put('/messages/{id}/read', [MessageController::class, 'read']);   // Changed from PUT to PATCH
    Route::apiResource('/messages', MessageController::class)->except('store');
});