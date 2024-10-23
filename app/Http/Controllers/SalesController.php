<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\ProductSalesOrder;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        try {
            return DB::transaction(function () use ($request) {
                $fields = [];
                if (isset($request->customer) && $request->customer['phone']) {
                    $request->validate([
                        'customer.name' => 'nullable|string|max:255',
                        'customer.phone' => 'required|string|regex:/^\+?[0-9]{10,15}$/',
                    ]);

                    $customer = Customer::where('phone', $request->customer['phone'])->first();

                    if (!$customer) {
                        $customer = Customer::create($request->customer);
                    }
                    $fields['customer_id'] = $customer->id;
                }

                $fields['user_id'] = auth('sanctum')->id();
                $fields['total_amount'] = 0;
                $salesOrder = SalesOrder::create($fields);
                $totalAmount = 0;
                $fields = $request->validate([
                    'products' => 'required|array|min:1',
                    'products.*.product_id' => 'required|integer|exists:products,id',
                    'products.*.price' => 'required|numeric|min:0',
                    'products.*.quantity' => 'required|integer|min:1',
                    'products.*.warehouse_section_id' => 'required|integer|exists:warehouse_sections,id',
                ]);

                foreach ($fields['products'] as $product) {
                    $productInstance = Product::with(['productWarehouse'])->findOrFail($product['product_id']);
                    $productWarehouse = $productInstance->productWarehouse->where('warehouse_section_id', $product['warehouse_section_id'])->first();
                    $availableQuantity = $productWarehouse->quantity;
                    $requestedQuantity = $product['quantity'];
                    if ($requestedQuantity > $availableQuantity) {
                        throw new \Exception("The requested quantity for product {$productInstance->name} is more than the available quantity '{$availableQuantity}'");
                    }

                    ProductSalesOrder::create([
                        'sales_order_id' => $salesOrder->id,
                        'product_id' => $product['product_id'],
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                    ]);

                    $totalAmount += $product['price'] * $product['quantity'];
                    $productInstance->decrement('quantity', $product['quantity']);
                    $productWarehouse->decrement('quantity', $product['quantity']);
                    $productWarehouse->warehouseSection->increment('empty_slots', $product['quantity']);
                    $productWarehouse->warehouseSection->decrement('reserved_slots', $product['quantity']);
                }

                $salesOrder->total_amount = $totalAmount;
                $salesOrder->save();

                $salesOrder = SalesOrder::with(['user', 'productSalesOrders.product', 'customer'])->findOrFail($salesOrder->id);

                return $salesOrder;
            });
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salesOrder = SalesOrder::with(['user', 'productSalesOrders.product', 'customer'])->findOrFail($id);
        return $salesOrder;
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
