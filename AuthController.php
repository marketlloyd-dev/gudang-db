<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $messages = [
            'email.unique' => 'Alamat email ini sudah terdaftar!',
            'password.min' => 'Password minimal harus 6 karakter!',
        ];

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ], $messages);

        // List email yang diizinkan jadi admin
        $adminEmails = ['patihaparhan@gmail.com', 'admin@gudang.com'];

        // Tentukan role berdasarkan email
        $role = in_array($request->email, $adminEmails) ? 'admin' : 'anggota';

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role
        ]);

        return response()->json([
            'message' => 'Registrasi Berhasil sebagai ' . $role,
            'user'    => $user
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        // Cek apakah akun terdaftar
        if (!$user) {
            return response()->json([
                'message' => 'Akun anda tidak terdaftar!'
            ], 404);
        }

        // Cek password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Password yang anda masukkan salah!'
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }
}