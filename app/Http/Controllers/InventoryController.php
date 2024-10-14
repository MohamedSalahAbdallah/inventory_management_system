<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    // warehouse

    public function warehouseIndex()
    {
        return Warehouse::get();
    }

    public function warehouseShow($id)
    {
        return Warehouse::with(['sections.productsWarehouse.product'])->findOrFail($id);
    }

    public function warehouseStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'total_capacity' => 'required|integer|gt:0',
        ]);

        return Warehouse::create($request->only(['name', 'location', 'total_capacity']));
    }

    public function warehouseUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:255',
            'location' => 'string|max:255',
            'total_capacity' => 'integer|gt:0',
        ]);

        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->only(['name', 'location', 'total_capacity']));
        return $warehouse;
    }

    public function warehouseDestroy($id)
    {
        return Warehouse::findOrFail($id)->delete();
    }

    // warehouse section
    
}
