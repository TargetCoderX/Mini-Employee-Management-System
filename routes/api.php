<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/departments', [DepartmentController::class, 'listDepartments']);
    Route::get('/employees', [EmployeeController::class, 'listEmployees']);
    Route::middleware([EnsureAdmin::class])->group(function () {
        Route::post('/departments', [DepartmentController::class, 'createDepartment']);
        Route::post('/employees', [EmployeeController::class, 'createEmployee']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'deleteEmployee']);
    });
});
