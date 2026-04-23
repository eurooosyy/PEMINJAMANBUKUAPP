<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrower;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['borrower.user'])->paginate(10);
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswaRoleId = Role::where('name', 'Siswa')->first()->id ?? 0;
        $siswaUsers = User::where('role_id', $siswaRoleId)->get();

        foreach ($siswaUsers as $user) {
            Borrower::firstOrCreate(['user_id' => $user->id], [
                'nis' => 'NIS-' . $user->id,
                'class' => 'XII IPA 1',
                'phone' => '08123456789',
            ]);
        }

        $borrowers = Borrower::with('user')->get();
        $books = Book::where('stock', '>', 0)->orderBy('title')->get();

        return view('loans.create', compact('borrowers', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'borrower_id' => 'required|exists:borrowers,id',
            'equipment_ids' => 'required|array|min:1',
            'equipment_ids.*' => 'exists:books,id',
            'due_date' => 'required|date|after:today',
        ]);

        $borrower = Borrower::findOrFail($request->borrower_id);
        $activeLoans = Loan::where('borrower_id', $request->borrower_id)
            ->where('status', 'active')
            ->count();

        if ($activeLoans >= 3) {
            return back()->with('error', 'Peminjam sudah memiliki 3 peminjaman aktif.')->withInput();
        }

        foreach ($request->equipment_ids as $bookId) {
            $book = Book::find($bookId);
            if (!$book || $book->stock <= 0) {
                return back()->with('error', 'Buku "' . ($book?->title ?? 'Unknown') . '" tidak tersedia.')->withInput();
            }
        }

        $loan = Loan::create([
            'borrower_id' => $request->borrower_id,
            'petugas_id' => Auth::id(),
            'loan_date' => now(),
            'due_date' => $request->due_date,
            'status' => 'active',
        ]);

        foreach ($request->equipment_ids as $bookId) {
            LoanItem::create([
                'loan_id' => $loan->id,
                'book_id' => $bookId,
                'quantity' => 1,
            ]);

            $book = Book::find($bookId);
            $book?->decrement('stock');
        }

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Loan $loan)
    {
        $loan->load(['borrower.user', 'petugas', 'loanItems.book']);
        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loan $loan)
    {
        if ($loan->status === 'dikembalikan') {
            return redirect()->route('loans.index')->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diedit.');
        }

        $borrowers = Borrower::with('user')->get();
        $books = Book::all();
        return view('loans.edit', compact('loan', 'borrowers', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loan $loan)
    {
        if ($loan->status === 'dikembalikan') {
            return redirect()->route('loans.index')->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat diupdate.');
        }

        $request->validate([
            'due_date' => 'required|date',
        ]);

        $loan->update([
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Loan $loan)
    {
        if ($loan->status === 'dikembalikan') {
            return redirect()->route('loans.index')->with('error', 'Peminjaman yang sudah dikembalikan tidak dapat dihapus.');
        }

        foreach ($loan->loanItems as $item) {
            $item->book?->increment('stock');
        }

        $loan->delete();

        return redirect()->route('loans.index')->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    /**
     * Return a loan.
     */
    public function returnLoan(Request $request, Loan $loan)
    {
        if ($loan->status === 'dikembalikan') {
            return redirect()->route('loans.index')->with('error', 'Peminjaman sudah dikembalikan.');
        }

        $returnDate = now();
        $status = $returnDate->isAfter($loan->due_date) ? 'terlambat' : 'dikembalikan';

        $loan->update([
            'return_date' => $returnDate,
            'status' => $status,
        ]);

        foreach ($loan->loanItems as $item) {
            $item->book?->increment('stock');
        }

        return redirect()->route('loans.index')->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Approve loan extension request
     */
    public function approveExtension(Request $request, Loan $loan)
    {
        $extension = $loan->extensions()->where('status', 'pending')->first();
        if (!$extension) {
            return back()->with('error', 'Tidak ada pengajuan perpanjangan pending.');
        }

        $extension->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes ?? 'Disetujui oleh admin',
        ]);

        // Extend due date
        $loan->update(['due_date' => $extension->new_due_date]);

        // Send notification to student
        $borrowerUserId = $loan->borrower->user_id;
        \App\Models\StudentNotification::create([
            'user_id' => $borrowerUserId,
            'title' => 'Perpanjangan Peminjaman Disetujui',
            'message' => 'Pengajuan perpanjangan peminjaman #' . $loan->id . ' telah disetujui. Tanggal kembali diperpanjang hingga ' . \Carbon\Carbon::parse($extension->new_due_date)->format('d/m/Y') . '.',
            'type' => 'extension_approved',
        ]);

        return back()->with('success', 'Perpanjangan berhasil disetujui.');
    }

    /**
     * Reject loan extension request
     */
    public function pendingExtensions()
    {
        $pendingExtensions = Loan::with(['extensions' => function ($q) {
            $q->where('status', 'pending');
        }, 'loanItems.book', 'borrower.user'])->whereHas('extensions', function ($q) {
            $q->where('status', 'pending');
        })->paginate(15);
        return view('admin.extensions', compact('pendingExtensions'));
    }

    public function rejectExtension(Request $request, Loan $loan)
    {

        $extension = $loan->extensions()->where('status', 'pending')->first();
        if (!$extension) {
            return back()->with('error', 'Tidak ada pengajuan perpanjangan pending.');
        }

        $extension->update([
            'status' => 'rejected',
            'admin_notes' => $request->reject_reason ?? 'Ditolak oleh admin',
        ]);

        // Send notification to student
        $borrowerUserId = $loan->borrower->user_id;
        \App\Models\StudentNotification::create([
            'user_id' => $borrowerUserId,
            'title' => 'Perpanjangan Peminjaman Ditolak',
            'message' => 'Pengajuan perpanjangan peminjaman #' . $loan->id . ' ditolak. Alasan: ' . ($request->reject_reason ?? 'Tidak disetujui'),
            'type' => 'extension_rejected',
        ]);

        return back()->with('error', 'Perpanjangan berhasil ditolak.');
    }
}
