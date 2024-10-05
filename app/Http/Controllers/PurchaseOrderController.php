<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PurchaseOrder::with(['user', 'supplier', 'productPurchaseOrders.product'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'total_amount' => 'required|numeric|min:1',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,completed',
            'supplier_id' => 'required|numeric|exists:suppliers,id'
        ]);

        $purchaseOrder = PurchaseOrder::create($request->all());

        return $purchaseOrder;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return PurchaseOrder::with(['user', 'supplier', 'productPurchaseOrders.product'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'total_amount' => 'required|numeric|min:1',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,completed',
            'supplier_id' => 'required|numeric|exists:suppliers,id'
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->update($request->all());
        return $purchaseOrder;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->delete();
        return response()->json(['message' => "Deleted Successfully"]);
    }
}
