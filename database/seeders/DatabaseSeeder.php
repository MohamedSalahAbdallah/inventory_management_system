<?php

namespace Database\Seeders;

use App\Models\Adjustment;
use App\Models\Category;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductPurchaseOrder;
use App\Models\ProductSalesOrder;
use App\Models\ProductWarehouse;
use App\Models\PurchaseOrder;
use App\Models\Role;
use App\Models\SalesOrder;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\WarehouseSection;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::factory(3)->create();
        User::factory(10)->create();
        Category::factory(10)->create();
        Supplier::factory(10)->create();
        Product::factory(100)->create();
        SalesOrder::factory(20)->create();
        PurchaseOrder::factory(20)->create();
        InventoryMovement::factory(25)->create();
        ProductSalesOrder::factory(100)->create();
        ProductPurchaseOrder::factory(100)->create();
        Adjustment::factory(25)->create();
        Warehouse::factory(5)->create();
        WarehouseSection::factory(10)->create();
        ProductWarehouse::factory(10)->create();
    }
}
