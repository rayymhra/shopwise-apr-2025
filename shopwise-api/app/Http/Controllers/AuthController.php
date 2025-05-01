<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $user = User::create([
            'name' => $r->name,
            'email' => $r->email,
            'password' => bcrypt($r->password),
        ]);
        return response(['message' => 'Register berhasil']);
    }

    public function login(Request $r)
    {
        $user = User::where('email', $r->email)->first();
        if (!$user || !Hash::check($r->password, $user->password)) {
            return response(['message' => 'Login gagal'], 401);
        }
        $token = $user->createToken('shopwise')->plainTextToken;
        return response(['token' => $token]);
    }

    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();
        return response(['message' => 'Logout berhasil']);
    }
}
