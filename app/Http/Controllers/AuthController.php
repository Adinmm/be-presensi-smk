<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller {
    public function register(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->createToken('auth_token')->plainTextToken;

        return $this->sendSuccessResponse('Register berhasil', null);
    }

    public function login(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        $cookie = cookie(
            'token',
            $token,
            0,
            '/',
            null,
            false,
            true
        );

        return $this->sendSuccessResponse('Login berhasil', [
            'role' => $user->role
        ])->withCookie($cookie);
    }

    public function me(Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

    public function logout(Request $request) {
        $cookie = Cookie::forget('token', '/');
        $request->user()->currentAccessToken()->delete();
        return $this->sendSuccessResponse('Logout berhasil', null)->withCookie($cookie);
    }
}
