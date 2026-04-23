@extends('dashboard.admin-layout')

@section('title')
    Laporan Keterlambatan
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="page-header"
            style="background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700;">⏰ Laporan Keterlambatan</h1>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Daftar peminjaman yang terlambat
                        dikembalikan</p>
                </div>
                <div style="font-size: 48px;">🔴</div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #f56565;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Terlambat</div>
                    <div style="font-size: 32px; font-weight: 700; color: #f56565;">{{ $stats['total_overdue'] }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #ed8936;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Denda</div>
                    <div style="font-size: 32px; font-weight: 700; color: #ed8936;">Rp
                        {{ number_format($stats['total_fine'], 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #9f7aea;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Peminjam Terlambat</div>
                    <div style="font-size: 32px; font-weight: 700; color: #9f7aea;">{{ $stats['borrowers_with_overdue'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-4"
            style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="🔍 Cari nama peminjam...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-danger" style="width: 100%;">
                        <i class="fas fa-download"></i> Export Laporan
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); overflow: hidden;">
            @if ($overdueLoans->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table table-hover" style="margin: 0;">
                        <thead style="background-color: #fff5f5; border-bottom: 2px solid #fed7d7;">
                            <tr>
                                <th style="padding: 15px; color: #862e2e; font-weight: 600;">No</th>
                                <th style="padding: 15px; color: #862e2e; font-weight: 600;">Peminjam</th>
                                <th style="padding: 15px; color: #862e2e; font-weight: 600;">Buku</th>
                                <th style="padding: 15px; color: #862e2e; font-weight: 600;">Tgl Jatuh Tempo</th>
                                <th style="padding: 15px; color: #862e2e; font-weight: 600;">Hari Terlambat</th>
                                <th style="padding: 15px; color: #862e2e; font-weight: 600;">Denda</th>
                                <th style="padding: 15px; color: #862e2e; font-weight: 600;">Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($overdueLoans as $index => $loan)
                                <tr
                                    style="border-bottom: 1px solid #e9ecef; background-color: {{ $loan->days_overdue > 7 ? '#fff5f5' : '' }};">
                                    <td style="padding: 15px;">
                                        {{ ($overdueLoans->currentPage() - 1) * $overdueLoans->perPage() + $index + 1 }}
                                    </td>
                                    <td style="padding: 15px;">
                                        <strong style="color: #c53030;">{{ $loan->borrower->name ?? '-' }}</strong><br>
                                        <small style="color: #999;">{{ $loan->borrower->email ?? '-' }}</small>
                                    </td>
                                    <td style="padding: 15px;">
                                        <div>
                                            @foreach ($loan->loanItems as $item)
                                                <small style="display: block; color: #333;">•
                                                    {{ $item->book->title ?? 'Unknown' }}</small>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td style="padding: 15px;">{{ $loan->due_date?->format('d/m/Y') }}</td>
                                    <td style="padding: 15px;">
                                        <span class="badge"
                                            style="background: #ffebee; color: #f56565; padding: 6px 12px; border-radius: 4px;">
                                            {{ $loan->days_overdue }} hari
                                        </span>
                                    </td>
                                    <td style="padding: 15px;">
                                        <strong style="color: #ed8936;">Rp
                                            {{ number_format($loan->fine_amount, 0, ',', '.') }}</strong>
                                    </td>
                                    <td style="padding: 15px;">{{ $loan->petugas->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 20px; border-top: 1px solid #e9ecef;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="color: #666; font-size: 14px;">
                            Menampilkan {{ $overdueLoans->firstItem() }} hingga {{ $overdueLoans->lastItem() }} dari
                            {{ $overdueLoans->total() }} data
                        </div>
                        <nav>
                            {{ $overdueLoans->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            @else
                <div style="padding: 60px 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">✅</div>
                    <h4 style="color: #48bb78; margin-bottom: 10px;">Tidak ada peminjaman terlambat</h4>
                    <p style="color: #999;">Semua peminjaman tepat waktu</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .page-header {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        }

        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
        }

        table tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endsection
