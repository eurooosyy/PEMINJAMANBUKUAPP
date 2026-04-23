<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use App\Models\LoanItem;
use App\Models\BookReview;
use App\Models\Wishlist;
use App\Models\BookReservation;
use App\Models\LoanExtension;
use App\Models\StudentNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    // Dashboard Siswa
    public function dashboard()
    {
        $user = auth()->user();

        // Total peralatan di sistem
        $totalEquipment = Equipment::where('stock', '>', 0)->count();

        // Peminjaman aktif (count)
        $activeLoansCount = Loan::where('borrower_id', $user->id)
            ->where('status', 'active')
            ->count();

        // Total semua peminjaman
        $totalLoans = Loan::where('borrower_id', $user->id)->count();

        // Detail peralatan yang sedang dipinjam
        $activeLoans = Loan::with('loanItems.equipment')
            ->where('borrower_id', $user->id)
            ->where('status', 'active')
            ->orderBy('loan_date', 'desc')
            ->get();

        // Hitung peralatan terlambat
        $overdueEquipment = Loan::with('loanItems.equipment')
            ->where('borrower_id', $user->id)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->count();

        // Peralatan populer (top 5 most borrowed)
        $popularBooks = Equipment::withCount(['loanItems as borrow_count' => function ($query) {
            $query->join('loans', 'loan_items.loan_id', '=', 'loans.id')
                ->whereIn('loans.status', ['returned', 'active']); // Include active for current popularity
        }])
            ->with('loanItems.loan') // For last borrow info
            ->having('borrow_count', '>', 0)
            ->orderBy('borrow_count', 'desc')
            ->limit(6)
            ->get();


        // Peralatan terbaru (latest 5 added)
        $recentBooks = Equipment::orderBy('created_at', 'desc')->with('reviews')->limit(5)->get();


        // Upcoming due dates (next 7 days)
        $upcomingDueDates = Loan::with('loanItems.equipment')
            ->where('borrower_id', $user->id)
            ->where('status', 'active')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->orderBy('due_date', 'asc')
            ->get();

        // Statistik bulanan (current month)
        $monthlyStats = [
            'borrowed' => Loan::where('borrower_id', $user->id)
                ->whereMonth('loan_date', now()->month)
                ->whereYear('loan_date', now()->year)
                ->count(),
            'returned' => Loan::where('borrower_id', $user->id)
                ->where('status', 'returned')
                ->whereMonth('return_date', now()->month)
                ->whereYear('return_date', now()->year)
                ->count(),
        ];

        // Pengumuman sekolah (hardcoded for now, can be made dynamic later)
        $announcements = [
            [
                'title' => 'Perpanjangan Jam Peminjaman',
                'content' => 'Ruang peminjaman peralatan akan buka lebih lama mulai bulan depan.',
                'date' => '2026-04-15'
            ],
            [
                'title' => 'Peralatan Baru Tiba',
                'content' => 'Koleksi peralatan laboratorium terbaru sudah tersedia.',
                'date' => '2026-04-10'
            ]
        ];

        return view('siswa.dashboard', [
            'totalEquipment' => $totalEquipment,
            'activeLoansCount' => $activeLoansCount,
            'activeLoans' => $activeLoans,
            'totalLoans' => $totalLoans,
            'overdueEquipment' => $overdueEquipment,
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
        $user = auth()->user();

        // Semua peminjaman user dengan relasi
        $allLoans = Loan::with('loanItems.equipment')
            ->where('borrower_id', $user->id)
            ->orderBy('loan_date', 'desc')
            ->paginate(10);

        return view('siswa.riwayat', ['allLoans' => $allLoans]);
    }

    // Pinjam Alat
    public function pinjam(Request $request, Equipment $equipment = null)
    {
        try {
            $user = auth()->user();

            // Validasi user
            if (!$user) {
                return redirect('/login')->with('error', 'Anda harus login terlebih dahulu');
            }

            // Ambil equipment dari route parameter atau request body
            if ($equipment === null) {
                // Coba ambil dari request body terlebih dahulu
                if ($request->has('equipment_id')) {
                    $validated = $request->validate([
                        'equipment_id' => 'required|integer|exists:equipment,id',
                    ]);
                    $equipment = Equipment::findOrFail($validated['equipment_id']);
                } else {
                    return back()->with('error', '❌ ID peralatan tidak ditemukan. Silahkan refresh halaman dan coba lagi.');
                }
            }

            // Double-check equipment exists
            if (!$equipment) {
                return back()->with('error', '❌ Peralatan tidak ditemukan di database.');
            }

            // Validasi stok
            if (!$equipment || $equipment->stock <= 0) {
                return back()->with('error', '❌ Peralatan "' . ($equipment?->nama_peralatan ?? 'Unknown') . '" tidak tersedia (Stok: ' . ($equipment?->stock ?? 0) . ')');
            }

            // Buat loan record
            $loan = Loan::create([
                'borrower_id' => $user->id,
                'loan_date' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'active',
            ]);

            if (!$loan) {
                throw new \Exception('Gagal membuat record peminjaman di database');
            }

            // Buat loan item
            $loanItem = LoanItem::create([
                'loan_id' => $loan->id,
                'equipment_id' => $equipment->id,
            ]);

            if (!$loanItem) {
                // Rollback loan jika gagal create loan item
                $loan->delete();
                throw new \Exception('Gagal membuat detail item peminjaman');
            }

            // Kurangi stok peralatan
            $equipment->decrement('stock');

            Log::info('Peralatan berhasil dipinjam', [
                'user_id' => $user->id,
                'equipment_id' => $equipment->id,
                'equipment_name' => $equipment->nama_peralatan,
                'loan_id' => $loan->id,
            ]);

            return back()->with('success', '✅ Peralatan "' . $equipment->nama_peralatan . '" berhasil dipinjam selama 7 hari! Cek di Riwayat Peminjaman.');
        } catch (\Exception $e) {
            Log::error('Error pinjam peralatan: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'equipment_id' => $equipment?->id ?? null,
                'exception' => $e,
            ]);
            return back()->with('error', '❌ Gagal meminjam peralatan: ' . $e->getMessage());
        }
    }

    // Kembalikan Peralatan
    public function kembalikan(Request $request)
    {
        try {
            $validated = $request->validate([
                'loan_id' => 'required|exists:loans,id',
            ]);

            $user = auth()->user();
            $loan = Loan::findOrFail($validated['loan_id']);

            // Validasi ownership
            if ($loan->borrower_id !== $user->id) {
                return back()->with('error', '❌ Anda tidak memiliki akses ke peminjaman ini');
            }

            // Validasi status
            if ($loan->status !== 'active') {
                return back()->with('error', '❌ Peminjaman ini sudah dalam status "' . $loan->status . '"');
            }

            // Kembalikan stok untuk semua item
            foreach ($loan->loanItems as $item) {
                $item->equipment->increment('stock');
            }

            // Update loan status
            $loan->update([
                'status' => 'returned',
                'return_date' => now(),
            ]);

            Log::info('Alat berhasil dikembalikan', [
                'user_id' => $user->id,
                'loan_id' => $loan->id,
                'item_count' => $loan->loanItems->count(),
            ]);

            return back()->with('success', '✅ Alat berhasil dikembalikan! Terima kasih telah meminjam.');
        } catch (\Exception $e) {
            Log::error('Error kembalikan alat: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal mengembalikan alat: ' . $e->getMessage());
        }
    }

    // Jelajahi Peralatan
    public function jelajahi(Request $request)
    {
        $query = Equipment::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peralatan', 'like', '%' . $search . '%')
                    ->orWhere('merk', 'like', '%' . $search . '%')
                    ->orWhere('kategori', 'like', '%' . $search . '%');
            });
        }

        // Filter by availability
        if ($request->has('available') && $request->available == '1') {
            $query->where('stock', '>', 0);
        }

        $equipment = $query->paginate(12);

        return view('siswa.jelajahi-alat', compact('equipment'));
    }

    // Tampilkan Form Tambah Peminjaman
    public function tambahPeminjaman()
    {
        $user = auth()->user();
        $equipment = Equipment::where('stock', '>', 0)->get();

        // Cek peminjaman aktif
        $activeLoans = Loan::where('borrower_id', $user->id)
            ->where('status', 'active')
            ->count();

        return view('siswa.tambah-peminjaman', [
            'equipment' => $equipment,
            'activeLoans' => $activeLoans,
        ]);
    }

    // Pinjam Multiple Peralatan
    public function pinjamMultiple(Request $request)
    {
        try {
            $user = auth()->user();

            // Validasi request
            $validated = $request->validate([
                'equipment_ids' => 'required|array|min:1',
                'equipment_ids.*' => 'required|integer|exists:equipment,id',
                'duration_days' => 'required|integer|min:1|max:30',
            ]);

            $equipmentIds = $validated['equipment_ids'];
            $durationDays = $validated['duration_days'];

            // Cek ketersediaan semua peralatan
            $equipment = Equipment::whereIn('id', $equipmentIds)->get();

            if ($equipment->count() !== count($equipmentIds)) {
                return back()->with('error', '❌ Beberapa peralatan tidak ditemukan di database.');
            }

            // Validasi stok untuk semua peralatan
            $unavailableEquipment = [];
            foreach ($equipment as $item) {
                if ($item->stock <= 0) {
                    $unavailableEquipment[] = $item->nama_peralatan;
                }
            }

            if (!empty($unavailableEquipment)) {
                return back()->with('error', '❌ Peralatan berikut tidak tersedia: ' . implode(', ', $unavailableEquipment));
            }

            // Mulai transaksi
            DB::beginTransaction();

            try {
                // Buat loan record
                $loan = Loan::create([
                    'borrower_id' => $user->id,
                    'loan_date' => now(),
                    'due_date' => now()->addDays($durationDays),
                    'status' => 'active',
                ]);

                if (!$loan) {
                    throw new \Exception('Gagal membuat record peminjaman di database');
                }

                // Buat loan items untuk setiap peralatan
                $borrowedEquipment = [];
                foreach ($equipment as $item) {
                    $loanItem = LoanItem::create([
                        'loan_id' => $loan->id,
                        'equipment_id' => $item->id,
                    ]);

                    if (!$loanItem) {
                        throw new \Exception('Gagal membuat detail item peminjaman untuk peralatan: ' . $item->nama_peralatan);
                    }

                    // Kurangi stok
                    $item->decrement('stock');
                    $borrowedEquipment[] = $item->nama_peralatan;
                }

                DB::commit();

                Log::info('Multiple peralatan berhasil dipinjam', [
                    'user_id' => $user->id,
                    'equipment_count' => count($borrowedEquipment),
                    'equipment_names' => implode(', ', $borrowedEquipment),
                    'loan_id' => $loan->id,
                    'duration_days' => $durationDays,
                ]);

                $message = '✅ ' . count($borrowedEquipment) . ' peralatan berhasil dipinjam selama ' . $durationDays . ' hari!<br>';
                $message .= '<small>Peralatan: ' . implode(', ', $borrowedEquipment) . '</small>';

                return redirect()->route('siswa.riwayat')->with('success', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Error pinjam multiple alat: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'exception' => $e,
            ]);
            return back()->with('error', '❌ Gagal meminjam alat: ' . $e->getMessage());
        }
    }

    // ========== FITUR BARU UNTUK SISWA ==========

    // Profil Siswa
    public function profile()
    {
        $user = auth()->user();
        $totalLoans = Loan::where('borrower_id', $user->id)->count();
        $activeLoans = Loan::where('borrower_id', $user->id)->where('status', 'active')->count();
        $returnedLoans = Loan::where('borrower_id', $user->id)->where('status', 'returned')->count();
        $overdueLoans = Loan::where('borrower_id', $user->id)
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

            return back()->with('success', '✅ Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error update profil: ' . $e->getMessage());
            return back()->with('error', '❌ Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    // Wishlist - Lihat Daftar
    public function wishlist()
    {
        $user = auth()->user();
        $wishlist = Wishlist::with('equipment')->where('user_id', $user->id)->paginate(10);

        return view('siswa.wishlist', compact('wishlist'));
    }

    // Wishlist - Tambah
    public function addToWishlist(Request $request)
    {
        try {
            $validated = $request->validate([
                'equipment_id' => 'required|exists:equipment,id',
            ]);

            $user = auth()->user();
            $equipment = Equipment::findOrFail($validated['equipment_id']);

            // Cek apakah sudah ada di wishlist
            $existing = Wishlist::where('user_id', $user->id)
                ->where('equipment_id', $validated['equipment_id'])
                ->first();

            if ($existing) {
                return back()->with('info', 'ℹ️ Peralatan ini sudah ada di wishlist Anda.');
            }

            Wishlist::create([
                'user_id' => $user->id,
                'equipment_id' => $validated['equipment_id'],
            ]);

            return back()->with('success', '❤️ Peralatan "' . $equipment->nama_peralatan . '" ditambahkan ke wishlist!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal menambahkan ke wishlist: ' . $e->getMessage());
        }
    }

    // Wishlist - Hapus
    public function removeFromWishlist($equipmentId)
    {
        try {
            $user = auth()->user();
            $equipment = Equipment::findOrFail($equipmentId);

            Wishlist::where('user_id', $user->id)
                ->where('equipment_id', $equipmentId)
                ->delete();

            return back()->with('success', '✅ Peralatan dihapus dari wishlist!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal menghapus dari wishlist: ' . $e->getMessage());
        }
    }

    // Review - Tambah/Update
    public function addReview(Request $request, $equipmentId)
    {
        try {
            $validated = $request->validate([
                'rating' => 'required|integer|between:1,5',
                'review' => 'nullable|string|max:1000',
            ]);

            $user = auth()->user();
            $equipment = Equipment::findOrFail($equipmentId);

            // Cek apakah user pernah meminjam peralatan ini
            $hasUserBorrowedEquipment = Loan::where('borrower_id', $user->id)
                ->whereHas('loanItems', function ($query) use ($equipmentId) {
                    $query->where('equipment_id', $equipmentId);
                })
                ->exists();

            if (!$hasUserBorrowedEquipment) {
                return back()->with('error', '❌ Anda harus meminjam peralatan ini terlebih dahulu untuk memberikan review.');
            }

            // Update atau buat review
            EquipmentReview::updateOrCreate(
                ['equipment_id' => $equipmentId, 'user_id' => $user->id],
                [
                    'rating' => $validated['rating'],
                    'review' => $validated['review'],
                ]
            );

            return back()->with('success', '⭐ Review Anda berhasil tersimpan!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal menyimpan review: ' . $e->getMessage());
        }
    }

    // Reservasi - Lihat Daftar
    public function reservations()
    {
        $user = auth()->user();
        $reservations = EquipmentReservation::with('equipment')
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
                'equipment_id' => 'required|exists:equipment,id',
            ]);

            $user = auth()->user();
            $equipment = Equipment::findOrFail($validated['equipment_id']);

            // Cek apakah peralatan sedang dipinjam user
            $onLoan = Loan::where('borrower_id', $user->id)
                ->where('status', 'active')
                ->whereHas('loanItems', function ($query) use ($validated) {
                    $query->where('equipment_id', $validated['equipment_id']);
                })
                ->exists();

            if ($onLoan) {
                return back()->with('info', 'ℹ️ Anda sedang meminjam peralatan ini. Reservasi otomatis dilakukan saat mengembalikan.');
            }

            // Cek apakah sudah di-reserve
            $existing = EquipmentReservation::where('user_id', $user->id)
                ->where('equipment_id', $validated['equipment_id'])
                ->whereIn('status', ['pending', 'ready'])
                ->first();

            if ($existing) {
                return back()->with('info', 'ℹ️ Anda sudah memiliki reservasi untuk peralatan ini.');
            }

            EquipmentReservation::create([
                'equipment_id' => $validated['equipment_id'],
                'user_id' => $user->id,
                'status' => 'pending',
                'reserved_until' => now()->addDays(3),
            ]);

            return back()->with('success', '✅ Peralatan "' . $equipment->nama_peralatan . '" berhasil di-reserve!');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal melakukan reservasi: ' . $e->getMessage());
        }
    }

    // Reservasi - Batal
    public function cancelReservation($reservationId)
    {
        try {
            $user = auth()->user();
            $reservation = EquipmentReservation::findOrFail($reservationId);

            if ($reservation->user_id !== $user->id) {
                return back()->with('error', '❌ Anda tidak memiliki akses.');
            }

            $reservation->update(['status' => 'cancelled']);

            return back()->with('success', '✅ Reservasi berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal membatalkan reservasi: ' . $e->getMessage());
        }
    }

    // Perpanjangan Pinjaman - Lihat Daftar
    public function extensions()
    {
        $user = auth()->user();
        $extensions = LoanExtension::with('loan.loanItems.equipment')
            ->whereHas('loan', function ($query) use ($user) {
                $query->where('borrower_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.perpanjangan', compact('extensions'));
    }

    // Perpanjangan - Ajukan
    public function requestExtension(Request $request)
    {
        try {
            $validated = $request->validate([
                'loan_id' => 'required|exists:loans,id',
                'reason' => 'nullable|string|max:500',
            ]);

            $user = auth()->user();
            $loan = Loan::findOrFail($validated['loan_id']);

            // Validasi ownership
            if ($loan->borrower_id !== $user->id) {
                return back()->with('error', '❌ Anda tidak memiliki akses.');
            }

            // Cek status
            if ($loan->status !== 'active') {
                return back()->with('error', '❌ Hanya peminjaman aktif yang bisa diperpanjang.');
            }

            // Cek apakah sudah ada extension pending
            $pending = LoanExtension::where('loan_id', $loan->id)
                ->where('status', 'pending')
                ->first();

            if ($pending) {
                return back()->with('info', 'ℹ️ Sudah ada permintaan perpanjangan yang menunggu persetujuan.');
            }

            // Cek apakah sudah pernah diperpanjang
            $previousExt = LoanExtension::where('loan_id', $loan->id)
                ->where('status', 'approved')
                ->count();

            if ($previousExt >= 2) {
                return back()->with('error', '❌ Buku sudah mencapai batas maksimal perpanjangan (2x).');
            }

            $extensionDays = 7;
            $newDueDate = $loan->due_date->addDays($extensionDays);

            LoanExtension::create([
                'loan_id' => $loan->id,
                'old_due_date' => $loan->due_date,
                'new_due_date' => $newDueDate,
                'extension_days' => $extensionDays,
                'reason' => $validated['reason'],
                'status' => 'pending',
            ]);

            return back()->with('success', '✅ Permintaan perpanjangan berhasil dikirim! Menunggu persetujuan admin.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal mengajukan perpanjangan: ' . $e->getMessage());
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
                return back()->with('error', '❌ Anda tidak memiliki akses.');
            }

            $notification->markAsRead();

            return back()->with('success', '✅ Notifikasi ditandai sebagai terbaca.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal memperbarui notifikasi: ' . $e->getMessage());
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

            return back()->with('success', '✅ Semua notifikasi telah ditandai sebagai terbaca.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal memperbarui notifikasi: ' . $e->getMessage());
        }
    }

    // Denda - Lihat Daftar
    public function denda()
    {
        $user = auth()->user();

        // Hitung denda dari peminjaman terlambat
        $overdueLoans = Loan::with('loanItems.equipment')
            ->where('borrower_id', $user->id)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->get();

        $totalFine = 0;
        $fineDetails = [];

        foreach ($overdueLoans as $loan) {
            $daysOverdue = now()->diffInDays($loan->due_date);
            $fineAmount = $daysOverdue * 5000; // 5000 per hari
            $totalFine += $fineAmount;

            $fineDetails[] = [
                'loan_id' => $loan->id,
                'equipment_names' => $loan->loanItems->pluck('equipment.nama_peralatan')->join(', '),
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
            $loans = Loan::with('loanItems.equipment')
                ->where('borrower_id', $user->id)
                ->orderBy('loan_date', 'desc')
                ->get();

            $content = "RIWAYAT PEMINJAMAN PERALATAN\n";
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

                $content .= "Peralatan:\n";
                foreach ($loan->loanItems as $item) {
                    $content .= "  - " . $item->equipment->nama_peralatan . " (" . $item->equipment->merk . ")\n";
                }
                $content .= "\n";
            }

            return response()
                ->download('data://text/plain;base64,' . base64_encode($content), 'Riwayat_Peminjaman_' . $user->id . '_' . now()->format('Ymd') . '.txt');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Gagal mengunduh riwayat: ' . $e->getMessage());
        }
    }
}
