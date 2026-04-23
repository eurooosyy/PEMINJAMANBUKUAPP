@extends('dashboard.siswa-layout')

@section('title', 'Daftar Denda')

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-file-invoice-dollar"></i> Daftar Denda</h1>
        <p>Hitungan denda dari peminjaman yang terlambat dikembalikan</p>
    </div>

    @if ($fineDetails && count($fineDetails) > 0)
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center bg-danger text-white shadow-lg">
                    <div class="card-body">
                        <i class="fas fa-coins fa-3x mb-3 opacity-75"></i>
                        <h2 class="display-4 fw-bold">Rp {{ number_format($totalFine, 0, ',', '.') }}</h2>
                        <p class="mb-0">Total Denda</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-lg">
            <div class="card-header bg-gradient-warning">
                <h5 class="text-white mb-0"><i class="fas fa-list me-2"></i>Rincian Denda</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Peralatan</th>
                                <th>Tgl Jatuh Tempo</th>
                                <th>Hari Telat</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fineDetails as $detail)
                                <tr class="table-warning">
                                    <td>{{ $detail['equipment_names'] }}</td>
                                    <td>{{ $detail['due_date']->format('d/m/Y') }}</td>
                                    <td><span class="badge bg-danger">{{ $detail['days_overdue'] }} hari</span></td>
                                    <td><strong>Rp {{ number_format($detail['fine_amount'], 0, ',', '.') }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted">Tarif: Rp 5.000/hari per peralatan</small>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Total: Rp {{ number_format($totalFine, 0, ',', '.') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 my-5">
            <i class="fas fa-check-circle fa-5x text-success mb-4"></i>
            <h3>Tidak Ada Denda</h3>
            <p class="lead text-muted mb-4">Semua peminjaman Anda sudah dikembalikan tepat waktu!</p>
            <a href="{{ route('siswa.riwayat') }}" class="btn btn-success btn-lg">
                <i class="fas fa-history me-2"></i> Lihat Riwayat Peminjaman
            </a>
        </div>
    @endif

    <style>
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .table-warning {
            --bs-table-bg: #fff3cd;
        }
    </style>
@endsection
