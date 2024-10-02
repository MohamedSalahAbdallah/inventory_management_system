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
        $user = User::findOrFail($id);

        $updatedUser = [];

        if ($request->name != $user->name) {
            $request->validate([
                "name" => 'required|string',
            ]);

            $updatedUser['name'] = $request->name;
        }

        if ($request->email != $user->email) {
            $request->validate([
                "email" => 'required|email|unique:users,email,' . $id,
            ]);

            $updatedUser['email'] = $request->email;
        }

        if ($request->password) {
            $request->validate([
                "password" => 'required|min:8|confirmed',
            ]);

            $updatedUser['password'] = bcrypt($request->password);
        }

        if ($request->role_id != $user->role_id) {
            $request->validate([
                "role_id" => 'required|exists:roles,id'
            ]);

            $updatedUser['role_id'] = $request->role_id;
        }

        if (count($updatedUser) > 0) {
            $user->update($updatedUser);
        }

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User Deleted']);
    }
}
