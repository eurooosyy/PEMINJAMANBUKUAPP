@extends('dashboard.petugas-layout')

@section('title')
    Peminjaman
@endsection

@section('content')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }

        .page-header p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 14px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-left: 4px solid;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-box.active {
            border-color: #667eea;
        }

        .stat-box.overdue {
            border-color: #f56565;
        }

        .stat-box.returned {
            border-color: #48bb78;
        }

        .stat-box .stat-icon {
            font-size: 32px;
            opacity: 0.3;
        }

        .stat-box .stat-info h3 {
            margin: 0;
            font-size: 14px;
            color: #7c8fa0;
            font-weight: 600;
        }

        .stat-box .stat-info .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-top: 5px;
        }

        .table-wrapper {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .table-wrapper h5 {
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
        }

        .table thead th {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            font-weight: 600;
            font-size: 13px;
        }

        .table tbody td {
            border-color: #e2e8f0;
            vertical-align: middle;
            font-size: 13px;
        }

        .table tbody tr:hover {
            background: #f7fafc;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-group.btn-group-sm .btn {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 4px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: #cbd5e0;
            margin-bottom: 20px;
            display: block;
        }

        .empty-state p {
            color: #7c8fa0;
            font-size: 16px;
            margin: 0;
        }

        .info-panel {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .info-panel h5 {
            margin: 0 0 15px 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
        }

        .info-panel p {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #4a5568;
        }

        .info-panel p strong {
            color: #2d3748;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-hand-holding-heart"></i> Kelola Peminjaman</h1>
        <p>Monitor dan kelola semua peminjaman buku aktif</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-box active">
            <div>
                <div class="stat-info">
                    <h3>Peminjaman Aktif</h3>
                    <div class="stat-number">{{ $loans->total() }}</div>
                </div>
            </div>
            <i class="fas fa-hand-holding-heart stat-icon"></i>
        </div>

        <div class="stat-box overdue">
            <div>
                <div class="stat-info">
                    <h3>Terlambat</h3>
                    <div class="stat-number">
                        {{ $loans->where('due_date', '<', now())->count() }}
                    </div>
                </div>
            </div>
            <i class="fas fa-exclamation-triangle stat-icon"></i>
        </div>

        <div class="stat-box returned">
            <div>
                <div class="stat-info">
                    <h3>Selesai Hari Ini</h3>
                    <div class="stat-number">
                        {{ \App\Models\Loan::whereDate('return_date', today())->count() }}
                    </div>
                </div>
            </div>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="table-wrapper">
                <h5><i class="fas fa-list"></i> Daftar Peminjaman Aktif</h5>
                @if ($loans->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Peminjam</th>
                                    <th>Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $index => $loan)
                                    <tr>
                                        <td>{{ ($loans->currentPage() - 1) * $loans->perPage() + $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $loan->borrower->name ?? '-' }}</strong><br>
                                            <small class="text-muted">{{ $loan->borrower->email ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @foreach ($loan->loanItems as $item)
                                                <div class="mb-1">
                                                    <span
                                                        class="badge bg-light text-dark">{{ $item->book->title ?? '-' }}</span>
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>{{ $loan->loan_date?->format('d/m/Y') }}</td>
                                        <td>{{ $loan->due_date?->format('d/m/Y') }}</td>
                                        <td>
                                            @if ($loan->due_date < now())
                                                <span class="badge bg-danger">Terlambat</span>
                                            @else
                                                <span class="badge bg-success">Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="#" class="btn btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="#" class="btn btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $loans->links('pagination::bootstrap-4') }}
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Tidak ada peminjaman aktif saat ini</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-panel">
                <h5><i class="fas fa-info-circle"></i> Informasi Peminjaman</h5>
                <p><strong>Durasi Peminjaman:</strong> 7 hari</p>
                <p><strong>Denda keterlambatan:</strong> Rp. 1.000/hari</p>
                <p><strong>Maksimal buku dipinjam:</strong> 3 buku</p>
                <p><strong>Waktu operasional:</strong> Senin - Jumat, 08:00 - 16:00</p>
            </div>

            <div class="info-panel mt-3">
                <h5><i class="fas fa-bolt"></i> Tindakan Cepat</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('petugas.pengembalian') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-undo"></i> Proses Pengembalian
                    </a>
                    <a href="{{ route('reports.overdue') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-exclamation-triangle"></i> Lihat Terlambat
                    </a>
                    <a href="{{ route('reports.borrowing') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-bar"></i> Laporan Peminjaman
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
