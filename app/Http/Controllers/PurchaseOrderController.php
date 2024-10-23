<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
            'supplier_id' => 'required|numeric|exists:suppliers,id'
        ]);



        $request->request->add(['user_id' => auth('sanctum')->id()]);


        $purchaseOrder = PurchaseOrder::create($request->all());

        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrder->id);
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

            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,completed',
            'warehouse_section_id' => 'numeric|required|exists:warehouse_sections,id'
        ]);

        $purchaseOrder = PurchaseOrder::with(['user', 'supplier', 'productPurchaseOrders.product'])->findOrFail($id);

        if ($purchaseOrder->status == 'delivered' && $request->status == 'cancelled') {
            foreach ($purchaseOrder->productPurchaseOrders as $item) {
                $product = Product::with(['productWarehouse'])->findOrFail($item->product->id);
                $product->quantity -= $item->quantity;
                $product->save();

                $productWarehouse = $product->productWarehouse()->where('warehouse_section_id', $request->warehouse_section_id)->first();
                if ($productWarehouse) {
                    $productWarehouse->quantity -= $item->quantity;
                    $productWarehouse->save();
                }
            }
        } elseif ($request->status == 'delivered') {
            foreach ($purchaseOrder->productPurchaseOrders as $item) {
                $product = Product::with(['productWarehouse'])->findOrFail($item->product->id);
                $product->quantity += $item->quantity;
                $product->save();

                $productWarehouse = $product->productWarehouse()->where('warehouse_section_id', $request->warehouse_section_id)->first();
                if ($productWarehouse) {
                    $productWarehouse->quantity += $item->quantity;
                    $productWarehouse->save();
                }
            }
        }

        $purchaseOrder->update([
            'status' => $request->status,
        ]);

        $purchaseOrder = PurchaseOrder::with(['user', 'supplier', 'productPurchaseOrders.product'])->findOrFail($id);
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
