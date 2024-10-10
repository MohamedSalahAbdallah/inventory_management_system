<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SalesOrder::with(['user', 'productSalesOrders.product', 'customer'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'total_amount' => 'required|numeric|min:1',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,completed'
        ]);
        $salesOrder = SalesOrder::create($fields);
        return $salesOrder;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return SalesOrder::with(['user', 'productSalesOrders.product', 'customer'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $fields = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'total_amount' => 'required|numeric|min:1',
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,completed'
        ]);
        $salesOrder->update($fields);
        return $salesOrder;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrder->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }
}
