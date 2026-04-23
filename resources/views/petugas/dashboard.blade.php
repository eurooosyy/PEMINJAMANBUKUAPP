@extends('dashboard.petugas-layout')

@section('title', 'Dashboard Petugas')

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard Petugas</h1>
        <p>Ringkasan aktivitas peminjaman buku hari ini</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-cogs fa-3x mb-3 opacity-75"></i>
                    <h3 class="display-4">{{ $totalBooks }}</h3>
                    <p class="mb-0">Total Buku</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x mb-3 opacity-75"></i>
                    <h3 class="display-4">{{ $availableBooks }}</h3>
                    <p class="mb-0">Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-hourglass-half fa-3x mb-3 opacity-75"></i>
                    <h3 class="display-4">{{ $activeLoans }}</h3>
                    <p class="mb-0">Peminjaman Aktif</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card danger text-white">
                <div class="card-body text-center">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3 opacity-75"></i>
                    <h3 class="display-4">{{ $overdueLoans }}</h3>
                    <p class="mb-0">Terlambat</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Peminjaman Aktif -->
            <div class="card shadow-lg mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Peminjaman Aktif (Hari Ini)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Siswa</th>
                                <th>Buku/th>
                                <th>Tgl Pinjam</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $todaysLoans = \App\Models\Loan::with(['borrower', 'loanItems.equipment'])
                                    ->whereDate('loan_date', today())
                                    ->limit(5)
                                    ->get();
                            @endphp
                            @forelse($todaysLoans as $loan)
                                <tr>
                                    <td><strong>#{{ $loan->id }}</strong></td>
                                    <td>{{ $loan->borrower->name }}</td>
                                    <td>
                                        @foreach ($loan->loanItems as $item)
                                            {{ $item->equipment->nama_peralatan }}<br>
                                        @endforeach
                                    </td>
                                    <td>{{ $loan->loan_date->format('d/m H:i') }}</td>
                                    <td>
                                        @if ($loan->due_date->isToday())
                                            <span class="badge bg-warning">Hari ini</span>
                                        @elseif($loan->due_date->gt(now()))
                                            {{ $loan->due_date->diffForHumans() }}
                                        @else
                                            <span class="badge bg-danger">{{ $loan->due_date->diffForHumans() }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($loan->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Tidak ada peminjaman hari ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-bolt me-2"></i>Tindakan Cepat</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('petugas.peralatan') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-cogs text-info me-3"></i>
                        <div>
                            <div class="fw-bold">Kelola Buku</div>
                            <small class="text-muted">Lihat & update stok</small>
                        </div>
                    </a>
                    <a href="{{ route('petugas.peminjaman') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-handshake text-warning me-3"></i>
                        <div>
                            <div class="fw-bold">Peminjaman Aktif</div>
                            <small class="text-muted">Proses peminjaman baru</small>
                        </div>
                    </a>
                    <a href="{{ route('petugas.pengembalian') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-undo text-success me-3"></i>
                        <div>
                            <div class="fw-bold">Pengembalian</div>
                            <small class="text-muted">Kelola pengembalian telat</small>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .bg-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%) !important;
        }

        .bg-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        }

        .bg-danger {
            background: linear-gradient(135deg, #ee7752 0%, #e73c7e 100%) !important;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }
    </style>
@endsection
