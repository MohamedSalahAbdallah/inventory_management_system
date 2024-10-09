<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Supplier::with(['products.category', 'purchaseOrders'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:1',
            'email' => 'required|string|email|max:255|unique:suppliers,email',
            'phone' => 'required|string|max:255|unique:suppliers,phone',
            'address' => 'required|string|max:255|min:1',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ]);




        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images/', $imageName);
            $imageName = 'http://127.0.0.1:8000/storage/images/' . $imageName;
        }
        $supplier = Supplier::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'image' => $imageName ?? null,

        ]);

        return $supplier;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::with(['products', 'purchaseOrders'])->findOrFail($id);
        return $supplier;
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     *
     * if the value is the same as the old value then dont update the value
     * if it is different then update it
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|min:1',
            'email' => 'required|string|email|max:255|unique:suppliers,email,' . $id,
            'phone' => 'required|string|max:255|unique:suppliers,phone,' . $id,
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'address' => 'required|string|max:255|min:1',

        ]);

        $supplier = Supplier::findOrFail($id);
        // Only update the values that are in the validation
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;

        if ($request->hasFile('image')) {
            // delete the old image
            if ($supplier->image != null) {
                Storage::delete(
                    'public/images/' . str_replace("http://127.0.0.1:8000/storage/images/", "", "$supplier->image")
                );
            }

            // store the new image
            $image = $request->file('image');
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images/', $imageName);
            $imageName = 'http://127.0.0.1:8000/storage/images/' . $imageName;

            // update the supp$supplier image
            $supplier->image = $imageName;
        }

        // save the supplier
        $supplier->save();
        $supplier = Supplier::with(['products.category', 'purchaseOrders'])->findOrFail($id);
        return $supplier;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['message' => 'Supplier Deleted']);
    }
}
