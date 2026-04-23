@extends('admin.layout')

@section('title', 'Buku Populer - Laporan')

@section('content')
    <style>
        .page-header {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-top: 4px solid #48bb78;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 36px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-header {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 25px 30px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-header h2 {
            margin: 0;
            font-size: 22px;
            color: #2d3748;
            font-weight: 700;
        }

        .rank-badge {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
        }

        .table {
            width: 100%;
        }

        .table thead th {
            background: #f7fafc;
            padding: 20px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            font-size: 13px;
            text-transform: uppercase;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody td {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8faff;
        }

        .book-cover {
            width: 60px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .book-info {
            margin-left: 15px;
        }

        .book-title {
            font-weight: 600;
            color: #2d3748;
            margin: 0 0 4px 0;
            font-size: 15px;
        }

        .book-author {
            color: #718096;
            font-size: 13px;
            margin: 0;
        }

        .borrow-count {
            font-weight: 700;
            color: #48bb78;
            font-size: 18px;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .empty-state {
            text-align: center;
            padding: 80px 40px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .pagination {
            padding: 30px;
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Buku Populer</h1>
        <p>Laporan buku yang paling sering dipinjam oleh siswa</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_equipment'] ?? 0 }}</div>
            <div class="stat-label">Total Buku</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total_borrowed'] ?? 0 }}</div>
            <div class="stat-label">Total Peminjaman</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['most_borrowed_count'] ?? 0 }}</div>
            <div class="stat-label">Terpopuler</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['average_borrows_per_equipment'] ?? 0 }}</div>
            <div class="stat-label">Rata-rata Peminjaman/Buku</div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="table-container">
        <div class="table-header">
            <h2><i class="fas fa-trophy"></i> Ranking Buku Paling Populer</h2>
            <div style="color: #718096; font-size: 14px;">
                {{ $popularEquipment->total() }} buku |
                <strong>{{ $stats['most_borrowed_count'] ?? 0 }}</strong> peminjaman terbanyak
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">Rank</th>
                    <th style="width: 20%;">Buku</th>
                    <th style="width: 25%;">Penulis</th>
                    <th>Peminjaman</th>
                    <th style="width: 20%;">Terakhir Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($popularEquipment as $index => $book)
                    <tr>
                        <td>
                            <div class="rank-badge">
                                {{ $loop->iteration }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <div class="book-cover">
                                    @if ($book->image)
                                        <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div
                                            style="width: 100%; height: 100%; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book" style="color: #cbd5e0; font-size: 24px;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="book-info">
                                    <div class="book-title">{{ Str::limit($book->title, 40) }}</div>
                                    @if ($book->category)
                                        <span class="badge bg-light text-dark"
                                            style="font-size: 11px; padding: 3px 8px;">{{ $book->category }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="color: #718096; font-size: 14px;">
                                {{ Str::limit($book->author ?? 'Tidak diketahui', 30) }}
                            </div>
                            @if ($book->isbn)
                                <small style="color: #a0aec0;">ISBN: {{ $book->isbn }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="borrow-count">{{ $book->loan_items_count }}x</div>
                            <small style="color: #718096;">{{ number_format($book->loan_items_count) }} peminjaman</small>
                        </td>
                        <td>
                            @if ($book->loanItems->isNotEmpty())
                                <div style="color: #48bb78; font-weight: 600;">
                                    {{ $book->loanItems->first()->loan->loan_date?->format('d M Y') ?? '-' }}
                                </div>
                                <small
                                    style="color: #a0aec0;">{{ $book->loanItems->first()->loan->created_at?->diffForHumans() }}</small>
                            @else
                                <span style="color: #a0aec0;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="border: none;">
                            <div class="empty-state">
                                <i class="fas fa-chart-line"></i>
                                <h3 style="color: #2d3748; margin-bottom: 10px;">Belum ada data peminjaman</h3>
                                <p style="color: #718096; font-size: 16px;">Buku populer akan muncul setelah ada riwayat
                                    peminjaman</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if (method_exists($popularEquipment, 'links'))
            <div class="pagination">
                {{ $popularEquipment->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
