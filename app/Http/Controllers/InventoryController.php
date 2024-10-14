<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseSection;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    // warehouse

    public function warehouseIndex()
    {
        return Warehouse::with(['sections.productsWarehouse.product'])->get();
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

        $warehouse = Warehouse::with(['sections.productsWarehouse.product'])->findOrFail($id);
        $warehouse->update($request->only(['name', 'location', 'total_capacity']));
        return $warehouse;
    }

    public function warehouseDestroy($id)
    {
        Warehouse::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted successfully']);
    }

    // warehouse-section


    public function warehouseSectionIndex()
    {
        return WarehouseSection::with(['productsWarehouse.product'])->get();
    }

    public function warehouseSectionShow($id)
    {
        return WarehouseSection::with(['productsWarehouse.product'])->findOrFail($id);
    }

    public function warehouseSectionStore(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|integer|exists:warehouses,id',
            'section_name' => 'required|string|max:255',
            'section_type' => 'required|string|max:255|in:refrigerator,shelves,other',
            'capacity' => 'required|integer|gt:0'
        ]);
        $empty_slots = $request->capacity;
        $reserved_slots = 0;

        $newSection = WarehouseSection::create($request->only(['warehouse_id', 'section_name', 'section_type', 'capacity']) + compact('empty_slots', 'reserved_slots'));

        return $newSection;
    }

    public function warehouseSectionUpdate(Request $request, $id)
    {
        $request->validate([
            'warehouse_id' => 'integer|exists:warehouses,id',
            'section_name' => 'string|max:255',
            'section_type' => 'string|max:255|in:refrigerator,shelves,other',
            'capacity' => 'integer|gt:0',
            'empty_slots' => 'integer|min:0',
            'reserved_slots' => 'integer|min:0'
        ]);

        $section = WarehouseSection::with(['productsWarehouse.product'])->findOrFail($id);
        $section->update($request->all());
        return $section;
    }

    public function warehouseSectionDestroy($id)
    {
        WarehouseSection::findOrFail($id)->delete();
        return response()->json(['message' => 'deleted successfully']);
    }
}
