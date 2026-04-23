@extends('dashboard.petugas-layout')

@section('title')
    Pengembalian
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

        .stat-box.due {
            border-color: #f6ad55;
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

        .fine-amount {
            color: #f56565;
            font-weight: 700;
        }

        .overdue-row {
            background-color: #fef5e7 !important;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-undo"></i> Proses Pengembalian</h1>
        <p>Kelola pengembalian buku dan hitung denda keterlambatan</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-box due">
            <div>
                <div class="stat-info">
                    <h3>Jatuh Tempo Hari Ini</h3>
                    <div class="stat-number">
                        {{ $loans->where('due_date', '>=', today())->where('due_date', '<', today()->addDay())->count() }}
                    </div>
                </div>
            </div>
            <i class="fas fa-calendar-day stat-icon"></i>
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
                    <h3>Dikembalikan Hari Ini</h3>
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
                <h5><i class="fas fa-list"></i> Buku yang Harus Dikembalikan</h5>
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
                                    <th>Terlambat</th>
                                    <th>Denda</th>
                                    <th>Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $index => $loan)
                                    @php
                                        $daysOverdue = max(0, now()->diffInDays($loan->due_date, false));
                                        $fine = $daysOverdue * 1000; // Rp 1000 per hari
                                    @endphp
                                    <tr class="{{ $daysOverdue > 0 ? 'overdue-row' : '' }}">
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
                                            @if ($daysOverdue > 0)
                                                <span class="badge bg-danger">{{ $daysOverdue }} hari</span>
                                            @else
                                                <span class="badge bg-success">Tepat Waktu</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($fine > 0)
                                                <span class="fine-amount">Rp {{ number_format($fine, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-success" title="Kembalikan"
                                                    onclick="returnBook({{ $loan->id }})">
                                                    <i class="fas fa-check"></i> Kembali
                                                </button>
                                                <a href="#" class="btn btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
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
                        <p>Tidak ada buku yang harus dikembalikan saat ini</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="info-panel">
                <h5><i class="fas fa-info-circle"></i> Informasi Pengembalian</h5>
                <p><strong>Durasi Peminjaman:</strong> 7 hari</p>
                <p><strong>Denda keterlambatan:</strong> Rp. 1.000/hari</p>
                <p><strong>Waktu pengembalian:</strong> Maksimal pukul 16:00</p>
                <p><strong>Prosedur:</strong> Periksa kondisi buku sebelum menerima</p>
            </div>

            <div class="info-panel mt-3">
                <h5><i class="fas fa-bolt"></i> Tindakan Cepat</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('petugas.peminjaman') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-hand-holding-heart"></i> Lihat Peminjaman
                    </a>
                    <a href="{{ route('reports.overdue') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-exclamation-triangle"></i> Laporan Terlambat
                    </a>
                    <a href="{{ route('reports.returns') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-chart-bar"></i> Laporan Pengembalian
                    </a>
                </div>
            </div>

            <div class="info-panel mt-3">
                <h5><i class="fas fa-calculator"></i> Kalkulator Denda</h5>
                <div class="mb-3">
                    <label for="daysOverdue" class="form-label">Hari Terlambat</label>
                    <input type="number" class="form-control" id="daysOverdue" placeholder="0" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label">Total Denda</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="text" class="form-control" id="fineAmount" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('daysOverdue').addEventListener('input', function() {
            const days = parseInt(this.value) || 0;
            const fine = days * 1000;
            document.getElementById('fineAmount').value = fine.toLocaleString('id-ID');
        });

        function returnBook(loanId) {
            if (confirm('Apakah Anda yakin ingin memproses pengembalian buku ini?')) {
                // Here you would typically send an AJAX request to process the return
                alert('Fitur pengembalian buku akan diimplementasikan.');
            }
        }
    </script>

@endsection
