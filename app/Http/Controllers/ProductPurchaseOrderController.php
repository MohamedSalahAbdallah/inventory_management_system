<?php

namespace App\Http\Controllers;

use App\Models\ProductPurchaseOrder;
use Illuminate\Http\Request;

class ProductPurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductPurchaseOrder::with(['product', 'purchaseOrder'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "quantity" => 'integer|required|min:1',
            'price' => 'required|numeric|min:0',
            'product_id' => 'required|exists:products,id',
            "purchase_order_id" => 'required|exists:purchase_orders,id'
        ]);

        $productPurchaseOrder = ProductPurchaseOrder::create($request->all());
        return $productPurchaseOrder;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ProductPurchaseOrder::with(['product', 'purchaseOrder'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "quantity" => 'integer|required|min:1',
            'price' => 'required|numeric|min:0',
            'product_id' => 'required|exists:products,id',
            "purchase_order_id" => 'required|exists:purchase_orders,id'
        ]);

        $productPurchaseOrder = ProductPurchaseOrder::findOrFail($id);
        $productPurchaseOrder->update($request->all());
        return $productPurchaseOrder;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $productPurchaseOrder = ProductPurchaseOrder::findOrFail($id);
        $productPurchaseOrder->delete();
        return response()->json(['message' => 'Product Purchase Order deleted successfully']);
    }
}
