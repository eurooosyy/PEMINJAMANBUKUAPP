<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Coba login dengan email & password
        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirect sesuai role
            if ($user->role && $user->role->name === 'Admin') {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Login berhasil! Selamat datang Admin.');
            } elseif ($user->role && $user->role->name === 'Petugas') {
                return redirect()->intended(route('petugas.dashboard'))->with('success', 'Login berhasil! Selamat datang Petugas.');
            } elseif ($user->role && $user->role->name === 'User') {
                return redirect()->intended(route('siswa.dashboard'))->with('success', 'Login berhasil! Selamat datang di Sistem Peminjaman Buku.');
            } elseif ($user->role && $user->role->name === 'Siswa') {
                return redirect()->intended(route('siswa.dashboard'))->with('success', 'Login berhasil! Selamat datang Siswa.');
            }

            // Default redirect jika role tidak ditemukan
            return redirect()->intended(route('siswa.dashboard'))->with('success', 'Login berhasil!');
        }

        // Login gagal - kembali ke login dengan error message
        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password tidak sesuai. Silakan coba lagi.');
    }
    public function showLogin()
    {
        return view('auth.login');
    }
    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */
    public function showRegister()
    {
        $roles = Role::all();
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role_id' => 'required|exists:roles,id'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }
}
