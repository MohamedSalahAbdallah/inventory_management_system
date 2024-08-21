<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('roles', RoleController::class);

Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login']);
Route::get("logout", [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::apiResource('suppliers', SupplierController::class)->middleware('auth:sanctum');
