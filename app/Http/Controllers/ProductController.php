<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::with(['category', 'supplier', 'inventoryMovements', 'productSalesOrders.salesOrder', 'productPurchaseOrders.purchaseOrder', 'adjustments'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sku' => 'required|numeric|min:1|unique:products',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif',
        ]);

        // store the image
        $image = $request->file('image');
        $imageName = uniqid() . '_' . $image->getClientOriginalName();
        $image->storeAs('public/images/', $imageName);
        $imageName = 'http://127.0.0.1:8000/storage/images/' . $imageName;



        // store the product
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'sku' => $request->sku,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'image' => $imageName,
        ]);
        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category', 'supplier', 'inventoryMovements', 'productSalesOrders.salesOrder', 'productPurchaseOrders.purchaseOrder', 'adjustments'])->findOrFail($id);
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sku' => 'required|numeric|min:1|unique:products,sku,' . $id,
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif',

        ]);

        // get the product
        $product = Product::findOrFail($id);

        // update the product
        $product->name = $request->name;
        $product->description = $request->description;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->supplier_id = $request->supplier_id;

        // handle the image
        if ($request->hasFile('image')) {
            // delete the old image
            if ($product->image != null) {
                Storage::delete(
                    'public/images/' . str_replace("http://127.0.0.1:8000/storage/images/", "", "$product->image")
                );
            }

            // store the new image
            $image = $request->file('image');
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images/', $imageName);
            $imageName = 'http://127.0.0.1:8000/storage/images/' . $imageName;

            // update the product image
            $product->image = $imageName;
        }

        // save the product
        $product->save();

        // return the product
        return $product;
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return ['message' => "Product Deleted"];
    }
}
