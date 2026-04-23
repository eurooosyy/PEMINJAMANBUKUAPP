@extends('dashboard.admin-layout')

@section('title')
    Dashboard Admin - Peminjaman Buku Perpustakaan
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>
        <p>Ringkasan sistem peminjaman buku perpustakaan sekolah</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card primary">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">Total Pengguna</p>
                        <p class="stat-number">{{ $totalUsers ?? 0 }}</p>
                    </div>
                    <i class="fas fa-users stat-icon text-primary"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card success">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">Total Petugas</p>
                        <p class="stat-number">{{ $totalPetugas ?? 0 }}</p>
                    </div>
                    <i class="fas fa-user-tie stat-icon text-success"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card info">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">Total Buku</p>
                        <p class="stat-number">{{ $totalBooks ?? 0 }}</p>
                    </div>
                    <i class="fas fa-book stat-icon text-info"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card warning">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">Peminjaman Aktif</p>
                        <p class="stat-number">{{ $activeLoans ?? 0 }}</p>
                    </div>
                    <i class="fas fa-hourglass-half stat-icon text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics -->
    <div class="row mb-4">
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="info-card">
                <div class="info-icon danger">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="info-content">
                    <p class="info-label">Buku Terlambat</p>
                    <p class="info-number">{{ $overdueLoans ?? 0 }} Peminjaman</p>
                    <small class="info-desc">Pantau buku yang harus segera dikembalikan</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12 mb-3">
            <div class="info-card">
                <div class="info-icon secondary">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="info-content">
                    <p class="info-label">Buku Dikembalikan</p>
                    <p class="info-number">{{ $returnedToday ?? 0 }} Hari Ini</p>
                    <small class="info-desc">Pemantauan pengembalian harian</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="action-card">
                <h5><i class="fas fa-bolt"></i> Tindakan Cepat</h5>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('petugas.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Tambah Petugas
                    </a>
                    <a href="{{ route('books.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle"></i> Tambah Buku
                    </a>
                    <a href="{{ route('petugas.index') }}" class="btn btn-info">
                        <i class="fas fa-users-cog"></i> Petugas
                    </a>
                    <a href="{{ route('books.index') }}" class="btn btn-warning">
                        <i class="fas fa-book"></i> Daftar Buku
                    </a>
                    <a href="{{ route('reports.statistics') }}" class="btn btn-secondary">
                        <i class="fas fa-chart-bar"></i> Statistik
                    </a>
                    <a href="{{ route('reports.overdue') }}" class="btn btn-danger">
                        <i class="fas fa-exclamation-circle"></i> Buku Terlambat
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 col-sm-12 mb-3">
            <div class="info-card">
                <div class="info-icon info">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="info-content">
                    <p class="info-label">Kategori Teratas</p>
                    <p class="info-number">{{ $topCategory->category ?? 'Belum ada' }}</p>
                    <small class="info-desc">{{ $topCategory->total ?? 0 }} buku dalam kategori ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-12 mb-3">
            <div class="action-card">
                <h5><i class="fas fa-layer-group"></i> Distribusi Kategori Buku</h5>
                @if (($categoryStats ?? collect())->count() > 0)
                    <div class="category-grid">
                        @foreach ($categoryStats as $item)
                            @php($style = \App\Models\Book::categoryBadgeStyle($item->category))
                            <div class="category-pill">
                                <span class="category-badge"
                                    style="background: {{ $style['background'] }}; color: {{ $style['color'] }};">
                                    {{ $item->category }}
                                </span>
                                <strong>{{ $item->total }}</strong>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted mb-0">Belum ada data kategori buku.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Menu Laporan -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="fas fa-chart-line"></i> Akses Laporan</h5>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ route('reports.borrowing') }}" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h6>Laporan Peminjaman</h6>
                <p>Lihat semua data peminjaman Buku</p>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ route('reports.popular-equipment') }}" class="menu-card">
                <div class="menu-icon success">
                    <i class="fas fa-star"></i>
                </div>
                <h6>Buku Populer</h6>
                <p>Buku paling sering dipinjam</p>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ route('reports.statistics') }}" class="menu-card">
                <div class="menu-icon info">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h6>Statistik Umum</h6>
                <p>Ringkasan data keseluruhan sistem</p>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ route('reports.overdue') }}" class="menu-card">
                <div class="menu-icon danger">
                    <i class="fas fa-clock"></i>
                </div>
                <h6>Buku Terlambat</h6>
                <p>Pantau peralatan yang terlambat dikembalikan</p>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ route('reports.returns') }}" class="menu-card">
                <div class="menu-icon">
                    <i class="fas fa-undo"></i>
                </div>
                <h6>Pengembalian</h6>
                <p>Data buku yang sudah dikembalikan</p>
            </a>
        </div>
        <div class="col-md-4 col-sm-6 mb-3">
            <a href="{{ route('reports.borrowers') }}" class="menu-card">
                <div class="menu-icon secondary">
                    <i class="fas fa-users"></i>
                </div>
                <h6>Data Peminjam</h6>
                <p>Informasi semua peminjam aktif</p>
            </a>
        </div>
    </div>

    <style>
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

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border-left: 5px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .stat-card.success {
            border-left-color: #28a745;
        }

        .stat-card.info {
            border-left-color: #17a2b8;
        }

        .stat-card.warning {
            border-left-color: #ffc107;
        }

        .stat-number {
            font-size: 32px;
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

        .stat-icon {
            font-size: 32px;
            opacity: 0.15;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .info-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
            flex-shrink: 0;
        }

        .info-icon.danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .info-icon.secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
        }

        .info-icon.info {
            background: linear-gradient(135deg, #0ea5e9, #2563eb);
        }

        .info-content {
            flex: 1;
        }

        .info-number {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin: 5px 0;
        }

        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .info-desc {
            color: #666;
            display: block;
            margin-top: 4px;
        }

        .action-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .action-card h5 {
            margin: 0 0 15px 0;
            font-weight: 700;
            color: #333;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 12px;
        }

        .category-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
        }

        .category-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .action-card .btn {
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            font-size: 14px;
        }

        .action-card .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .menu-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            text-align: center;
            border-top: 4px solid #667eea;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .menu-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            color: #667eea;
        }

        .menu-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .menu-icon.success {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .menu-icon.info {
            background: linear-gradient(135deg, #17a2b8, #138496);
        }

        .menu-icon.danger {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .menu-icon.secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
        }

        .menu-card h6 {
            margin: 0;
            font-weight: 700;
            font-size: 16px;
            color: #333;
        }

        .menu-card p {
            margin: 0;
            font-size: 13px;
            color: #666;
        }

        .menu-card:hover h6 {
            color: #667eea;
        }

        @media (max-width: 768px) {
            .stat-number {
                font-size: 28px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .action-card .btn {
                font-size: 12px;
                padding: 8px 12px;
            }
        }

    @endsection
