<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPurchaseOrderController;
use App\Http\Controllers\ProductSalesOrderController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::apiResource('roles', RoleController::class)->middleware('auth:sanctum');

Route::post("register", [AuthController::class, 'register']);
Route::post("login", [AuthController::class, 'login'])->name('login');
Route::get("logout", [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::apiResource("categories", controller: CategoryController::class)->middleware('auth:sanctum');

Route::apiResource("salesorders", SalesOrderController::class)->middleware('auth:sanctum');

Route::apiResource('suppliers', SupplierController::class)->middleware('auth:sanctum');

Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');

Route::apiResource('inventory-movements', InventoryMovementController::class)->middleware('auth:sanctum');

Route::apiResource('product-sales-orders', ProductSalesOrderController::class)->middleware('auth:sanctum');

Route::apiResource('users', UserController::class)->middleware('auth:sanctum');

Route::apiResource("purchase-orders", PurchaseOrderController::class)->middleware('auth:sanctum');

Route::apiResource('product-purchase-orders', ProductPurchaseOrderController::class)->middleware('auth:sanctum');


route::get('salesInvoice/{id}', [InvoicesController::class, 'salesInvoice']);


route::get('purchaseInvoice/{id}', [InvoicesController::class, 'purchaseInvoice']);
