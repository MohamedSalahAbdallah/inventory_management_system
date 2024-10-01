<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::with(['role', 'salesOrders', 'purchaseOrders', 'adjustments'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => 'required|string',
            "email" => 'required|email|unique:users,email',
            "password" => 'required|min:8|confirmed',
            "role_id" => 'required|exists:roles,id'
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role_id" => $request->role_id
        ]);

        return $user;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return User::with(['role', 'salesOrders', 'purchaseOrders', 'adjustments'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "name" => 'required|string',
            "email" => 'required|email|unique:users,email,' . $id,
            "password" => 'required|min:8|confirmed',
            "role_id" => 'required|exists:roles,id'
        ]);

        $user = User::findOrFail($id);
        $user->update([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role_id" => $request->role_id
        ]);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return "User Deleted";
    }
}
