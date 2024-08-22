<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return SalesOrder::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'user_id' => 'required|integer',
            'total_amount' => 'required|decimal:,2',
            'status' => 'required|string|max:255'

        ]);
        $salesOrder = SalesOrder::create($fields);
        return $salesOrder;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return SalesOrder::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $fields = $request->validate([
            'user_id' => 'required|integer',
            'total_amount' => 'required|decimal:,2',
            'status' => 'required|string|max:255'
        ]);
        $salesOrder->update($fields);
        return $salesOrder;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        $salesOrder->delete();
        return "Deleted Successfully";
    }
}
