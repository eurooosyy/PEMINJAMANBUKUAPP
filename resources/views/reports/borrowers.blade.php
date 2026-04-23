@extends('dashboard.admin-layout')

@section('title')
    Laporan Peminjam
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="page-header"
            style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700;">👥 Laporan Peminjam</h1>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Daftar peminjam dan aktivitas peminjaman
                        mereka</p>
                </div>
                <div style="font-size: 48px;">📋</div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #ed8936;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Peminjam</div>
                    <div style="font-size: 32px; font-weight: 700; color: #ed8936;">{{ $stats['total_borrowers'] }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #dd6b20;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Peminjam Aktif</div>
                    <div style="font-size: 32px; font-weight: 700; color: #dd6b20;">{{ $stats['active_borrowers'] }}</div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #c05621;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Peminjaman</div>
                    <div style="font-size: 32px; font-weight: 700; color: #c05621;">
                        {{ $stats['total_loans_all_borrowers'] }}</div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-4"
            style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="🔍 Cari nama peminjam atau email...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-warning" style="width: 100%; color: white;">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); overflow: hidden;">
            @if ($borrowers->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table table-hover" style="margin: 0;">
                        <thead style="background-color: #fffbeb; border-bottom: 2px solid #fde68a;">
                            <tr>
                                <th style="padding: 15px; color: #92400e; font-weight: 600;">No</th>
                                <th style="padding: 15px; color: #92400e; font-weight: 600;">Nama Peminjam</th>
                                <th style="padding: 15px; color: #92400e; font-weight: 600;">Email</th>
                                <th style="padding: 15px; color: #92400e; font-weight: 600;">Total Peminjaman</th>
                                <th style="padding: 15px; color: #92400e; font-weight: 600;">Peminjaman Terakhir</th>
                                <th style="padding: 15px; color: #92400e; font-weight: 600;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($borrowers as $index => $borrower)
                                @php
                                    $status = $borrower->loans_as_borrower_count > 0 ? 'Aktif' : 'Tidak Aktif';
                                    $statusColor = $borrower->loans_as_borrower_count > 0 ? '#e0f2fe' : '#f5f5f5';
                                @endphp
                                <tr style="border-bottom: 1px solid #e9ecef;">
                                    <td style="padding: 15px;">
                                        <span class="badge"
                                            style="background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%); color: white; padding: 6px 10px;">{{ ($borrowers->currentPage() - 1) * $borrowers->perPage() + $index + 1 }}</span>
                                    </td>
                                    <td style="padding: 15px;">
                                        <strong style="font-size: 15px; color: #333;">{{ $borrower->name }}</strong>
                                    </td>
                                    <td style="padding: 15px;">
                                        <code
                                            style="background: #f3f4f6; padding: 4px 8px; border-radius: 3px;">{{ $borrower->email }}</code>
                                    </td>
                                    <td style="padding: 15px;">
                                        <span class="badge"
                                            style="background: #fef3c7; color: #92400e; padding: 8px 14px; font-size: 15px; border-radius: 4px; font-weight: 600;">
                                            {{ $borrower->loans_as_borrower_count }}x
                                        </span>
                                    </td>
                                    <td style="padding: 15px;">
                                        @if ($borrower->loansAsBorrower->first())
                                            <small style="color: #666;">
                                                📚
                                                {{ $borrower->loansAsBorrower->first()->created_at?->format('d/m/Y H:i') }}
                                            </small>
                                        @else
                                            <small style="color: #999;">-</small>
                                        @endif
                                    </td>
                                    <td style="padding: 15px;">
                                        @if ($borrower->loans_as_borrower_count > 0)
                                            <span class="badge"
                                                style="background: #e0f2fe; color: #0369a1; padding: 6px 12px; border-radius: 4px;">Aktif</span>
                                        @else
                                            <span class="badge"
                                                style="background: #f3f4f6; color: #666; padding: 6px 12px; border-radius: 4px;">Tidak
                                                Aktif</span>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Expanded Detail: Recent Loans -->
                                @if ($borrower->loansAsBorrower->count() > 0)
                                    <tr style="background-color: #fafafa; border-bottom: 1px solid #e9ecef;">
                                        <td colspan="6" style="padding: 15px; padding-left: 50px;">
                                            <div style="font-size: 13px; color: #666;">
                                                <strong style="display: block; margin-bottom: 10px;">📕 Peminjaman
                                                    Terakhir:</strong>
                                                <div style="margin-left: 20px;">
                                                    @foreach ($borrower->loansAsBorrower->take(3) as $loan)
                                                        <div
                                                            style="margin-bottom: 8px; padding: 10px; background: white; border-left: 3px solid #ed8936; border-radius: 4px;">
                                                            <div style="display: flex; justify-content: space-between;">
                                                                <div>
                                                                    @foreach ($loan->loanItems as $item)
                                                                        <small
                                                                            style="display: block; color: #333;">{{ $item->book->title ?? 'Unknown' }}</small>
                                                                    @endforeach
                                                                </div>
                                                                <div style="text-align: right;">
                                                                    <small
                                                                        style="display: block; color: #666;">{{ $loan->loan_date?->format('d/m/Y') }}</small>
                                                                    <span class="badge"
                                                                        style="background: {{ $loan->status === 'active' ? '#e0f2fe' : '#e8f5e9' }}; color: {{ $loan->status === 'active' ? '#0369a1' : '#166534' }}; padding: 4px 8px; margin-top: 4px;">
                                                                        {{ $loan->status === 'active' ? 'Aktif' : 'Selesai' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 20px; border-top: 1px solid #e9ecef;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="color: #666; font-size: 14px;">
                            Menampilkan {{ $borrowers->firstItem() }} hingga {{ $borrowers->lastItem() }} dari
                            {{ $borrowers->total() }} data
                        </div>
                        <nav>
                            {{ $borrowers->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            @else
                <div style="padding: 60px 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">👥</div>
                    <h4 style="color: #666; margin-bottom: 10px;">Tidak ada data peminjam</h4>
                    <p style="color: #999;">Belum ada peminjam terdaftar</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .page-header {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
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
