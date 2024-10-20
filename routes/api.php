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


route::get('salesInvoice/{id}', [InvoicesController::class, 'salesInvoice']);


route::get('purchaseInvoice/{id}', [InvoicesController::class, 'purchaseInvoice']);

Route::apiResource('purchase', PurchaseController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logs', [ActivityLogController::class, 'index']);
    Route::get('/logs/user/{userId}', [ActivityLogController::class, 'getUserLogs']);
});
Route::apiResource('warehouses', WarehouseController::class)->middleware('auth:sanctum');

Route::apiResource('warehouse-sections', WarehouseSectionController::class)->middleware('auth:sanctum');

Route::apiResource('product-warehouses', ProductWarehouseController::class)->middleware('auth:sanctum');

Route::apiResource('sales', SalesController::class);


Route::get('top-selling-products/{length}/{days}', [ChartsController::class, 'topSellingProducts']);

Route::get('sales-of/{days}', [ChartsController::class, 'SalesPerDays']);


Route::get('widgets', [ChartsController::class, 'widgets']);

Route::get('products-per-category/{length}', [ChartsController::class, 'productsPerCategory']);

//inventoryController

Route::get('warehouse-index', [InventoryController::class, 'warehouseIndex']);


Route::get('warehouse-show/{id}', [InventoryController::class, 'warehouseShow']);

Route::post('warehouse-store', [InventoryController::class, 'warehouseStore']);

Route::put('warehouse-update/{id}', [InventoryController::class, 'warehouseUpdate']);

Route::delete('warehouse-destroy/{id}', [InventoryController::class, 'warehouseDestroy']);


Route::get('warehouseSection-index', [InventoryController::class, 'warehouseSectionIndex']);


Route::get('warehouseSection-show/{id}', [InventoryController::class, 'warehouseSectionShow']);

Route::post('warehouseSection-store', [InventoryController::class, 'warehouseSectionStore']);

Route::put('warehouseSection-update/{id}', [InventoryController::class, 'warehouseSectionUpdate']);

Route::delete('warehouseSection-destroy/{id}', [InventoryController::class, 'warehouseSectionDestroy']);


Route::get('productWarehouse-index', [InventoryController::class, 'productWarehouseIndex']);
Route::get('productWarehouse-show/{id}', [InventoryController::class, 'productWarehouseShow']);
Route::post('productWarehouse-store', [InventoryController::class, 'productWarehouseStore']);
Route::put('productWarehouse-update/{id}', [InventoryController::class, 'productWarehouseUpdate']);
Route::delete('productWarehouse-destroy/{id}', [InventoryController::class, 'productWarehouseDestroy']);
