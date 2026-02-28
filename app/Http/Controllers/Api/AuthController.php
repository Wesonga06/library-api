<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // --- REGISTER NEW USER ---
    public function register(Request $request)
    {
        // 1. Validate the incoming data from your frontend form
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed', // 'confirmed' expects 'password_confirmation' which we sent from JS!
        ]);

        // 2. Create the user in the database
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],
            'password' => Hash::make($validatedData['password']), // ALWAYS hash passwords!
        ]);

        // 3. Return a success response
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    // --- LOGIN USER ---
    public function login(Request $request)
    {
        // 1. Validate the login request
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Check if the credentials are correct
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        // 3. Get the user from the database
        $user = User::where('email', $request->email)->firstOrFail();

        // 4. Generate the Sanctum Auth Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. Send the token and user data back to the frontend
        return response()->json([
            'message' => 'Login successful',
            'token' => $token, // This is what your JS saves to localStorage!
            'user' => $user
        ], 200);
    }

    // --- LOGOUT USER ---
    public function logout(Request $request)
    {
        // 1. Check if we actually have a logged-in user making the request
        if ($request->user()) {
            // 2. Go directly to the tokens relationship and delete them from the database
            $request->user()->tokens()->delete();
        }

        // 3. Always return a success message so the frontend can move on
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
