@extends('dashboard.admin-layout')

@section('title')
    Laporan Buku Populer
@endsection

@section('content')
    <div class="container-fluid mt-4">
        <!-- Header -->
        <div class="page-header"
            style="background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="margin: 0; font-size: 32px; font-weight: 700;">⭐ Laporan Buku Populer</h1>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Daftar buku yang paling banyak dipinjam</p>
                </div>
                <div style="font-size: 48px;">📚</div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #805ad5;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Buku</div>
                    <div style="font-size: 32px; font-weight: 700; color: #805ad5;">{{ $stats['total_books'] }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #ed64a6;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Peminjaman</div>
                    <div style="font-size: 32px; font-weight: 700; color: #ed64a6;">{{ $stats['total_borrowed'] }}</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #f6ad55;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Peminjaman Tertinggi</div>
                    <div style="font-size: 32px; font-weight: 700; color: #f6ad55;">{{ $stats['most_borrowed_count'] }}
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card"
                    style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border-left: 4px solid #48bb78;">
                    <div style="font-size: 14px; color: #666; margin-bottom: 8px;">Rata-rata</div>
                    <div style="font-size: 32px; font-weight: 700; color: #48bb78;">{{ $stats['average_borrows_per_book'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="mb-4"
            style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" placeholder="🔍 Cari judul buku...">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-primary"
                        style="width: 100%; background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%); border: none;">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); overflow: hidden;">
            @if ($popularBooks->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="table table-hover" style="margin: 0;">
                        <thead style="background-color: #faf5ff; border-bottom: 2px solid #ede9fe;">
                            <tr>
                                <th style="padding: 15px; color: #553399; font-weight: 600;">No</th>
                                <th style="padding: 15px; color: #553399; font-weight: 600;">Buku</th>
                                <th style="padding: 15px; color: #553399; font-weight: 600;">Pengarang</th>
                                <th style="padding: 15px; color: #553399; font-weight: 600;">Penerbit</th>
                                <th style="padding: 15px; color: #553399; font-weight: 600;">ISBN</th>
                                <th style="padding: 15px; color: #553399; font-weight: 600;">Jumlah Dipinjam</th>
                                <th style="padding: 15px; color: #553399; font-weight: 600;">Peminjaman Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($popularBooks as $index => $book)
                                <tr
                                    style="border-bottom: 1px solid #e9ecef; {{ $index < 3 ? 'background-color: #faf5ff;' : '' }}">
                                    <td style="padding: 15px;">
                                        <span class="badge"
                                            style="background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%); color: white; padding: 6px 10px;">{{ ($popularBooks->currentPage() - 1) * $popularBooks->perPage() + $index + 1 }}</span>
                                    </td>
                                    <td style="padding: 15px;">
                                        <strong>{{ $book->title }}</strong><br>
                                        <small style="color: #999;">{{ $book->isbn ?? 'No ISBN' }}</small>
                                    </td>
                                    <td style="padding: 15px;">{{ $book->author }}</td>
                                    <td style="padding: 15px;">{{ $book->publisher }}</td>
                                    <td style="padding: 15px;"><code
                                            style="background: #f3e5f5; padding: 4px 8px; border-radius: 3px;">{{ $book->isbn }}</code>
                                    </td>
                                    <td style="padding: 15px;">
                                        <span class="badge"
                                            style="background: #e9d8fd; color: #553399; padding: 8px 14px; font-size: 16px; border-radius: 4px;">
                                            {{ $book->loan_items_count ?? 0 }}x
                                        </span>
                                    </td>
                                    <td style="padding: 15px;">
                                        @if ($book->loanItems->first())
                                            {{ $book->loanItems->first()->created_at?->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 20px; border-top: 1px solid #e9ecef;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="color: #666; font-size: 14px;">
                            Menampilkan {{ $popularBooks->firstItem() }} hingga {{ $popularBooks->lastItem() }} dari
                            {{ $popularBooks->total() }} data
                        </div>
                        <nav>
                            {{ $popularBooks->links('pagination::bootstrap-4') }}
                        </nav>
                    </div>
                </div>
            @else
                <div style="padding: 60px 20px; text-align: center;">
                    <div style="font-size: 48px; margin-bottom: 15px;">📭</div>
                    <h4 style="color: #666; margin-bottom: 10px;">Belum ada data buku</h4>
                    <p style="color: #999;">Belum ada buku yang dipinjam</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .page-header {
            background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);
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
