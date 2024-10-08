<?php

namespace App\Http\Controllers;

use App\Models\ProductPurchaseOrder;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return PurchaseOrder::with(['user', 'supplier'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'supplier_id' => 'required|numeric|exists:suppliers,id'
        ]);



        $request->request->add(['user_id' => auth('sanctum')->id()]);


        $purchaseOrder = PurchaseOrder::create($request->all());



        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($request->products as $product) {

            ProductPurchaseOrder::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $product['product_id'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
            ]);
        }

        $purchaseOrder = PurchaseOrder::with(['user', 'supplier', 'productPurchaseOrders.product'])->findOrFail($purchaseOrder->id);
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
        //
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
