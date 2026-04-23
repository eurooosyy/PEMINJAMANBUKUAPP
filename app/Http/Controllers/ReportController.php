<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\User;
use App\Models\Equipment;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Laporan Peminjaman - All loans with details
     */
    public function borrowingReport()
    {
        $loans = Loan::with(['borrower', 'petugas', 'loanItems.equipment'])
            ->orderBy('loan_date', 'desc')
            ->paginate(20);

        // Statistics
        $stats = [
            'total_loans' => Loan::count(),
            'active_loans' => Loan::where('status', 'active')->count(),
            'completed_loans' => Loan::where('status', 'returned')->count(),
            'overdue_loans' => Loan::where('status', 'active')
                ->where('due_date', '<', Carbon::now())
                ->count(),
        ];

        return view('reports.borrowing', compact('loans', 'stats'));
    }

    /**
     * Laporan Keterlambatan - Overdue loans
     */
    public function overdueReport()
    {
        $overdueLoans = Loan::with(['borrower', 'petugas', 'loanItems.equipment'])
            ->where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->orderBy('due_date', 'asc')
            ->paginate(20);

        // Calculate fines
        $overdueLoans->getCollection()->transform(function ($loan) {
            $daysOverdue = Carbon::now()->diffInDays($loan->due_date);
            $loan->days_overdue = $daysOverdue;
            $loan->fine_amount = $daysOverdue * 5000; // Rp 5000 per hari
            return $loan;
        });

        $stats = [
            'total_overdue' => Loan::where('status', 'active')
                ->where('due_date', '<', Carbon::now())
                ->count(),
            'total_fine' => Loan::where('status', 'active')
                ->where('due_date', '<', Carbon::now())
                ->get()
                ->sum(function ($loan) {
                    return Carbon::now()->diffInDays($loan->due_date) * 5000;
                }),
            'borrowers_with_overdue' => Loan::where('status', 'active')
                ->where('due_date', '<', Carbon::now())
                ->distinct()
                ->count('borrower_id'),
        ];

        return view('reports.overdue', compact('overdueLoans', 'stats'));
    }

    /**
     * Laporan Peralatan Populer - Most borrowed equipment
     */
    public function popularEquipmentReport()
    {
        $popularEquipment = Equipment::withCount('loanItems')
            ->with(['loanItems' => function ($query) {
                $query->with('loan')->latest('created_at');
            }])
            ->orderBy('loan_items_count', 'desc')
            ->paginate(20);

        // Statistics
        $stats = [
            'total_equipment' => Equipment::count(),
            'total_borrowed' => LoanItem::count(),
            'most_borrowed_count' => Equipment::withCount('loanItems')
                ->orderBy('loan_items_count', 'desc')
                ->first()
                ->loan_items_count ?? 0,
            'average_borrows_per_equipment' => Equipment::count() > 0 ? round(LoanItem::count() / Equipment::count(), 2) : 0,
        ];

        return view('reports.popular-equipment', compact('popularEquipment', 'stats'));
    }

    /**
     * Laporan Statistik Umum - Overall statistics
     */
    public function statisticsReport()
    {
        $now = Carbon::now();
        $thirtyDaysAgo = $now->copy()->subDays(30);
        $ninetyDaysAgo = $now->copy()->subDays(90);

        // Monthly borrowing data for chart
        $monthlyBorrows = Loan::selectRaw('MONTH(loan_date) as month, COUNT(*) as count')
            ->where('loan_date', '>=', $now->copy()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $stats = [
            'total_equipment' => Equipment::count(),
            'total_users' => User::count(),
            'total_loans' => Loan::count(),
            'total_loans_30days' => Loan::where('loan_date', '>=', $thirtyDaysAgo)->count(),
            'total_loans_90days' => Loan::where('loan_date', '>=', $ninetyDaysAgo)->count(),

            // Loan status breakdown
            'active_loans' => Loan::where('status', 'active')->count(),
            'completed_loans' => Loan::where('status', 'returned')->count(),
            'overdue_loans' => Loan::where('status', 'active')
                ->where('due_date', '<', Carbon::now())
                ->count(),

            'equipment_in_stock' => Equipment::where('stock', '>', 0)->count(),
            'equipment_out_of_stock' => Equipment::where('stock', 0)->count(),
            'total_equipment_borrowed' => LoanItem::sum('quantity'),

            // Borrower statistics
            'active_borrowers' => Loan::distinct()->count('borrower_id'),
            'top_borrower_loans' => Loan::selectRaw('borrower_id, COUNT(*) as loan_count')
                ->groupBy('borrower_id')
                ->orderBy('loan_count', 'desc')
                ->first()
                ->loan_count ?? 0,

            'monthly_borrows' => $monthlyBorrows,
        ];

        // Top 5 borrowers
        $topBorrowers = User::withCount('loansAsBorrower')
            ->orderBy('loans_as_borrower_count', 'desc')
            ->limit(5)
            ->get();

        // Top 5 equipment
        $topEquipment = Equipment::withCount('loanItems')
            ->orderBy('loan_items_count', 'desc')
            ->limit(5)
            ->get();

        return view('reports.statistics', compact('stats', 'topBorrowers', 'topEquipment'));
    }

    /**
     * Laporan Pengembalian - Returned equipment
     */
    public function returnReport()
    {
        $returnedLoans = Loan::with(['borrower', 'petugas', 'loanItems.equipment'])
            ->where('status', 'returned')
            ->orderBy('return_date', 'desc')
            ->paginate(20);

        $stats = [
            'total_returned' => Loan::where('status', 'returned')->count(),
            'returned_30days' => Loan::where('status', 'returned')
                ->where('return_date', '>=', Carbon::now()->subDays(30))
                ->count(),
            'average_loan_duration' => Loan::where('status', 'returned')->exists() ? round(
                Loan::where('status', 'returned')
                    ->selectRaw('AVG(DATEDIFF(return_date, loan_date)) as avg_days')
                    ->first()
                    ->avg_days ?? 0,
                1
            ) : 0,
        ];

        return view('reports.returns', compact('returnedLoans', 'stats'));
    }

    /**
     * Laporan Peminjam - Borrower details
     */
    public function borrowerReport()
    {
        $borrowers = User::where('role_id', function ($query) {
            $query->select('id')->from('roles')->where('name', 'Siswa');
        })
            ->withCount('loansAsBorrower')
            ->with(['loansAsBorrower' => function ($query) {
                $query->latest()->limit(3);
            }])
            ->paginate(20);

        $stats = [
            'total_borrowers' => User::where('role_id', function ($query) {
                $query->select('id')->from('roles')->where('name', 'Siswa');
            })->count(),
            'active_borrowers' => Loan::where('status', 'active')
                ->distinct()
                ->count('borrower_id'),
            'total_loans_all_borrowers' => Loan::count(),
        ];

        return view('reports.borrowers', compact('borrowers', 'stats'));
    }
}
