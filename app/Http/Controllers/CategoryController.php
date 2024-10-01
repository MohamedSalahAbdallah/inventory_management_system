<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Category::with('products')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => "required|string|unique:categories,name"
        ]);
        $category = Category::create($fields);
        return $category;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Category::with('products')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $fields = $request->validate([
            'name' => "required|string|unique:categories,name"
        ]);
        $category->update($fields);
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return "DELETED SUCCESSFULLY";
    }
}
