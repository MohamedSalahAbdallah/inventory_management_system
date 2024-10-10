<?php

namespace App\Http\Controllers;

use App\Models\ProductSalesOrder;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SalesOrder::with(['user', 'productSalesOrders.product'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = [];
        $fields['user_id'] = auth('sanctum')->id();
        $fields['total_amount'] = 0;
        $salesOrder = SalesOrder::create($fields);
        $totalAmount = 0;
        $fields = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.quantity' => 'required|integer|min:1',

            'customer' => 'required|array',
            'customer.name' => 'required|string|max:255',
            'customer.phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
        ]);

        foreach ($fields['products'] as $product) {

            ProductSalesOrder::create([
                'sales_order_id' => $salesOrder->id,
                'product_id' => $product['product_id'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
            ]);

            $totalAmount += $product['price'] * $product['quantity'];
        }

        $salesOrder->total_amount = $totalAmount;
        $salesOrder->save();

        $salesOrder = SalesOrder::with(['user', 'productSalesOrders.product', 'customer'])->findOrFail($salesOrder->id);


        return $salesOrder;
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
