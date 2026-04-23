<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\Loan;
use App\Models\User;

class PetugasController extends Controller
{
    // Dashboard Petugas
    public function dashboard()
    {
        // Statistik
        $totalEquipment = Equipment::count();
        $availableEquipment = Equipment::where('stock', '>', 0)->count();
        $activeLoans = Loan::where('status', 'active')->count();
        $overdueLoans = Loan::where('status', 'active')
            ->where('due_date', '<', now())
            ->count();

        return view('petugas.dashboard', compact(
            'totalEquipment',
            'availableEquipment',
            'activeLoans',
            'overdueLoans'
        ));
    }

    // Data Peralatan
    public function peralatan()
    {
        $equipment = Equipment::paginate(10);
        return view('petugas.peralatan', compact('equipment'));
    }

    // Peminjaman
    public function peminjaman()
    {
        $loans = Loan::with('loanItems.equipment', 'borrower')
            ->where('status', 'active')
            ->orderBy('loan_date', 'desc')
            ->paginate(10);

        return view('petugas.peminjaman', compact('loans'));
    }

    // Pengembalian
    public function pengembalian()
    {
        $loans = Loan::with('loanItems.equipment', 'borrower')
            ->where('status', 'active')
            ->where('due_date', '<=', now())
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('petugas.pengembalian', compact('loans'));
    }
}
