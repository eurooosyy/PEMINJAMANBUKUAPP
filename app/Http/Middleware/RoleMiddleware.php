<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        // kalau belum login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        if (!$user->role) {
            return redirect('/login')->with('error', 'Role tidak ditemukan.');
        }

        $userRole = $user->role->name;

        // Support untuk multiple roles yang dipisahkan dengan koma
        // Format: role:Admin,Petugas,Siswa
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, array_map('trim', explode(',', $role)));
        }

        // kalau role user ADA di daftar role yang diizinkan → lanjut
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // ❌ kalau tidak sesuai, arahkan ke dashboard sesuai rolenya
        if ($userRole === 'Admin') {
            return redirect('/admin/dashboard');
        }

        if ($userRole === 'Petugas') {
            return redirect('/petugas/dashboard');
        }

        if ($userRole === 'Siswa') {
            return redirect('/siswa/dashboard');
        }

        // fallback
        return redirect('/login');
    }
}
