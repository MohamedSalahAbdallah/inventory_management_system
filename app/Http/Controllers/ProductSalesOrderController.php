<?php

namespace App\Http\Controllers;

use App\Models\ProductSalesOrder;
use Illuminate\Http\Request;

class ProductSalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductSalesOrder::with(['product', 'salesOrder'])->get();
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
            "sales_order_id" => 'required|exists:sales_orders,id'
        ]);

        $productSalesOrder = ProductSalesOrder::create($request->all());
        return $productSalesOrder;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return ProductSalesOrder::with(['product', 'salesOrder'])->findOrFail($id);
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
            "sales_order_id" => 'required|exists:sales_orders,id'
        ]);

        $productSalesOrder = ProductSalesOrder::findOrFail($id);
        $productSalesOrder->update($request->all());
        return $productSalesOrder;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productSalesOrder = ProductSalesOrder::findOrFail($id);
        $productSalesOrder->delete();
        return "Product Sales Order Deleted";
    }
}
