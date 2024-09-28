<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Rules\ValueExistsInTwoTables;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InventoryMovement::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => "required|numeric|min:1",
            'movement_type' => 'required|string|in:inbound, outbound, adjustment',
            'related_order_id' =>  [
                'required',
                'numeric',
                'min:1',
                new ValueExistsInTwoTables('sales_orders', 'purchase_orders', 'id')
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return InventoryMovement::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => "required|numeric|min:1",
            'movement_type' => 'required|string|in:inbound, outbound, adjustment',
            'related_order_id' =>  [
                'required',
                'numeric',
                'min:1',
                new ValueExistsInTwoTables('sales_orders', 'purchase_orders', 'id')
            ],
        ]);

        $inventoryMovement = InventoryMovement::findOrFail($id);
        $inventoryMovement->update($request->all());
        return $inventoryMovement;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $inventoryMovement = InventoryMovement::findOrFail($id);
        $inventoryMovement->delete();
        return "Deleted Successfully";
    }
}
