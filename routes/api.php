<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//auth
Route::prefix('v1')->group(function () {
Route::post('/register', [App\Http\Controllers\AuthController::class, 'userRegistration']);
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
});


Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
Route::post('/request/create-user', [App\Http\Controllers\ChangeRequestController::class, 'store']);
Route::post('/request/update-user', [App\Http\Controllers\ChangeRequestController::class, 'requestToUpdateUser']);
Route::post('/request/delete-user', [App\Http\Controllers\ChangeRequestController::class, 'requestToDeleteUser']);
});

Route::prefix('v1')->middleware(['auth:sanctum','IsSuperAdmin'])->group(function () {
    Route::post('/approve-request/{requestId}', [App\Http\Controllers\ChangeRequestController::class, 'approveRequest']);
    Route::post('/decline-request/{requestId}', [App\Http\Controllers\ChangeRequestController::class, 'declineRequest']);
    Route::get('/change-request', [App\Http\Controllers\ChangeRequestController::class, 'index']);
    Route::get('/change-request/{requestId}', [App\Http\Controllers\ChangeRequestController::class, 'show']);

});
