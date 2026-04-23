<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| HALAMAN AWAL
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/setup', function () {
    return view('setup');
});

/*
|--------------------------------------------------------------------------
| DEBUG (Temporary - untuk troubleshoot)
|--------------------------------------------------------------------------
*/
Route::get('/debug/petugas-user', function () {
    $petugas = \App\Models\User::where('email', 'petugas@mail.com')->first();
    $roles = \App\Models\Role::all();

    return response()->json([
        'petugas_user' => $petugas ? [
            'id' => $petugas->id,
            'name' => $petugas->name,
            'email' => $petugas->email,
            'role_id' => $petugas->role_id,
            'role_name' => $petugas->role?->name ?? 'NO ROLE'
        ] : 'PETUGAS USER NOT FOUND',
        'all_roles' => $roles,
        'all_users' => \App\Models\User::with('role')->get(),
    ]);
});

Route::get('/setup-seeder', function () {
    try {
        // Run seeders
        \Illuminate\Support\Facades\Artisan::call('db:seed');

        // Check result
        $petugas = \App\Models\User::where('email', 'petugas@mail.com')->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Seeder sudah dijalankan',
            'petugas' => [
                'email' => $petugas->email ?? 'not found',
                'role' => $petugas->role?->name ?? 'no role',
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug', function () {
    $users = \App\Models\User::with('role')->get();
    $roles = \App\Models\Role::all();
    $loans = \App\Models\Loan::with('borrower', 'loanItems.equipment')->get();
    $loanItems = \App\Models\LoanItem::with('loan', 'equipment')->get();

    return response()->json([
        'roles' => $roles,
        'users' => $users,
        'loans' => $loans,
        'loanItems' => $loanItems,
        'app_key' => config('app.key') ? 'SET' : 'NOT SET',
        'session_driver' => config('session.driver'),
    ]);
});

Route::get('/debug/fix-petugas', function () {
    try {
        // Ensure roles exist
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'Admin']);
        $petugasRole = \App\Models\Role::firstOrCreate(['name' => 'Petugas']);
        $siswaRole = \App\Models\Role::firstOrCreate(['name' => 'Siswa']);
        $userRole = \App\Models\Role::firstOrCreate(['name' => 'User']);

        // Create petugas user
        $petugas = \App\Models\User::firstOrCreate(
            ['email' => 'petugas@mail.com'],
            [
                'name' => 'Petugas User',
                'password' => bcrypt('12345678'),
                'role_id' => $petugasRole->id
            ]
        );

        // Update any petugas user without a proper role
        \App\Models\User::whereNull('role_id')->update(['role_id' => $siswaRole->id]);

        return response()->json([
            'success' => true,
            'message' => 'Database fixed!',
            'roles' => \App\Models\Role::all(),
            'petugas_user' => $petugas,
            'all_users' => \App\Models\User::with('role')->get()
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ROUTE INCLUDES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/admin.php';
require __DIR__ . '/petugas.php';
require __DIR__ . '/siswa.php';
require __DIR__ . '/books.php';

/*
|--------------------------------------------------------------------------
| SHARED ROUTES (Catalog & Loans Management)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Siswa'])->group(function () {
    Route::get('/catalog', [BooksController::class, 'catalog'])->name('catalog');
});

Route::middleware(['auth', 'role:Admin,Petugas'])->group(function () {
    Route::resource('loans', \App\Http\Controllers\LoanController::class);
    Route::post('/loans/{loan}/return', [\App\Http\Controllers\LoanController::class, 'returnLoan'])->name('loans.return');

    // Legacy equipment URLs redirect to unified books routes
    Route::redirect('/equipment', '/books');
    Route::redirect('/equipment/create', '/books/create');
    Route::get('/equipment/{book}/edit', function (\App\Models\Book $book) {
        return redirect()->route('books.edit', $book);
    })->name('equipment.edit.legacy');
});
