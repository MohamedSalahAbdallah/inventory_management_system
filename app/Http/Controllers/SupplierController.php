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
            'email' => 'required|string|email|unique:suppliers,email|max:255',
            'phone' => 'required|string|unique:suppliers,phone',
            'address' => 'required|string|max:255',

        ]);

        $supplier = Supplier::create($request->all());

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
            'email' => 'required|string|email|unique:suppliers,email|max:255',
            'phone' => 'required|string|unique:suppliers,phone',
            'address' => 'required|string|max:255',
        ]);

        $supplier = Supplier::findOrFail($id);

        foreach ($request->all() as $key => $value) {
            // if the value is different from the old value
            // then update it
            if ($value != $supplier->$key) {
                $supplier->$key = $value;
            }
        }

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
