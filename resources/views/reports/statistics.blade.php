@extends('dashboard.admin-layout')

@section('title')
    Laporan Statistik
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="page-header"
            style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700;">📊 Laporan Statistik Umum</h1>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Ringkasan data dan analisis peminjaman
                        secara keseluruhan</p>
                </div>
                <div style="font-size: 48px;">📈</div>
            </div>
        </div>

        <!-- Main Statistics -->
        <div class="row mb-4">
            <div class="col-md-2 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #0ea5e9; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🔧</div>
                    <div style="font-size: 24px; font-weight: 700; color: #0ea5e9;">
                        {{ $stats['total_equipment'] ?? Equipment::count() }}</div>
                    <div style="font-size: 12px; color: #666;">Total Buku</div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #06b6d4; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">👥</div>
                    <div style="font-size: 24px; font-weight: 700; color: #06b6d4;">{{ $stats['total_users'] }}</div>
                    <div style="font-size: 12px; color: #666;">Total Pengguna</div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #48bb78; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">📕</div>
                    <div style="font-size: 24px; font-weight: 700; color: #48bb78;">{{ $stats['total_loans'] }}</div>
                    <div style="font-size: 12px; color: #666;">Total Peminjaman</div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #f6ad55; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🟢</div>
                    <div style="font-size: 24px; font-weight: 700; color: #f6ad55;">{{ $stats['active_loans'] }}</div>
                    <div style="font-size: 12px; color: #666;">Aktif</div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #f56565; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">🔴</div>
                    <div style="font-size: 24px; font-weight: 700; color: #f56565;">{{ $stats['overdue_loans'] }}</div>
                    <div style="font-size: 12px; color: #666;">Terlambat</div>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #764ba2; text-align: center;">
                    <div style="font-size: 28px; margin-bottom: 8px;">✓</div>
                    <div style="font-size: 24px; font-weight: 700; color: #764ba2;">{{ $stats['completed_loans'] }}</div>
                    <div style="font-size: 12px; color: #666;">Selesai</div>
                </div>
            </div>
        </div>

        <!-- Row 2: Detailed Stats -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                    <h6 style="font-weight: 600; color: #333; margin-bottom: 15px;">📍 Stok Buku</h6>
                    <div class="mb-3">
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Tersedia</div>
                        <div style="font-size: 24px; font-weight: 700; color: #48bb78;">
                            {{ $stats['equipment_in_stock'] ?? Equipment::where('stock', '>', 0)->count() }}</div>
                    </div>
                    <hr style="margin: 15px 0;">
                    <div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Habis Stok</div>
                        <div style="font-size: 24px; font-weight: 700; color: #f56565;">
                            {{ $stats['equipment_out_of_stock'] ?? Equipment::where('stock', 0)->count() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                    <h6 style="font-weight: 600; color: #333; margin-bottom: 15px;">📊 Periode Peminjaman</h6>
                    <div class="mb-3">
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">30 Hari Terakhir</div>
                        <div style="font-size: 24px; font-weight: 700; color: #0ea5e9;">{{ $stats['total_loans_30days'] }}
                        </div>
                    </div>
                    <hr style="margin: 15px 0;">
                    <div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">90 Hari Terakhir</div>
                        <div style="font-size: 24px; font-weight: 700; color: #06b6d4;">{{ $stats['total_loans_90days'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
                    <h6 style="font-weight: 600; color: #333; margin-bottom: 15px;">👥 Peminjam</h6>
                    <div class="mb-3">
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Peminjam Aktif</div>
                        <div style="font-size: 24px; font-weight: 700; color: #667eea;">{{ $stats['active_borrowers'] }}
                        </div>
                    </div>
                    <hr style="margin: 15px 0;">
                    <div>
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">Peminjaman Tertinggi (1 Peminjam)
                        </div>
                        <div style="font-size: 24px; font-weight: 700; color: #764ba2;">{{ $stats['top_borrower_loans'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Borrowers & Books -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div
                    style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); overflow: hidden;">
                    <div
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px;">
                        <h6 style="margin: 0; font-weight: 600;">🏆 Top 5 Peminjam</h6>
                    </div>
                    <div style="padding: 20px;">
                        @foreach ($topBorrowers as $index => $borrower)
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; {{ $index < count($topBorrowers) - 1 ? 'border-bottom: 1px solid #e9ecef;' : '' }}">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span class="badge"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 10px;">{{ $index + 1 }}</span>
                                    <div>
                                        <strong style="display: block;">{{ $borrower->name }}</strong>
                                        <small style="color: #999;">{{ $borrower->email }}</small>
                                    </div>
                                </div>
                                <span class="badge"
                                    style="background: #e3f2fd; color: #667eea; padding: 6px 12px;">{{ $borrower->loans_as_borrower_count }}x</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div
                    style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); overflow: hidden;">
                    <div
                        style="background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%); color: white; padding: 20px;">
                        <h6 style="margin: 0; font-weight: 600;">⭐ Top 5 Buku Populer</h6>
                    </div>
                    <div style="padding: 20px;">
                        @foreach ($topEquipment as $index => $equipment)
                            <div {{ $index < count($topEquipment) - 1 ? 'border-bottom: 1px solid #e9ecef;' : '' }} <div
                                style="display: flex; align-items: center; gap: 12px;">
                                <span class="badge"
                                    style="background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%); color: white; padding: 6px 10px;">{{ $index + 1 }}</span>
                                <div>
                                    <strong style="display: block; color: #333;">{{ $equipment->nama_peralatan }}</strong>
                                    <small style="color: #999;">{{ $equipment->merk ?? 'N/A' }}</small>
                                </div>
                            </div>
                            <span class="badge"
                                style="background: #faf5ff; color: #805ad5; padding: 6px 12px;">{{ $equipment->loan_items_count }}x</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Export -->
    <div class="mt-4" style="text-align: center;">
        <button class="btn btn-primary"
            style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border: none; padding: 10px 30px; border-radius: 6px;">
            <i class="fas fa-download"></i> Export Laporan Lengkap
        </button>
    </div>
    </div>

    <style>
        .page-header {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
        }
    </style>
@endsection
