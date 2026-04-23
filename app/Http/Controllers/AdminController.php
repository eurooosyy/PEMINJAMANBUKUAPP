<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Models\Equipment; removed

use App\Models\Book;
use App\Models\Loan;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard Admin
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPetugas = User::whereHas('role', function ($q) {
            $q->where('name', 'Petugas');
        })->count();
        $totalBooks = Book::count();
        $activeLoans = Loan::where('status', 'active')->count();
        $overdueLoans = Loan::where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->count();
        $returnedToday = Loan::whereDate('return_date', Carbon::today())
            ->where('status', 'returned')
            ->count();
        $categoryStats = collect();
        if (Book::hasCategoryColumn()) {
            $categoryStats = Book::selectRaw('COALESCE(category, "Lainnya") as category, COUNT(*) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->limit(6)
                ->get();
        }
        $topCategory = $categoryStats->first();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPetugas',
            'totalBooks',
            'activeLoans',
            'overdueLoans',
            'returnedToday',
            'categoryStats',
            'topCategory'
        ));
    }

    // List all petugas
    public function index()
    {
        $petugases = User::whereHas('role', function ($q) {
            $q->where('name', 'Petugas');
        })->with('role')->paginate(10);

        return view('admin.petugas.index', compact('petugases'));
    }

    // Show create form
    public function create()
    {
        return view('admin.petugas.create');
    }

    // Store new petugas
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $petugasRole = Role::where('name', 'Petugas')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'role_id' => $petugasRole->id,
        ]);

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan.');
    }

    // Show petugas details
    public function show(User $petugas)
    {
        return view('admin.petugas.show', compact('petugas'));
    }

    // Show edit form
    public function edit(User $petugas)
    {
        return view('admin.petugas.edit', compact('petugas'));
    }

    // Update petugas
    public function update(Request $request, User $petugas)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $petugas->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $petugas->update($data);

        return redirect()->route('petugas.show', $petugas)->with('success', 'Petugas berhasil diperbarui.');
    }

    // Delete petugas
    public function destroy(User $petugas)
    {
        $petugas->delete();
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus.');
    }
}
