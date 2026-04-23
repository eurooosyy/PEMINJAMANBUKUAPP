<?php

use Illuminate\Support\Facades\Route;

// Add this inside web.php, in the DEBUG section

Route::get('/debug/roles', function () {
    $roles = \App\Models\Role::all();
    $users = \App\Models\User::with('role')->get();

    return response()->json([
        'roles' => $roles->map(fn($r) => ['id' => $r->id, 'name' => $r->name]),
        'users' => $users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'role_id' => $u->role_id,
            'role_name' => $u->role?->name ?? 'NO ROLE'
        ])
    ]);
});

Route::get('/debug/seed', function () {
    // Run seeder
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\DatabaseSeeder']);
    return 'Seeder executed! Check /debug/roles';
});
