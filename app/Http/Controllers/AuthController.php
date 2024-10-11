<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|integer|exists:roles,id',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        $token = $user->createToken($request->email);


        return [
            "user" => $user->load('role'),
            "token" => $token->plainTextToken
        ];
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email|max:255',
            "password" => 'required|min:8'
        ]);

        $user = User::with(relations: 'role')->where("email", $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {

            return response([
                "message" => "Wrong email or password"
            ], 401);
        } else {
            $token = $user->createToken($request->email);

            return response([
                "user" => $user,
                "token" => $token->plainTextToken
            ], 200);
        }
        ;
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response([
            "message" => "Logged out successfully"
        ], 200);
    }
}
