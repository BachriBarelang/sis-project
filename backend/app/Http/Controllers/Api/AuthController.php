<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
        public function registerAdmin(Request $request)
            {
                $request->validate([
                    'email' => [
                        'required',
                        'email',
                        'unique:users,email',
                    ],
                    'password' => [
                    'required',
                    'min:6',
                    'confirmed',
                    ],
                    'special_key' => [
                        'required',
                    ],
                ]);

                if (!Hash::check(
                    $request->special_key,
                    env('ADMIN_SPECIAL_KEY_HASH')
                )) {
                    return response()->json([
                        'message' => 'Special key tidak valid',
                    ], 403);
                }

                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make(
                        $request->password
                    ),
                    'role' => 'admin',
                ]);

                return response()->json([
                    'message' => 'Admin berhasil dibuat',
                ], 201);
            }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    public function profile()
    {
        return response()->json(Auth::user());
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}