<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSalesOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('roles', RoleController::class)->middleware('auth:sanctum');

Route::apiResource("categories", CategoryController::class)->middleware('auth:sanctum');
Route::apiResource("salesorders", SalesOrderController::class);
Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login'])->name('login');
Route::get("logout", [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::apiResource('suppliers', SupplierController::class)->middleware('auth:sanctum');

Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');

Route::apiResource('inventory-movements', InventoryMovementController::class)->middleware('auth:sanctum');

Route::apiResource('product-sales-orders', ProductSalesOrderController::class)->middleware('auth:sanctum');
