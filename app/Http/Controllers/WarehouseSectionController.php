<?php

namespace App\Http\Controllers;

use App\Models\WarehouseSection;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WarehouseSection::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'warehouse_id' => 'required|integer|min:1|exists:warehouses,id',
            'section_name' => 'required|string|min:3',
            'section_type' => 'required|in:refrigerator,shelves,other',
            'capacity' => 'required|integer|min:1',
            'empty_slots' => 'required|integer|min:0',
            'reserved_slots' => 'required|integer|min:0'
        ]);
        if ($fields['empty_slots'] + $fields['reserved_slots'] > $fields['capacity']) {
            return response()->json([
                'message' => 'The total of empty slots and reserved slots cannot exceed the capacity of the section.'
            ], 422);
        }

        return WarehouseSection::create($fields);
    }

    /**
     * Display the specified resource.
     */
    public function show(WarehouseSection $warehouseSection)
    {
        return $warehouseSection;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WarehouseSection $warehouseSection)
    {
        $updatedWarehouseSection = [];
        if ($request->warehouse_id != $warehouseSection->warehouse_id) {
            $request->validate([
                'warehouse_id' => 'required|integer|min:1|exists:warehouses,id'

            ]);
            $updatedWarehouseSection['warehouse_id'] = $request->warehouse_id;
        }

        if ($request->section_name != $warehouseSection->section_name) {
            $request->validate([
                'section_name' => ['required', 'string', 'min:3',]
            ]);
            $updatedWarehouseSection['section_name'] = $request->section_name;
        }

        if ($request->section_type != $warehouseSection->section_type) {
            $request->validate([
                'section_type' => 'required|in:refrigerator,shelves,other'
            ]);
            $updatedWarehouseSection['section_type'] = $request->section_type;
        }

        if ($request->capacity != $warehouseSection->capacity) {
            $request->validate([
                'capacity' => 'required|integer|min:1'
            ]);
            $updatedWarehouseSection['capacity'] = $request->capacity;
        }

        if ($request->empty_slots != $warehouseSection->empty_slots) {
            $request->validate([
                'empty_slots' => 'required|integer|min:0'
            ]);
            $updatedWarehouseSection['empty_slots'] = $request->empty_slots;
        }

        if ($request->reserved_slots != $warehouseSection->reserved_slots) {
            $request->validate([
                'reserved_slots' => 'required|integer|min:0'
            ]);
            $updatedWarehouseSection['reserved_slots'] = $request->reserved_slots;
        }

        if (!empty($updatedWarehouseSection)) {
            $warehouseSection->update($updatedWarehouseSection);
        }

        if ($warehouseSection['empty_slots'] + $warehouseSection['reserved_slots'] > $warehouseSection['capacity']) {
            return response()->json([
                'message' => 'The total of empty slots and reserved slots cannot exceed the capacity of the section.'
            ], 422);
        }

        return $warehouseSection;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WarehouseSection $warehouseSection)
    {
        $warehouseSection->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
