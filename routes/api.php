<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPurchaseOrderController;
use App\Http\Controllers\ProductSalesOrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductWarehouseController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseSectionController;
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


route::get('salesInvoice/{id}', [InvoicesController::class, 'salesInvoice'])->middleware('auth:sanctum');


route::get('purchaseInvoice/{id}', [InvoicesController::class, 'purchaseInvoice'])->middleware('auth:sanctum');

Route::apiResource('purchase', PurchaseController::class)->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logs', [ActivityLogController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/logs/user/{userId}', [ActivityLogController::class, 'getUserLogs'])->middleware('auth:sanctum');
});
Route::apiResource('warehouses', WarehouseController::class)->middleware('auth:sanctum')->middleware('auth:sanctum');

Route::apiResource('warehouse-sections', WarehouseSectionController::class)->middleware('auth:sanctum');

Route::apiResource('product-warehouses', ProductWarehouseController::class)->middleware('auth:sanctum');

Route::apiResource('sales', SalesController::class)->middleware('auth:sanctum');


Route::get('top-selling-products/{length}/{days}', [ChartsController::class, 'topSellingProducts'])->middleware('auth:sanctum');

Route::get('sales-of/{days}', [ChartsController::class, 'SalesPerDays'])->middleware('auth:sanctum');


Route::get('widgets', [ChartsController::class, 'widgets'])->middleware('auth:sanctum');

Route::get('products-per-category/{length}', [ChartsController::class, 'productsPerCategory'])->middleware('auth:sanctum');

//inventoryController

Route::get('warehouse-index', [InventoryController::class, 'warehouseIndex'])->middleware('auth:sanctum');


Route::get('warehouse-show/{id}', [InventoryController::class, 'warehouseShow'])->middleware('auth:sanctum');

Route::post('warehouse-store', [InventoryController::class, 'warehouseStore'])->middleware('auth:sanctum');

Route::put('warehouse-update/{id}', [InventoryController::class, 'warehouseUpdate'])->middleware('auth:sanctum');

Route::delete('warehouse-destroy/{id}', [InventoryController::class, 'warehouseDestroy'])->middleware('auth:sanctum');


Route::get('warehouseSection-index', [InventoryController::class, 'warehouseSectionIndex'])->middleware('auth:sanctum');


Route::get('warehouseSection-show/{id}', [InventoryController::class, 'warehouseSectionShow'])->middleware('auth:sanctum');

Route::post('warehouseSection-store', [InventoryController::class, 'warehouseSectionStore'])->middleware('auth:sanctum');

Route::put('warehouseSection-update/{id}', [InventoryController::class, 'warehouseSectionUpdate'])->middleware('auth:sanctum');

Route::delete('warehouseSection-destroy/{id}', [InventoryController::class, 'warehouseSectionDestroy'])->middleware('auth:sanctum');


Route::get('productWarehouse-index', [InventoryController::class, 'productWarehouseIndex'])->middleware('auth:sanctum');
Route::get('productWarehouse-show/{id}', [InventoryController::class, 'productWarehouseShow'])->middleware('auth:sanctum');
Route::post('productWarehouse-store', [InventoryController::class, 'productWarehouseStore'])->middleware('auth:sanctum');
Route::put('productWarehouse-update/{id}', [InventoryController::class, 'productWarehouseUpdate'])->middleware('auth:sanctum');
Route::delete('productWarehouse-destroy/{id}', [InventoryController::class, 'productWarehouseDestroy'])->middleware('auth:sanctum');
