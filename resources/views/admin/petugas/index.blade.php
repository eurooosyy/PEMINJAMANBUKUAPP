@extends('dashboard.admin-layout')

@section('title')
    Manajemen Petugas
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-users-cog"></i> Manajemen Petugas</h1>
            <p>Kelola data petugas sistem peminjaman peralatan</p>
        </div>
        <a href="{{ route('petugas.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Tambah Petugas
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Petugas</h3>
            <div class="number">{{ count($petugases) }}</div>
        </div>
        <div class="stat-card success">
            <h3>Aktif</h3>
            <div class="number">{{ $petugases->count() }}</div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama</th>
                        <th style="width: 25%;">Email</th>
                        <th style="width: 15%;">Telepon</th>
                        <th style="width: 15%;">Tanggal Dibuat</th>
                        <th style="width: 15%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($petugases as $index => $petugas)
                        <tr>
                            <td>{{ ($petugases->currentPage() - 1) * $petugases->perPage() + $loop->iteration }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                        {{ substr($petugas->name, 0, 1) }}
                                    </div>
                                    <strong>{{ $petugas->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $petugas->email }}</td>
                            <td>{{ $petugas->phone ?? '-' }}</td>
                            <td>{{ $petugas->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('petugas.show', $petugas) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('petugas.edit', $petugas) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('petugas.destroy', $petugas) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus petugas ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center" style="padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                <p style="margin-top: 15px; color: #999;">Belum ada data petugas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($petugases->hasPages())
            <div style="margin-top: 20px; display: flex; justify-content: center;">
                {{ $petugases->links() }}
            </div>
        @endif
    </div>

    <style>
        /* Page Header */
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
            font-size: 28px;
            font-weight: 700;
        }

        .page-header p {
            color: #666;
            margin: 8px 0 0 0;
            font-size: 14px;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .stat-card h3 {
            font-size: 12px;
            color: #7c8fa0;
            margin: 0 0 10px 0;
            font-weight: 600;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-card.success {
            border-left-color: #28a745;
        }

        /* Table */
        .table-wrapper {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-wrapper .table-header {
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-wrapper .table-header h2 {
            margin: 0;
            font-size: 18px;
            color: #2d3748;
            font-weight: 700;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            font-weight: 700;
            color: #333;
            border-bottom: 2px solid #e9ecef;
            padding: 15px;
            font-size: 13px;
            text-transform: uppercase;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        .table tbody tr:hover {
            background-color: #f7fafc;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-action.btn-info {
            background: #4299e1;
            color: white;
        }

        .btn-action.btn-info:hover {
            background: #3182ce;
        }

        .btn-action.btn-warning {
            background: #ed8936;
            color: white;
        }

        .btn-action.btn-warning:hover {
            background: #dd6b20;
        }

        .btn-action.btn-danger {
            background: #f56565;
            color: white;
        }

        .btn-action.btn-danger:hover {
            background: #e53e3e;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 15px 20px;
            border: none;
        }

        .alert-success {
            background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
            color: #22543d;
            border-left: 4px solid #38a169;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fed7d7, #feb2b2);
            color: #742a2a;
            border-left: 4px solid #e53e3e;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
            opacity: 0.5;
        }

        .empty-state p {
            margin: 10px 0;
            font-size: 16px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            padding: 30px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            text-decoration: none;
            color: #667eea;
            transition: all 0.2s ease;
        }

        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination .active span {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
@endsection
