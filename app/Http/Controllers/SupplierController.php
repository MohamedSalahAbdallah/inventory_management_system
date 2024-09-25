<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Supplier::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|phone',
            'address' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ]);

        $supplier = new Supplier($request->all());


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName();
            $image->storeAs('public/images', $imageName);
            $supplier->image = $imageName;
        }


        $supplier->save();
        $supplier->image = $_ENV['APP_URL'] . '/' . $supplier->image;
        return $supplier;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $supplier = Supplier::findOrFail($id);
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:suppliers,email,' . $id,
            'phone' => 'required|string|max:255|unique:suppliers,phone,' . $id,
            'address' => 'required|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);
        // Only update the values that are in the validation
        $supplier->name = $request->name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;

        // save the supplier
        $supplier->save();

        // return the supplier

        return $supplier;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return "Supplier Deleted";
    }
}
