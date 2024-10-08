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
            "quantity" => 'integer|required|min:1',
            'price' => 'required|numeric|min:0',
            'product_id' => 'required|exists:products,id',
            "purchase_order_id" => 'required|exists:purchase_orders,id'
        ]);

        $productPurchaseOrder = ProductPurchaseOrder::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
