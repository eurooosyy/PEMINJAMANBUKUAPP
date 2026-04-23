<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use App\Models\User;

class DashboardController extends Controller
{
    // HALAMAN DASHBOARD UTAMA (setelah login)
    public function index()
    {
        $user = auth()->user();

        // Data untuk dashboard user
        $totalBooks = Book::count();
        $availableBooks = Book::where('stock', '>', 0)->count();
        $userActiveLoans = Loan::where('borrower_id', $user->id)->where('status', 'active')->count();
        $userTotalLoans = Loan::where('borrower_id', $user->id)->count();

        // Peralatan terbaru (5 terakhir)
        $recentBooks = Book::orderBy('created_at', 'desc')->take(5)->get();

        // Peminjaman aktif user
        $activeLoans = Loan::with('loanItems.equipment')
            ->where('borrower_id', $user->id)
            ->where('status', 'active')->get();

        return view('dashboard.index', compact(
            'totalEquipment',
            'availableEquipment',
            'userActiveLoans',
            'userTotalLoans',
            'recentEquipment',
            'activeLoans'
        ));
    }

    // HALAMAN DATA BUKU
    public function buku()
    {
        return view('dashboard.buku');
    }

    // HALAMAN DATA PEMINJAMAN
    public function peminjaman()
    {
        return view('dashboard.peminjaman');
    }

    // HALAMAN DATA PENGEMBALIAN
    public function pengembalian()
    {
        return view('dashboard.pengembalian');
    }
}