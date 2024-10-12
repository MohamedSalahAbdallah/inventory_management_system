<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::with(['role', 'salesOrders', 'purchaseOrders', 'adjustments'])
            ->where('id', '!=', auth('sanctum')->user()->id)
            ->get();
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
            "role_id" => 'required|exists:roles,id',
        ]);
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,jpg,png,gif',
            ]);
            $image = $request->file('image');
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/images/', $imageName);
            $imageName = 'http://127.0.0.1:8000/storage/images/' . $imageName;
        }


        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "role_id" => $request->role_id,
            'image' => $imageName
        ]);

        return User::with(['role'])->findOrFail($user->id);;
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
        if (isset($request->image)) {
            if ($request->hasFile('image')) {
                $request->validate([
                    'image' => 'required|image|mimes:jpeg,jpg,png,gif',
                ]);
                // delete the old image
                if ($user->image != null) {
                    Storage::delete(
                        'public/images/' . str_replace("http://127.0.0.1:8000/storage/images/", "", "$user->image")
                    );
                }

                // store the new image
                $image = $request->file('image');
                $imageName = uniqid() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images/', $imageName);
                $imageName = 'http://127.0.0.1:8000/storage/images/' . $imageName;

                // update the user image
                $user->update([
                    'image' => $imageName
                ]);
                return $user;
            }
        } else {

            if (isset($request->current_password) && !Hash::check($request->current_password, $user->password)) {
                return response([
                    'error' => [
                        'current_password' => 'Current password is invalid'
                    ]
                ], 401);
            } elseif (!isset($request->current_password)) {
                return response([
                    'error' => [
                        'current_password' => 'Current password is required'
                    ]
                ], 401);
            }

            $updatedUser = [];
            if (isset($request->name) && $request->name != $user->name) {
                $request->validate([
                    "name" => 'required|string',
                ]);

                $updatedUser['name'] = $request->name;
            }

            if (isset($request->email) && $request->email != $user->email) {
                $request->validate([
                    "email" => 'required|email|unique:users,email,' . $id,
                ]);

                $updatedUser['email'] = $request->email;
            }

            if (isset($request->new_password)) {
                $request->validate([
                    "new_password" => 'required|min:8|confirmed',
                ]);

                $updatedUser['password'] = bcrypt($request->new_password);
            }

            if (isset($request->role_id) && $request->role_id != $user->role_id) {
                $request->validate([
                    "role_id" => 'required|exists:roles,id'
                ]);

                $updatedUser['role_id'] = $request->role_id;
            }
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
