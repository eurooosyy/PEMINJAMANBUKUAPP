@extends('dashboard.admin-layout')

@section('title')
    Laporan & Statistik
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> Laporan & Statistik Perpustakaan</h1>
        <p>Akses berbagai laporan dan analisis data peminjaman</p>
    </div>

    <!-- Reports Grid -->
    <div class="row">
        <!-- Laporan Peminjaman -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="report-card">
                <div class="report-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h5 class="report-title">Laporan Peminjaman</h5>
                <p class="report-desc">Lihat semua data peminjaman buku dengan detail lengkap</p>
                <a href="{{ route('reports.borrowing') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-right"></i> Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Terlambat -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="report-card">
                <div class="report-icon warning">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h5 class="report-title">Buku Terlambat</h5>
                <p class="report-desc">Pantau buku-buku yang belum dikembalikan melampaui tanggal jatuh tempo</p>
                <a href="{{ route('reports.overdue') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-arrow-right"></i> Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Buku Populer -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="report-card">
                <div class="report-icon success">
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="report-title">Buku Populer</h5>
                <p class="report-desc">Analisis buku yang paling sering dipinjam oleh pengguna</p>
                <a href="{{ route('reports.popular-equipment') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-arrow-right"></i> Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Statistik -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="report-card">
                <div class="report-icon info">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h5 class="report-title">Statistik Umum</h5>
                <p class="report-desc">Ringkasan statistik keseluruhan sistem peminjaman</p>
                <a href="{{ route('reports.statistics') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-arrow-right"></i> Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Pengembalian -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="report-card">
                <div class="report-icon secondary">
                    <i class="fas fa-undo"></i>
                </div>
                <h5 class="report-title">Laporan Pengembalian</h5>
                <p class="report-desc">Data pengembalian buku dengan riwayat lengkap</p>
                <a href="{{ route('reports.returns') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-right"></i> Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Laporan Peminjam -->
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="report-card">
                <div class="report-icon info">
                    <i class="fas fa-users"></i>
                </div>
                <h5 class="report-title">Laporan Peminjam</h5>
                <p class="report-desc">Data peminjam dengan riwayat peminjaman mereka</p>
                <a href="{{ route('reports.borrowers') }}" class="btn btn-info btn-sm">
                    <i class="fas fa-arrow-right"></i> Lihat Laporan
                </a>
            </div>
        </div>
    </div>

    <style>
        .report-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-top: 4px solid #667eea;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .report-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .report-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 15px;
        }

        .report-icon.warning {
            background: linear-gradient(135deg, #f39c12, #e74c3c);
        }

        .report-icon.success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        .report-icon.info {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .report-icon.secondary {
            background: linear-gradient(135deg, #95a5a6, #34495e);
        }

        .report-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .report-desc {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
            flex-grow: 1;
            line-height: 1.5;
        }

        .report-card .btn {
            align-self: flex-start;
            font-size: 13px;
            padding: 8px 16px;
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
            font-size: 28px;
            font-weight: 700;
        }

        .page-header p {
            color: #666;
            margin: 8px 0 0 0;
            font-size: 14px;
        }
    </style>
@endsection
