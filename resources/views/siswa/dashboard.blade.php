@extends('dashboard.siswa-layout')

@section('title')
    Dashboard Siswa
@endsection

@section('content')
    @php
        $borrowerClass = $borrower->class ?? null;
    @endphp

    <div
        style="background: linear-gradient(135deg, #4facfe 0%, #00c6fb 100%); color: white; padding: 32px; border-radius: 18px; margin-bottom: 30px; box-shadow: 0 14px 30px rgba(79, 172, 254, 0.22);">
        <div style="display: flex; justify-content: space-between; gap: 24px; align-items: center; flex-wrap: wrap;">
            <div>
                <h1 style="margin: 0 0 8px 0; font-size: 30px; font-weight: 800;">
                    Selamat datang, {{ auth()->user()->name }}
                </h1>
                <p style="margin: 0; font-size: 15px; opacity: 0.95;">
                    Kelola peminjaman buku, cek jatuh tempo, dan temukan bacaan baru dengan lebih mudah.
                </p>
                @if (!empty($borrowerClass) && $borrowerClass !== '-')
                    <div style="margin-top: 14px;">
                        <span
                            style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.18); padding: 8px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">
                            <i class="fas fa-school"></i> Kelas {{ $borrowerClass }}
                        </span>
                    </div>
                @endif
            </div>
            <div style="font-size: 68px; opacity: 0.18;">
                <i class="fas fa-book-reader"></i>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 18px; margin-bottom: 28px;">
        <a href="{{ route('siswa.tambah-peminjaman') }}"
            style="background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%); color: white; padding: 18px; border-radius: 14px; text-decoration: none; box-shadow: 0 10px 24px rgba(37, 99, 235, 0.18);">
            <div style="font-size: 24px; margin-bottom: 10px;"><i class="fas fa-plus-circle"></i></div>
            <strong style="display: block; margin-bottom: 4px;">Tambah Peminjaman</strong>
            <span style="font-size: 13px; opacity: 0.95;">Pilih buku yang ingin dipinjam</span>
        </a>
        <a href="{{ route('siswa.jelajahi') }}"
            style="background: white; color: #2563eb; padding: 18px; border-radius: 14px; text-decoration: none; border: 1px solid #dbeafe; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);">
            <div style="font-size: 24px; margin-bottom: 10px;"><i class="fas fa-search"></i></div>
            <strong style="display: block; margin-bottom: 4px;">Jelajahi Buku</strong>
            <span style="font-size: 13px; color: #64748b;">Cari berdasarkan judul atau kategori</span>
        </a>
        <a href="{{ route('siswa.riwayat') }}"
            style="background: white; color: #475569; padding: 18px; border-radius: 14px; text-decoration: none; border: 1px solid #e2e8f0; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);">
            <div style="font-size: 24px; margin-bottom: 10px;"><i class="fas fa-history"></i></div>
            <strong style="display: block; margin-bottom: 4px;">Riwayat Peminjaman</strong>
            <span style="font-size: 13px; color: #64748b;">Lihat semua transaksi peminjaman</span>
        </a>
        <a href="{{ route('siswa.perpanjangan') }}"
            style="background: white; color: #0f766e; padding: 18px; border-radius: 14px; text-decoration: none; border: 1px solid #ccfbf1; box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);">
            <div style="font-size: 24px; margin-bottom: 10px;"><i class="fas fa-sync-alt"></i></div>
            <strong style="display: block; margin-bottom: 4px;">Perpanjangan</strong>
            <span style="font-size: 13px; color: #64748b;">Ajukan perpanjangan peminjaman</span>
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 18px; margin-bottom: 28px;">
        <div
            style="background: white; padding: 22px; border-radius: 16px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); border-left: 5px solid #2563eb;">
            <div style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Buku Tersedia</div>
            <div style="font-size: 34px; font-weight: 800; color: #0f172a; margin-top: 10px;">{{ $totalBooks ?? 0 }}</div>
        </div>
        <div
            style="background: white; padding: 22px; border-radius: 16px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); border-left: 5px solid #16a34a;">
            <div style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Peminjaman Aktif</div>
            <div style="font-size: 34px; font-weight: 800; color: #0f172a; margin-top: 10px;">{{ $activeLoansCount ?? 0 }}</div>
        </div>
        <div
            style="background: white; padding: 22px; border-radius: 16px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); border-left: 5px solid #7c3aed;">
            <div style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Total Riwayat</div>
            <div style="font-size: 34px; font-weight: 800; color: #0f172a; margin-top: 10px;">{{ $totalLoans ?? 0 }}</div>
        </div>
        <div
            style="background: white; padding: 22px; border-radius: 16px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); border-left: 5px solid #dc2626;">
            <div style="font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">Terlambat</div>
            <div style="font-size: 34px; font-weight: 800; color: #0f172a; margin-top: 10px;">{{ $overdueBooks ?? 0 }}</div>
        </div>
    </div>

    @php
        $currentBorrower = \App\Models\Borrower::firstOrCreate(
            ['user_id' => auth()->id()],
            ['nis' => 'NIS-' . auth()->id(), 'class' => '-', 'phone' => '-'],
        );
        $pendingExtensions = \App\Models\LoanExtension::whereHas('loan', function ($q) use ($currentBorrower) {
            $q->where('borrower_id', $currentBorrower->id);
        })->where('status', 'pending')->count();
        $totalFine = \App\Models\Loan::where('borrower_id', $currentBorrower->id)
            ->where('status', 'active')
            ->where('due_date', '<', now())
            ->get()
            ->sum(function ($loan) {
                return now()->diffInDays($loan->due_date) * 5000;
            });
    @endphp

    @if ($pendingExtensions > 0 || $totalFine > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; margin-bottom: 28px;">
            @if ($pendingExtensions > 0)
                <div style="background: #fff7ed; border-left: 5px solid #f59e0b; border-radius: 14px; padding: 18px;">
                    <strong style="display: block; color: #9a3412; margin-bottom: 8px;">Permintaan Perpanjangan</strong>
                    <span style="color: #9a3412; font-size: 14px;">Anda memiliki {{ $pendingExtensions }} permintaan yang masih menunggu persetujuan.</span>
                </div>
            @endif
            @if ($totalFine > 0)
                <div style="background: #fef2f2; border-left: 5px solid #ef4444; border-radius: 14px; padding: 18px;">
                    <strong style="display: block; color: #991b1b; margin-bottom: 8px;">Denda Aktif</strong>
                    <span style="color: #991b1b; font-size: 14px;">Total denda sementara: Rp {{ number_format($totalFine, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 22px; margin-bottom: 28px;">
        <div style="background: white; border-radius: 16px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); padding: 22px;">
            <h5 style="margin: 0 0 18px 0; font-weight: 800; color: #0f172a;">Buku yang Sedang Dipinjam</h5>
            @if (($activeLoans ?? collect())->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 12px; text-align: left; font-size: 12px; color: #64748b;">Buku</th>
                                <th style="padding: 12px; text-align: center; font-size: 12px; color: #64748b;">Pinjam</th>
                                <th style="padding: 12px; text-align: center; font-size: 12px; color: #64748b;">Kembali</th>
                                <th style="padding: 12px; text-align: center; font-size: 12px; color: #64748b;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeLoans as $loan)
                                @foreach ($loan->loanItems as $item)
                                    @php($daysLeft = $loan->due_date ? now()->diffInDays($loan->due_date, false) : 0)
                                    <tr style="border-bottom: 1px solid #eef2f7;">
                                        <td style="padding: 12px;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                <div
                                                    style="width: 52px; height: 72px; border-radius: 10px; overflow: hidden; background: #e2e8f0; flex-shrink: 0; display: flex; align-items: center; justify-content: center;">
                                                    @if ($item->book?->image_url)
                                                        <img src="{{ $item->book->image_url }}" alt="{{ $item->book->title }}"
                                                            style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <i class="fas fa-book" style="color: #2563eb;"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div style="color: #0f172a; font-weight: 700;">{{ $item->book->title ?? '-' }}</div>
                                                    <div style="font-size: 12px; color: #64748b;">
                                                        {{ $item->book->author ?? 'Penulis tidak diketahui' }} | Jumlah
                                                        {{ $item->quantity ?? 1 }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 12px; text-align: center; color: #64748b;">{{ $loan->loan_date?->format('d/m/Y') ?? '-' }}</td>
                                        <td style="padding: 12px; text-align: center; color: #64748b;">
                                            {{ $loan->due_date?->translatedFormat('d M Y') ?? '-' }}<br>
                                            <span style="font-size: 12px; color: #94a3b8;">{{ $loan->due_date?->format('H:i') ?? '--:--' }} WIB</span>
                                        </td>
                                        <td style="padding: 12px; text-align: center;">
                                            @if ($daysLeft > 0)
                                                <span style="display: inline-block; background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700;">{{ $daysLeft }} hari lagi</span>
                                            @elseif($daysLeft === 0)
                                                <span style="display: inline-block; background: #fef3c7; color: #92400e; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700;">Hari ini</span>
                                            @else
                                                <span style="display: inline-block; background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 700;">Terlambat {{ abs($daysLeft) }} hari</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 26px; color: #94a3b8;">
                    <i class="fas fa-inbox" style="font-size: 32px; margin-bottom: 12px;"></i>
                    <div>Belum ada buku yang sedang dipinjam.</div>
                </div>
            @endif
        </div>

        <div style="background: white; border-radius: 16px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); padding: 22px;">
            <h5 style="margin: 0 0 18px 0; font-weight: 800; color: #0f172a;">Ringkasan Bulan Ini</h5>
            <div style="display: grid; gap: 14px;">
                <div style="padding: 16px; background: #eff6ff; border-radius: 14px;">
                    <div style="font-size: 12px; color: #1d4ed8; text-transform: uppercase; letter-spacing: 1px;">Dipinjam</div>
                    <div style="font-size: 28px; font-weight: 800; color: #1e3a8a;">{{ $monthlyStats['borrowed'] ?? 0 }}</div>
                </div>
                <div style="padding: 16px; background: #ecfdf5; border-radius: 14px;">
                    <div style="font-size: 12px; color: #047857; text-transform: uppercase; letter-spacing: 1px;">Dikembalikan</div>
                    <div style="font-size: 28px; font-weight: 800; color: #065f46;">{{ $monthlyStats['returned'] ?? 0 }}</div>
                </div>
            </div>

            @if (($upcomingDueDates ?? collect())->count() > 0)
                <h6 style="margin: 22px 0 12px 0; font-size: 15px; font-weight: 800; color: #0f172a;">Jatuh Tempo Terdekat</h6>
                <div style="display: grid; gap: 10px;">
                    @foreach ($upcomingDueDates->take(3) as $loan)
                        @foreach ($loan->loanItems->take(1) as $item)
                            @php($daysLeft = $loan->due_date ? now()->diffInDays($loan->due_date, false) : 0)
                            <div style="padding: 12px 14px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                                <strong style="display: block; color: #0f172a; font-size: 14px;">{{ $item->book->title ?? '-' }}</strong>
                                <span style="display: block; color: #64748b; font-size: 12px; margin-top: 4px;">
                                    Kembali {{ $loan->due_date?->translatedFormat('d M Y, H:i') ?? '-' }} WIB
                                </span>
                                <span style="display: inline-block; margin-top: 8px; color: {{ $daysLeft <= 1 ? '#991b1b' : '#92400e' }}; font-size: 12px; font-weight: 700;">
                                    @if ($daysLeft > 0)
                                        {{ $daysLeft }} hari lagi
                                    @elseif($daysLeft === 0)
                                        Jatuh tempo hari ini
                                    @else
                                        Terlewat {{ abs($daysLeft) }} hari
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    @if (($popularBooks ?? collect())->count() > 0)
        <div style="margin-bottom: 28px;">
            <h5 style="margin: 0 0 18px 0; font-size: 18px; font-weight: 800; color: #0f172a;">Buku Populer</h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 18px;">
                @foreach ($popularBooks as $book)
                    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);">
                        <div
                            style="aspect-ratio: 16 / 9; max-height: 150px; background: #e2e8f0; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            @if ($book->image_url)
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}"
                                    style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                            @else
                                <i class="fas fa-book" style="font-size: 40px; color: #2563eb;"></i>
                            @endif
                        </div>
                        <div style="padding: 16px;">
                            <strong style="display: block; color: #0f172a; font-size: 14px; line-height: 1.4;">{{ Str::limit($book->title, 42) }}</strong>
                            <span style="display: block; color: #64748b; font-size: 12px; margin-top: 6px;">{{ Str::limit($book->author ?? 'Tidak diketahui', 30) }}</span>
                            <a href="{{ route('siswa.tambah-peminjaman') }}"
                                style="display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; background: #eff6ff; color: #2563eb; padding: 8px 12px; border-radius: 10px; text-decoration: none; font-size: 12px; font-weight: 700;">
                                <i class="fas fa-plus"></i> Pinjam
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (($recentBooks ?? collect())->count() > 0)
        <div style="margin-bottom: 28px;">
            <h5 style="margin: 0 0 18px 0; font-size: 18px; font-weight: 800; color: #0f172a;">Buku Terbaru</h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 18px;">
                @foreach ($recentBooks as $book)
                    <div style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);">
                        <div
                            style="aspect-ratio: 4 / 3; background: #dbeafe; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            @if ($book->image_url)
                                <img src="{{ $book->image_url }}" alt="{{ $book->title }}"
                                    style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                            @else
                                <i class="fas fa-book-open" style="font-size: 40px; color: #2563eb;"></i>
                            @endif
                        </div>
                        <div style="padding: 16px;">
                            <strong style="display: block; color: #0f172a; font-size: 14px; line-height: 1.4;">{{ Str::limit($book->title, 42) }}</strong>
                            <span style="display: block; color: #64748b; font-size: 12px; margin-top: 6px;">{{ Str::limit($book->author ?? 'Tidak diketahui', 30) }}</span>
                            <a href="{{ route('siswa.tambah-peminjaman') }}"
                                style="display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; background: #ede9fe; color: #6d28d9; padding: 8px 12px; border-radius: 10px; text-decoration: none; font-size: 12px; font-weight: 700;">
                                <i class="fas fa-plus"></i> Pinjam
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if (!empty($announcements))
        <div style="background: white; border-radius: 16px; box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06); padding: 22px;">
            <h5 style="margin: 0 0 18px 0; font-size: 18px; font-weight: 800; color: #0f172a;">Pengumuman Terbaru</h5>
            <div style="display: grid; gap: 14px;">
                @foreach ($announcements as $announcement)
                    <div style="padding: 14px 16px; border-left: 4px solid #38bdf8; background: #f8fafc; border-radius: 12px;">
                        <div style="display: flex; justify-content: space-between; gap: 12px; align-items: flex-start;">
                            <strong style="color: #0f172a; font-size: 14px;">{{ $announcement['title'] }}</strong>
                            <span style="font-size: 12px; color: #64748b;">{{ \Carbon\Carbon::parse($announcement['date'])->format('d/m/Y') }}</span>
                        </div>
                        <p style="margin: 8px 0 0 0; color: #475569; font-size: 13px; line-height: 1.6;">
                            {{ Str::limit($announcement['content'], 120) }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
