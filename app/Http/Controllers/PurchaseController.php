<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductPurchaseOrder;
use App\Models\ProductWarehouse;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'supplier_id' => 'required|numeric|exists:suppliers,id',
            'warehouse_section_id' => 'numeric|required',
        ]);

        $totalAmount = 0;

        try {
            return DB::transaction(function () use ($request, &$totalAmount) {


                $purchaseOrder = PurchaseOrder::create([
                    'supplier_id' => $request->supplier_id,
                    'user_id' => auth('sanctum')->id(),
                    'total_amount' => $totalAmount
                ]);

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
                    $totalAmount += $product['price'] * $product['quantity'];

                    $productWarehouse = ProductWarehouse::firstOrCreate([
                        'product_id' => $product['product_id'],
                        'warehouse_section_id' => $request->warehouse_section_id,
                    ], [
                        'quantity' => 0,
                        'empty_slots' => 0,
                        'reserved_slots' => 0,
                    ]);

                    if ($productWarehouse->empty_slots >= $product['quantity']) {
                        $productWarehouse->update([
                            'empty_slots' => $productWarehouse->empty_slots - $product['quantity'],
                            'reserved_slots' => $productWarehouse->reserved_slots + $product['quantity'],
                        ]);
                    } else {
                        return response()->json(['message' => 'There are not enough empty slots for the product ' . $product['product_id'] . ' in the warehouse section ' . $request->warehouse_section_id], 422);
                    }
                }

                $purchaseOrder->update([
                    'total_amount' => $totalAmount,
                ]);
                $purchaseOrder = PurchaseOrder::with(['user', 'supplier', 'productPurchaseOrders.product'])->findOrFail($purchaseOrder->id);
                return $purchaseOrder;
            });
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
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
