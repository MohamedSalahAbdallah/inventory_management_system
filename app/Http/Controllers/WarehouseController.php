<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Warehouse::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|min:3|unique:warehouses,name',
            'location' => 'required|string|min:3',
            'total_capacity' => 'required|integer|min:1'
        ]);

        return Warehouse::create($fields);
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        return $warehouse;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    // public function update(Request $request, Warehouse $warehouse)
    {
        $updatedWarehouse = [];
        if ($request->name != $warehouse->name) {
            $request->validate([
                'name' => [
                    'string',
                    'min:3',
                    Rule::unique('warehouses', 'name')->ignore($warehouse)->whereNull('deleted_at')
                ]
            ]);
            $updatedWarehouse['name'] = $request->name;
        }
        if ($request->location != $warehouse->location) {
            $request->validate([
                'location' => 'string|min:3'
            ]);
            $updatedWarehouse['location'] = $request->location;
        }
        if ($request->total_capacity != $warehouse->total_capacity) {
            $request->validate([
                'total_capacity' => 'integer|min:1'
            ]);
            $updatedWarehouse['total_capacity'] = $request->total_capacity;
        }
        if (!empty($updatedWarehouse)) {
            $warehouse->update($updatedWarehouse);
        }

        return $warehouse;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return response()->json(['message' => 'Warehouse Deleted Successfully']);
    }
}
