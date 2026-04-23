@extends('dashboard.admin-layout')

@section('title')
    Laporan Pengembalian
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="page-header"
            style="background: linear-gradient(135deg, #48bb78 0%, #38a169 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700;">✓ Laporan Pengembalian</h1>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Detail buku yang telah dikembalikan peminjam
                    </p>
                </div>
                <div style="font-size: 48px;">📦</div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #48bb78;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Pengembalian</div>
                    <div style="font-size: 32px; font-weight: 700; color: #48bb78;">{{ $stats['total_returned'] }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #38a169;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Pengembalian 30 Hari</div>
                    <div style="font-size: 32px; font-weight: 700; color: #38a169;">{{ $stats['returned_30days'] }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #22543d;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Rata-rata Durasi</div>
                    <div style="font-size: 32px; font-weight: 700; color: #22543d;">{{ $stats['average_loan_duration'] }}
                        hari</div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-4"
            style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="🔍 Cari nama peminjam...">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" placeholder="Dari tanggal">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success" style="width: 100%;">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); overflow: hidden;">
            @if ($returnedLoans->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table table-hover" style="margin: 0;">
                        <thead style="background-color: #f0fdf4; border-bottom: 2px solid #dbeafe;">
                            <tr>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">No</th>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">Peminjam</th>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">Buku</th>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">Tgl Pinjam</th>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">Tgl Jatuh Tempo</th>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">Tgl Kembali</th>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">Durasi</th>
                                <th style="padding: 15px; color: #166534; font-weight: 600;">Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($returnedLoans as $index => $loan)
                                @php
                                    $duration = $loan->return_date->diffInDays($loan->loan_date);
                                    $isOnTime = $loan->return_date <= $loan->due_date;
                                @endphp
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 15px;">
                                        {{ ($returnedLoans->currentPage() - 1) * $returnedLoans->perPage() + $index + 1 }}
                                    </td>
                                    <td style="padding: 15px;">
                                        <strong>{{ $loan->borrower->name ?? '-' }}</strong><br>
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
                                    <td style="padding: 15px;">{{ $loan->loan_date?->format('d/m/Y') }}</td>
                                    <td style="padding: 15px;">{{ $loan->due_date?->format('d/m/Y') }}</td>
                                    <td style="padding: 15px;">
                                        <strong style="color: #48bb78;">{{ $loan->return_date?->format('d/m/Y') }}</strong>
                                    </td>
                                    <td style="padding: 15px;">
                                        <span class="badge"
                                            style="background: {{ $isOnTime ? '#e8f5e9' : '#ffebee' }}; color: {{ $isOnTime ? '#48bb78' : '#f56565' }}; padding: 6px 12px; border-radius: 4px;">
                                            {{ $duration }} hari
                                        </span>
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
                            Menampilkan {{ $returnedLoans->firstItem() }} hingga {{ $returnedLoans->lastItem() }} dari
                            {{ $returnedLoans->total() }} data
                        </div>
                        <nav>
                            {{ $returnedLoans->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            @else
                <div style="padding: 60px 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                    <h4 style="color: #666; margin-bottom: 10px;">Tidak ada data pengembalian</h4>
                    <p style="color: #999;">Belum ada peminjaman yang dikembalikan</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .page-header {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
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
