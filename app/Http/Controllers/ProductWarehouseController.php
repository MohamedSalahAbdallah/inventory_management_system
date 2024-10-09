<?php

namespace App\Http\Controllers;

use App\Models\ProductWarehouse;
use Illuminate\Http\Request;

class ProductWarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductWarehouse::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'warehouse_section_id' => 'required|integer|min:1|exists:warehouse_sections,id',
            'product_id' => 'required|integer|min:1|exists:products,id',
            'quantity' => 'required|integer|min:0'
        ]);

        return ProductWarehouse::create($fields);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductWarehouse $productWarehouse)
    {
        return $productWarehouse;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductWarehouse $productWarehouse)
    {
        $updatedProductWarehouse = [];
        if ($request->warehouse_section_id != $productWarehouse->warehouse_section_id) {
            $request->validate([
                'warehouse_section_id' => 'integer|min:1|exists:warehouse_sections,id'
            ]);
            $updatedProductWarehouse['warehouse_section_id'] = $request->warehouse_section_id;
        }
        if ($request->product_id != $productWarehouse->product_id) {
            $request->validate([
                'product_id' => 'integer|min:1|exists:products,id'
            ]);
            $updatedProductWarehouse['product_id'] = $request->product_id;
        }
        if ($request->quantity != $productWarehouse->quantity) {
            $request->validate([
                'quantity' => 'integer|min:0'
            ]);
            $updatedProductWarehouse['quantity'] = $request->quantity;
        }

        if (!empty($updatedProductWarehouse)) {
            $productWarehouse->update($updatedProductWarehouse);
        }

        return $productWarehouse;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductWarehouse $productWarehouse)
    {
        $productWarehouse->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }
}
