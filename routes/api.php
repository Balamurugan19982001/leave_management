<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/employees', [EmployeeController::class, 'store']);
Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees/{id}/leaves', [LeaveController::class, 'store']);
Route::get('/employees/{id}/leaves', [LeaveController::class, 'index']);
Route::patch('/leaves/{id}', [LeaveController::class, 'update'])->middleware('role:manager');

