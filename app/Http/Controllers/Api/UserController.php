<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:3'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'message' => 'User created successfully.'
        ]);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $user = Auth::user();

            $token = $user->createToken('auth_token')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
                'message' => 'User logged in successfully.'
            ]);
        }
        return response()->json([
            'message' => 'Invalid credss.'
        ]);
    }
    public function findUser($id)
    {
        $user = User::find($id);
        //check if user not found at all or requesting other users data
        if (is_null($user) || auth('api')->user()->id != $user->id) {
            return response()->json([
                'user' => null,
                'message' => 'User not found.'
            ]);
        } else {
            return response()->json([
                'user' => $user,
                'message' => 'User found.'
            ]);
        }
    }
    public function logout()
    {
        auth()->user()->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Logged out successfully']);
    }
}
