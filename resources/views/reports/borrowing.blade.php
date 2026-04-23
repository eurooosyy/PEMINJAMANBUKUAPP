@extends('dashboard.admin-layout')

@section('title')
    Laporan Peminjaman
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-file-alt"></i> Laporan Peminjaman Buku</h1>
        <p>Data lengkap semua peminjaman buku</p>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card primary">
                <p class="stat-label">Total Peminjaman</p>
                <p class="stat-number">{{ $stats['total_loans'] ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card success">
                <p class="stat-label">Aktif</p>
                <p class="stat-number">{{ $stats['active_loans'] ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card info">
                <p class="stat-label">Selesai</p>
                <p class="stat-number">{{ $stats['completed_loans'] ?? 0 }}</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card danger">
                <p class="stat-label">Terlambat</p>
                <p class="stat-number">{{ $stats['overdue_loans'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Borrowing Table -->
    <div class="row">
        <div class="col-12">
            <div class="table-wrapper">
                <h5><i class="fas fa-list"></i> Daftar Peminjaman</h5>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $loan)
                                @foreach ($loan->loanItems as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $loan->borrower->name ?? '-' }}</strong>
                                            <br><small class="text-muted">{{ $loan->borrower->email ?? '-' }}</small>
                                        </td>
                                        <td>{{ $item->book->title ?? '-' }}</td>
                                        <td>{{ $loan->loan_date?->format('d/m/Y H:i') ?? '-' }}</td>
                                        <td>{{ $loan->return_date?->format('d/m/Y H:i') ?? ($loan->due_date?->format('d/m/Y') ?? '-') }}
                                        </td>
                                        <td>
                                            @if ($loan->status === 'active')
                                                @php
                                                    $daysLeft = $loan->due_date?->diffInDays(now()) ?? 0;
                                                @endphp
                                                @if ($daysLeft < 0)
                                                    <span class="badge bg-danger">Terlambat {{ abs($daysLeft) }} hari</span>
                                                @else
                                                    <span class="badge bg-info">Aktif ({{ $daysLeft }} hari)</span>
                                                @endif
                                            @else
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada data peminjaman</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $loans->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .stat-card.success {
            border-left-color: #28a745;
        }

        .stat-card.info {
            border-left-color: #17a2b8;
        }

        .stat-card.danger {
            border-left-color: #dc3545;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin: 10px 0 0 0;
        }

        .stat-label {
            font-size: 13px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }

        .page-header h1 {
            color: #667eea;
            margin: 0;
            font-size: 24px;
        }

        .page-header p {
            color: #666;
            margin: 8px 0 0 0;
        }

        .table-wrapper {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .table-wrapper h5 {
            margin: 0 0 20px 0;
            font-weight: 700;
            color: #333;
        }
    </style>
@endsection
