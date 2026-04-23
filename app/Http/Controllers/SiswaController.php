<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReservation;
use App\Models\BookReview;
use App\Models\Borrower;
use App\Models\Loan;
use App\Models\LoanExtension;
use App\Models\LoanItem;
use App\Models\StudentNotification;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    private const CLASS_OPTIONS = [
        'X IPA 1',
        'X IPA 2',
        'X IPS 1',
        'X IPS 2',
        'XI IPA 1',
        'XI IPA 2',
        'XI IPS 1',
        'XI IPS 2',
        'XII IPA 1',
        'XII IPA 2',
        'XII IPS 1',
        'XII IPS 2',
    ];

    private function getBorrowerForCurrentUser(): Borrower
    {
        $user = auth()->user();

        return Borrower::firstOrCreate(
            ['user_id' => $user->id],
            [
                'nis' => 'NIS-' . $user->id,
                'class' => '-',
                'phone' => '-',
            ]
        );
    }

    private function getBorrowerIdForCurrentUser(): int
    {
        return $this->getBorrowerForCurrentUser()->id;
    }

    private function classOptions(): array
    {
        return self::CLASS_OPTIONS;
    }

    private function calculateDueDate(int $durationDays)
    {
        return now()->addDays($durationDays)->endOfDay();
    }

    // Dashboard Siswa
    public function dashboard()
    {
        $user = auth()->user();
        $borrower = $this->getBorrowerForCurrentUser();
        $borrowerId = $this->getBorrowerIdForCurrentUser();

        $totalBooks = Book::where('stock', '>', 0)->count();

        $activeLoansCount = Loan::where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->count();

        $totalLoans = Loan::where('borrower_id', $borrowerId)->count();

        $activeLoans = Loan::with('loanItems.book')
            ->where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->orderBy('loan_date', 'desc')
            ->get();

        $overdueBooks = Loan::with('loanItems.book')
            ->where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->count();

        $popularBooks = Book::withCount(['loanItems as borrow_count' => function ($query) {
            $query->join('loans', 'loan_items.loan_id', '=', 'loans.id')
                ->whereIn('loans.status', ['returned', 'active']);
        }])
            ->with('loanItems.loan')
            ->having('borrow_count', '>', 0)
            ->orderBy('borrow_count', 'desc')
            ->limit(6)
            ->get();

        $recentBooks = Book::orderBy('created_at', 'desc')->with('reviews')->limit(5)->get();

        $upcomingDueDates = Loan::with('loanItems.book')
            ->where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->orderBy('due_date', 'asc')
            ->get();

        $monthlyStats = [
            'borrowed' => Loan::where('borrower_id', $borrowerId)
                ->whereMonth('loan_date', now()->month)
                ->whereYear('loan_date', now()->year)
                ->count(),
            'returned' => Loan::where('borrower_id', $borrowerId)
                ->where('status', 'returned')
                ->whereMonth('return_date', now()->month)
                ->whereYear('return_date', now()->year)
                ->count(),
        ];

        $announcements = [
            [
                'title' => 'Perpanjangan Jam Peminjaman',
                'content' => 'Ruang perpustakaan akan buka lebih lama mulai bulan depan.',
                'date' => '2026-04-15',
            ],
            [
                'title' => 'Buku Baru Tiba',
                'content' => 'Koleksi buku terbaru sudah tersedia di perpustakaan.',
                'date' => '2026-04-10',
            ],
        ];

        return view('siswa.dashboard', [
            'borrower' => $borrower,
            'totalBooks' => $totalBooks,
            'activeLoansCount' => $activeLoansCount,
            'activeLoans' => $activeLoans,
            'totalLoans' => $totalLoans,
            'overdueBooks' => $overdueBooks,
            'popularBooks' => $popularBooks,
            'recentBooks' => $recentBooks,
            'upcomingDueDates' => $upcomingDueDates,
            'monthlyStats' => $monthlyStats,
            'announcements' => $announcements,
        ]);
    }

    // Riwayat Peminjaman
    public function riwayatPeminjaman()
    {
        $borrowerId = $this->getBorrowerIdForCurrentUser();

        $allLoans = Loan::with('loanItems.book')
            ->where('borrower_id', $borrowerId)
            ->orderBy('loan_date', 'desc')
            ->paginate(10);

        $loanSummary = [
            'total' => Loan::where('borrower_id', $borrowerId)->count(),
            'active' => Loan::where('borrower_id', $borrowerId)->where('status', 'active')->count(),
            'returned' => Loan::where('borrower_id', $borrowerId)->where('status', 'returned')->count(),
        ];

        return view('siswa.riwayat', compact('allLoans', 'loanSummary'));
    }

    // Pinjam Buku
    public function pinjam(Request $request, Book $book = null)
    {
        try {
            $user = auth()->user();
            $borrowerId = $this->getBorrowerIdForCurrentUser();

            if (!$user) {
                return redirect('/login')->with('error', 'Anda harus login terlebih dahulu.');
            }

            $durationDays = 7;
            $quantity = 1;

            if ($book === null) {
                if (!$request->has('book_id')) {
                    return back()->with('error', 'ID buku tidak ditemukan. Silakan refresh halaman dan coba lagi.');
                }

                $validated = $request->validate([
                    'book_id' => 'required|integer|exists:books,id',
                    'duration_days' => 'nullable|integer|min:1|max:30',
                    'quantity' => 'nullable|integer|min:1',
                ]);

                $book = Book::findOrFail($validated['book_id']);
                $durationDays = (int) ($validated['duration_days'] ?? 7);
                $quantity = (int) ($validated['quantity'] ?? 1);
            }

            if (!$book || $book->stock <= 0) {
                return back()->with('error', 'Buku "' . ($book?->title ?? 'Unknown') . '" tidak tersedia.');
            }

            if ($quantity > $book->stock) {
                return back()->with('error', 'Stok buku "' . $book->title . '" tidak mencukupi untuk jumlah yang dipilih.');
            }

            $loan = Loan::create([
                'borrower_id' => $borrowerId,
                'loan_date' => now(),
                'due_date' => $this->calculateDueDate($durationDays),
                'status' => 'active',
            ]);

            LoanItem::create([
                'loan_id' => $loan->id,
                'book_id' => $book->id,
                'quantity' => $quantity,
            ]);

            $book->decrement('stock', $quantity);

            Log::info('Buku berhasil dipinjam', [
                'user_id' => $user->id,
                'borrower_id' => $borrowerId,
                'book_id' => $book->id,
                'book_title' => $book->title,
                'loan_id' => $loan->id,
                'quantity' => $quantity,
            ]);

            return redirect()
                ->route('siswa.riwayat')
                ->with('success', 'Buku "' . $book->title . '" berhasil dipinjam sebanyak ' . $quantity . ' eksemplar selama ' . $durationDays . ' hari. Data sudah masuk ke riwayat peminjaman.');
        } catch (\Exception $e) {
            Log::error('Error pinjam buku: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'book_id' => $book?->id ?? null,
                'exception' => $e,
            ]);

            return back()->with('error', 'Gagal meminjam buku: ' . $e->getMessage());
        }
    }

    // Kembalikan Buku
    public function kembalikan(Request $request)
    {
        try {
            $validated = $request->validate([
                'loan_id' => 'required|exists:loans,id',
            ]);

            $user = auth()->user();
            $borrowerId = $this->getBorrowerIdForCurrentUser();
            $loan = Loan::with('loanItems.book')->findOrFail($validated['loan_id']);

            if ((int) $loan->borrower_id !== $borrowerId) {
                return back()->with('error', 'Anda tidak memiliki akses ke peminjaman ini.');
            }

            if ($loan->status !== 'active') {
                return back()->with('error', 'Peminjaman ini sudah dalam status "' . $loan->status . '".');
            }

            foreach ($loan->loanItems as $item) {
                $item->book?->increment('stock', $item->quantity ?? 1);
            }

            $loan->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);

            Log::info('Buku berhasil dikembalikan', [
                'user_id' => $user->id,
                'borrower_id' => $borrowerId,
                'loan_id' => $loan->id,
                'item_count' => $loan->loanItems->count(),
            ]);

            return back()->with('success', 'Buku berhasil dikembalikan. Terima kasih telah meminjam.');
        } catch (\Exception $e) {
            Log::error('Error kembalikan buku: ' . $e->getMessage());

            return back()->with('error', 'Gagal mengembalikan buku: ' . $e->getMessage());
        }
    }

    // Jelajahi Buku
    public function jelajahi(Request $request)
    {
        $query = Book::query();
        $hasCategoryColumn = Book::hasCategoryColumn();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('author', 'like', '%' . $search . '%')
                    ->orWhere('publisher', 'like', '%' . $search . '%');

                if (Book::hasCategoryColumn()) {
                    $q->orWhere('category', 'like', '%' . $search . '%');
                }
            });
        }

        $selectedCategory = $request->input('category', $request->input('kategori'));
        if ($hasCategoryColumn && !empty($selectedCategory)) {
            $query->where('category', $selectedCategory);
        }

        if ($request->has('available') && $request->available == '1') {
            $query->where('stock', '>', 0);
        }

        $books = $query->orderBy('title')->paginate(12)->appends($request->query());
        $categories = Book::categoryOptions();

        return view('siswa.jelajahi', compact('books', 'categories', 'hasCategoryColumn'));
    }

    // Tampilkan Form Tambah Peminjaman
    public function tambahPeminjaman()
    {
        $borrower = $this->getBorrowerForCurrentUser();
        $borrowerId = $borrower->id;
        $equipment = Book::where('stock', '>', 0)->get();
        $categories = Book::categoryOptions();
        $classOptions = $this->classOptions();

        $activeLoans = Loan::where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->count();

        return view('siswa.tambah-peminjaman', [
            'borrower' => $borrower,
            'equipment' => $equipment,
            'categories' => $categories,
            'classOptions' => $classOptions,
            'activeLoans' => $activeLoans,
        ]);
    }

    // Pinjam Multiple Buku
    public function pinjamMultiple(Request $request)
    {
        try {
            $user = auth()->user();
            $borrower = $this->getBorrowerForCurrentUser();
            $borrowerId = $borrower->id;

            $validated = $request->validate([
                'books' => 'required|array|min:1',
                'books.*.id' => 'required|integer|exists:books,id',
                'books.*.quantity' => 'required|integer|min:1',
                'borrower_class' => 'required|string|in:' . implode(',', $this->classOptions()),
                'duration_days' => 'required|integer|min:1|max:30',
            ]);

            if ($borrower->class !== $validated['borrower_class']) {
                $borrower->update([
                    'class' => $validated['borrower_class'],
                ]);
            }

            $selectedBooks = collect($validated['books']);
            $bookIds = $selectedBooks->pluck('id')->all();
            $durationDays = (int) $validated['duration_days'];
            $books = Book::whereIn('id', $bookIds)->get();

            if ($books->count() !== count($bookIds)) {
                return back()->with('error', 'Beberapa buku tidak ditemukan di database.');
            }

            $unavailableBooks = [];
            foreach ($books as $item) {
                $requestedQuantity = (int) $selectedBooks->firstWhere('id', $item->id)['quantity'];

                if ($item->stock < $requestedQuantity) {
                    $unavailableBooks[] = $item->title . ' (tersedia ' . $item->stock . ', diminta ' . $requestedQuantity . ')';
                }
            }

            if (!empty($unavailableBooks)) {
                return back()->with('error', 'Buku berikut tidak tersedia: ' . implode(', ', $unavailableBooks));
            }

            DB::beginTransaction();

            try {
                $loan = Loan::create([
                    'borrower_id' => $borrowerId,
                    'loan_date' => now(),
                    'due_date' => $this->calculateDueDate($durationDays),
                    'status' => 'active',
                ]);

                $borrowedBooks = [];

                foreach ($books as $item) {
                    $requestedQuantity = (int) $selectedBooks->firstWhere('id', $item->id)['quantity'];

                    LoanItem::create([
                        'loan_id' => $loan->id,
                        'book_id' => $item->id,
                        'quantity' => $requestedQuantity,
                    ]);

                    $item->decrement('stock', $requestedQuantity);
                    $borrowedBooks[] = $item->title . ' x' . $requestedQuantity;
                }

                DB::commit();

                Log::info('Multiple buku berhasil dipinjam', [
                    'user_id' => $user->id,
                    'borrower_id' => $borrowerId,
                    'book_count' => count($borrowedBooks),
                    'book_titles' => implode(', ', $borrowedBooks),
                    'loan_id' => $loan->id,
                    'duration_days' => $durationDays,
                ]);

                $message = count($borrowedBooks) . ' buku berhasil dipinjam selama ' . $durationDays . ' hari.';
                $message .= ' Data sudah masuk ke riwayat peminjaman: ' . implode(', ', $borrowedBooks);

                return redirect()->route('siswa.riwayat')->with('success', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error pinjam multiple buku: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'exception' => $e,
            ]);

            return back()->with('error', 'Gagal meminjam buku: ' . $e->getMessage());
        }
    }

    // Profil Siswa
    public function profile()
    {
        $user = auth()->user();
        $borrowerId = $this->getBorrowerIdForCurrentUser();

        $totalLoans = Loan::where('borrower_id', $borrowerId)->count();
        $activeLoans = Loan::where('borrower_id', $borrowerId)->where('status', 'active')->count();
        $returnedLoans = Loan::where('borrower_id', $borrowerId)->where('status', 'returned')->count();
        $overdueLoans = Loan::where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->count();

        return view('siswa.profil', [
            'user' => $user,
            'totalLoans' => $totalLoans,
            'activeLoans' => $activeLoans,
            'returnedLoans' => $returnedLoans,
            'overdueLoans' => $overdueLoans,
        ]);
    }

    // Update Profil Siswa
    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . auth()->id(),
            ]);

            auth()->user()->update($validated);

            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error update profil: ' . $e->getMessage());

            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    // Wishlist - Lihat Daftar
    public function wishlist()
    {
        $user = auth()->user();
        $wishlist = Wishlist::with('book')->where('user_id', $user->id)->paginate(10);

        $wishlistSummary = [
            'total' => Wishlist::where('user_id', $user->id)->count(),
            'available' => Wishlist::where('user_id', $user->id)
                ->whereHas('book', fn ($query) => $query->where('stock', '>', 0))
                ->count(),
            'out_of_stock' => Wishlist::where('user_id', $user->id)
                ->whereHas('book', fn ($query) => $query->where('stock', '<=', 0))
                ->count(),
        ];

        return view('siswa.wishlist', compact('wishlist', 'wishlistSummary'));
    }

    // Wishlist - Tambah
    public function addToWishlist(Request $request)
    {
        try {
            $validated = $request->validate([
                'book_id' => 'required|exists:books,id',
            ]);

            $user = auth()->user();
            $book = Book::findOrFail($validated['book_id']);

            $existing = Wishlist::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->first();

            if ($existing) {
                return back()->with('info', 'Buku ini sudah ada di wishlist Anda.');
            }

            Wishlist::create([
                'user_id' => $user->id,
                'book_id' => $validated['book_id'],
            ]);

            return back()->with('success', 'Buku "' . $book->title . '" ditambahkan ke wishlist.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan ke wishlist: ' . $e->getMessage());
        }
    }

    // Wishlist - Hapus
    public function removeFromWishlist($bookId)
    {
        try {
            $user = auth()->user();

            Wishlist::where('user_id', $user->id)
                ->where('book_id', $bookId)
                ->delete();

            return back()->with('success', 'Buku dihapus dari wishlist.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus dari wishlist: ' . $e->getMessage());
        }
    }

    // Review - Tambah/Update
    public function addReview(Request $request, $bookId)
    {
        try {
            $validated = $request->validate([
                'rating' => 'required|integer|between:1,5',
                'review' => 'nullable|string|max:1000',
            ]);

            $user = auth()->user();
            $borrowerId = $this->getBorrowerIdForCurrentUser();

            $hasUserBorrowedBook = Loan::where('borrower_id', $borrowerId)
                ->whereHas('loanItems', function ($query) use ($bookId) {
                    $query->where('book_id', $bookId);
                })
                ->exists();

            if (!$hasUserBorrowedBook) {
                return back()->with('error', 'Anda harus meminjam buku ini terlebih dahulu untuk memberikan review.');
            }

            BookReview::updateOrCreate(
                ['book_id' => $bookId, 'user_id' => $user->id],
                [
                    'rating' => $validated['rating'],
                    'review' => $validated['review'],
                ]
            );

            return back()->with('success', 'Review Anda berhasil tersimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan review: ' . $e->getMessage());
        }
    }

    // Reservasi - Lihat Daftar
    public function reservations()
    {
        $user = auth()->user();
        $reservations = BookReservation::with('book')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.reservasi', compact('reservations'));
    }

    // Reservasi - Tambah
    public function addReservation(Request $request)
    {
        try {
            $validated = $request->validate([
                'book_id' => 'required|exists:books,id',
            ]);

            $user = auth()->user();
            $borrowerId = $this->getBorrowerIdForCurrentUser();
            $book = Book::findOrFail($validated['book_id']);

            $onLoan = Loan::where('borrower_id', $borrowerId)
                ->where('status', 'active')
                ->whereHas('loanItems', function ($query) use ($validated) {
                    $query->where('book_id', $validated['book_id']);
                })
                ->exists();

            if ($onLoan) {
                return back()->with('info', 'Anda sedang meminjam buku ini.');
            }

            $existing = BookReservation::where('user_id', $user->id)
                ->where('book_id', $validated['book_id'])
                ->whereIn('status', ['pending', 'ready'])
                ->first();

            if ($existing) {
                return back()->with('info', 'Anda sudah memiliki reservasi untuk buku ini.');
            }

            BookReservation::create([
                'book_id' => $validated['book_id'],
                'user_id' => $user->id,
                'status' => 'pending',
                'reserved_until' => now()->addDays(3),
            ]);

            return back()->with('success', 'Buku "' . $book->title . '" berhasil direservasi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan reservasi: ' . $e->getMessage());
        }
    }

    // Reservasi - Batal
    public function cancelReservation($reservationId)
    {
        try {
            $user = auth()->user();
            $reservation = BookReservation::findOrFail($reservationId);

            if ($reservation->user_id !== $user->id) {
                return back()->with('error', 'Anda tidak memiliki akses.');
            }

            $reservation->update(['status' => 'cancelled']);

            return back()->with('success', 'Reservasi berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan reservasi: ' . $e->getMessage());
        }
    }

    // Perpanjangan Pinjaman - Lihat Daftar
    public function extensions()
    {
        $borrowerId = $this->getBorrowerIdForCurrentUser();

        $extensions = LoanExtension::with('loan.loanItems.book')
            ->whereHas('loan', function ($query) use ($borrowerId) {
                $query->where('borrower_id', $borrowerId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $activeLoans = Loan::with(['loanItems.book', 'extensions'])
            ->where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->orderBy('due_date', 'asc')
            ->get();

        $extensionSummary = [
            'pending' => LoanExtension::whereHas('loan', function ($query) use ($borrowerId) {
                $query->where('borrower_id', $borrowerId);
            })->where('status', 'pending')->count(),
            'approved' => LoanExtension::whereHas('loan', function ($query) use ($borrowerId) {
                $query->where('borrower_id', $borrowerId);
            })->where('status', 'approved')->count(),
            'rejected' => LoanExtension::whereHas('loan', function ($query) use ($borrowerId) {
                $query->where('borrower_id', $borrowerId);
            })->where('status', 'rejected')->count(),
        ];

        return view('siswa.perpanjangan', compact('extensions', 'activeLoans', 'extensionSummary'));
    }

    // Perpanjangan - Ajukan
    public function requestExtension(Request $request)
    {
        try {
            $validated = $request->validate([
                'loan_id' => 'required|exists:loans,id',
                'reason' => 'nullable|string|max:500',
            ]);

            $borrowerId = $this->getBorrowerIdForCurrentUser();
            $loan = Loan::findOrFail($validated['loan_id']);

            if ((int) $loan->borrower_id !== $borrowerId) {
                return back()->with('error', 'Anda tidak memiliki akses.');
            }

            if ($loan->status !== 'active') {
                return back()->with('error', 'Hanya peminjaman aktif yang bisa diperpanjang.');
            }

            $pending = LoanExtension::where('loan_id', $loan->id)
                ->where('status', 'pending')
                ->first();

            if ($pending) {
                return back()->with('info', 'Sudah ada permintaan perpanjangan yang menunggu persetujuan.');
            }

            $previousExt = LoanExtension::where('loan_id', $loan->id)
                ->where('status', 'approved')
                ->count();

            if ($previousExt >= 2) {
                return back()->with('error', 'Buku sudah mencapai batas maksimal perpanjangan (2x).');
            }

            $extensionDays = 7;
            $newDueDate = $loan->due_date->copy()->addDays($extensionDays)->endOfDay();

            LoanExtension::create([
                'loan_id' => $loan->id,
                'old_due_date' => $loan->due_date,
                'new_due_date' => $newDueDate,
                'extension_days' => $extensionDays,
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]);

            return back()->with('success', 'Permintaan perpanjangan berhasil dikirim.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengajukan perpanjangan: ' . $e->getMessage());
        }
    }

    // Notifikasi - Lihat Daftar
    public function notifications()
    {
        $user = auth()->user();

        $notifications = StudentNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $unreadCount = StudentNotification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('siswa.notifikasi', compact('notifications', 'unreadCount'));
    }

    // Notifikasi - Tandai Terbaca
    public function markNotificationAsRead($notificationId)
    {
        try {
            $user = auth()->user();
            $notification = StudentNotification::findOrFail($notificationId);

            if ($notification->user_id !== $user->id) {
                return back()->with('error', 'Anda tidak memiliki akses.');
            }

            $notification->markAsRead();

            return back()->with('success', 'Notifikasi ditandai sebagai terbaca.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui notifikasi: ' . $e->getMessage());
        }
    }

    // Notifikasi - Tandai Semua Terbaca
    public function markAllNotificationsAsRead()
    {
        try {
            $user = auth()->user();

            StudentNotification::where('user_id', $user->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            return back()->with('success', 'Semua notifikasi telah ditandai sebagai terbaca.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui notifikasi: ' . $e->getMessage());
        }
    }

    // Denda - Lihat Daftar
    public function denda()
    {
        $borrowerId = $this->getBorrowerIdForCurrentUser();

        $overdueLoans = Loan::with('loanItems.book')
            ->where('borrower_id', $borrowerId)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->get();

        $totalFine = 0;
        $fineDetails = [];

        foreach ($overdueLoans as $loan) {
            $daysOverdue = now()->diffInDays($loan->due_date);
            $fineAmount = $daysOverdue * 5000;
            $totalFine += $fineAmount;

            $fineDetails[] = [
                'loan_id' => $loan->id,
                'book_titles' => $loan->loanItems->pluck('book.title')->join(', '),
                'due_date' => $loan->due_date,
                'days_overdue' => $daysOverdue,
                'fine_amount' => $fineAmount,
            ];
        }

        return view('siswa.denda', [
            'fineDetails' => $fineDetails,
            'totalFine' => $totalFine,
        ]);
    }

    // Resume / Download Riwayat
    public function downloadHistory()
    {
        try {
            $user = auth()->user();
            $borrowerId = $this->getBorrowerIdForCurrentUser();

            $loans = Loan::with('loanItems.book')
                ->where('borrower_id', $borrowerId)
                ->orderBy('loan_date', 'desc')
                ->get();

            $content = "RIWAYAT PEMINJAMAN BUKU\n";
            $content .= "Nama: " . $user->name . "\n";
            $content .= "Email: " . $user->email . "\n";
            $content .= "Tanggal Laporan: " . now()->format('d/m/Y H:i') . "\n";
            $content .= "=" . str_repeat("=", 70) . "\n\n";

            foreach ($loans as $loan) {
                $content .= "ID Peminjaman: " . $loan->id . "\n";
                $content .= "Tanggal Pinjam: " . $loan->loan_date->format('d/m/Y') . "\n";
                $content .= "Tanggal Jatuh Tempo: " . $loan->due_date->format('d/m/Y') . "\n";
                $content .= "Status: " . ucfirst($loan->status) . "\n";

                if ($loan->return_date) {
                    $content .= "Tanggal Kembali: " . $loan->return_date->format('d/m/Y') . "\n";
                }

                $content .= "Buku:\n";
                foreach ($loan->loanItems as $item) {
                    $content .= "  - " . $item->book->title . " (Penulis: " . $item->book->author . ")\n";
                }
                $content .= "\n";
            }

            return response()->download(
                'data://text/plain;base64,' . base64_encode($content),
                'Riwayat_Peminjaman_' . $user->id . '_' . now()->format('Ymd') . '.txt'
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunduh riwayat: ' . $e->getMessage());
        }
    }
}
