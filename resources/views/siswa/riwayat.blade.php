@extends('dashboard.siswa-layout')

@section('title', 'Riwayat Peminjaman')

@section('content')
    <div class="history-hero">
        <div>
            <span class="hero-kicker">Riwayat Siswa</span>
            <h1><i class="fas fa-history"></i> Riwayat Peminjaman Buku</h1>
            <p>Lihat seluruh aktivitas peminjaman Anda, termasuk buku aktif, yang sudah dikembalikan, dan durasi peminjamannya.</p>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card blue">
            <span>Total Peminjaman</span>
            <strong>{{ $loanSummary['total'] ?? 0 }}</strong>
        </div>
        <div class="summary-card green">
            <span>Masih Aktif</span>
            <strong>{{ $loanSummary['active'] ?? 0 }}</strong>
        </div>
        <div class="summary-card slate">
            <span>Sudah Kembali</span>
            <strong>{{ $loanSummary['returned'] ?? 0 }}</strong>
        </div>
    </div>

    <div class="panel-card">
        <div class="panel-head">
            <h2>Daftar Peminjaman</h2>
            <p>Riwayat ditampilkan berdasarkan tanggal pinjam terbaru.</p>
        </div>

        @if ($allLoans->count() > 0)
            <div class="history-list">
                @foreach ($allLoans as $loan)
                    @php
                        $duration = $loan->return_date
                            ? $loan->return_date->diffInDays($loan->loan_date)
                            : now()->diffInDays($loan->loan_date);
                    @endphp
                    <div class="history-card">
                        <div class="history-top">
                            <div>
                                <span class="history-id">Peminjaman #{{ $loan->id }}</span>
                                <h3>{{ $loan->loanItems->pluck('book.title')->filter()->join(', ') ?: 'Buku tidak tersedia' }}</h3>
                            </div>
                            <span class="status-badge {{ $loan->status === 'active' ? 'active' : ($loan->status === 'returned' ? 'returned' : 'other') }}">
                                {{ $loan->status === 'active' ? 'Aktif' : ($loan->status === 'returned' ? 'Dikembalikan' : ucfirst($loan->status)) }}
                            </span>
                        </div>

                        <div class="meta-grid">
                            <div>
                                <span>Pinjam</span>
                                <strong>{{ $loan->loan_date?->format('d/m/Y H:i') ?? '-' }}</strong>
                            </div>
                            <div>
                                <span>{{ $loan->return_date ? 'Dikembalikan' : 'Jatuh Tempo' }}</span>
                                <strong>{{ $loan->return_date?->format('d/m/Y H:i') ?? ($loan->due_date?->format('d/m/Y H:i') ?? '-') }}</strong>
                            </div>
                            <div>
                                <span>Durasi</span>
                                <strong>{{ $duration }} hari</strong>
                            </div>
                        </div>

                        <div class="book-list">
                            @foreach ($loan->loanItems as $item)
                                <div class="book-row">
                                    <div class="book-cover">
                                        @if ($item->book?->image_url)
                                            <img src="{{ $item->book->image_url }}" alt="{{ $item->book->title }}">
                                        @else
                                            <i class="fas fa-book"></i>
                                        @endif
                                    </div>
                                    <div class="book-copy">
                                        <strong>{{ $item->book->title ?? '-' }}</strong>
                                        <span>{{ $item->book->author ?? 'Penulis tidak diketahui' }} | Jumlah {{ $item->quantity ?? 1 }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($loan->status === 'active')
                            <form action="{{ route('siswa.kembalikan') }}" method="POST" class="action-row"
                                onsubmit="return confirm('Kembalikan buku ini sekarang?')">
                                @csrf
                                <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-undo me-1"></i> Kembalikan Buku
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $allLoans->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>Belum ada riwayat peminjaman</h4>
                <p>Riwayat peminjaman akan muncul setelah Anda meminjam buku dari katalog.</p>
            </div>
        @endif
    </div>

    <style>
        .history-hero,
        .panel-card,
        .history-card,
        .empty-state {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
        }

        .history-hero {
            padding: 28px;
            margin-bottom: 22px;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #60a5fa 100%);
            color: #fff;
        }

        .hero-kicker {
            display: inline-flex;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .history-hero h1 {
            margin: 0 0 8px 0;
            font-size: 30px;
            font-weight: 800;
        }

        .history-hero p {
            margin: 0;
            max-width: 680px;
            line-height: 1.7;
            opacity: 0.94;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .summary-card {
            padding: 18px 20px;
            border-radius: 18px;
            color: #fff;
        }

        .summary-card span {
            display: block;
            font-size: 13px;
        }

        .summary-card strong {
            display: block;
            margin-top: 8px;
            font-size: 30px;
            font-weight: 800;
        }

        .summary-card.blue { background: linear-gradient(135deg, #2563eb, #60a5fa); }
        .summary-card.green { background: linear-gradient(135deg, #16a34a, #4ade80); }
        .summary-card.slate { background: linear-gradient(135deg, #334155, #64748b); }

        .panel-card {
            padding: 22px;
        }

        .panel-head {
            margin-bottom: 18px;
        }

        .panel-head h2 {
            margin: 0 0 4px 0;
            font-size: 20px;
            color: #0f172a;
            font-weight: 800;
        }

        .panel-head p {
            margin: 0;
            color: #64748b;
            font-size: 13px;
        }

        .history-list {
            display: grid;
            gap: 16px;
        }

        .history-card {
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        .history-top {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .history-id {
            font-size: 12px;
            color: #64748b;
            font-weight: 700;
        }

        .history-top h3 {
            margin: 4px 0 0 0;
            font-size: 18px;
            color: #0f172a;
            font-weight: 800;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-badge.active { background: #dbeafe; color: #1d4ed8; }
        .status-badge.returned { background: #dcfce7; color: #166534; }
        .status-badge.other { background: #e2e8f0; color: #475569; }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .meta-grid div {
            padding: 14px;
            border-radius: 16px;
            background: #f8fafc;
        }

        .meta-grid span {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .meta-grid strong {
            color: #0f172a;
            font-size: 14px;
        }

        .book-list {
            display: grid;
            gap: 10px;
        }

        .book-row {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            background: #fff;
        }

        .book-cover {
            width: 48px;
            height: 64px;
            border-radius: 10px;
            overflow: hidden;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            flex-shrink: 0;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-copy {
            display: grid;
            gap: 4px;
        }

        .book-copy strong {
            color: #0f172a;
            font-size: 14px;
        }

        .book-copy span {
            color: #64748b;
            font-size: 12px;
        }

        .action-row {
            margin-top: 16px;
        }

        .empty-state {
            padding: 56px 24px;
            text-align: center;
        }

        .empty-state i {
            font-size: 52px;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        @media (max-width: 768px) {
            .summary-grid,
            .meta-grid {
                grid-template-columns: 1fr;
            }

            .history-top {
                flex-direction: column;
            }
        }
    </style>
@endsection
