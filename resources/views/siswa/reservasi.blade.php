@extends('dashboard.siswa-layout')

@section('title', 'Reservasi')

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-bookmark"></i> Reservasi Buku perpustakaan</h1>
        <p>Status reservasi buku yang Anda ajukan</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-info shadow-lg text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-3x mb-3"></i>
                    <h3>{{ $reservations->where('status', 'pending')->count() }}</h3>
                    <small>Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow-lg text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h3>{{ $reservations->where('status', 'ready')->count() }}</h3>
                    <small>Siap Diambil</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-lg text-center">
                <div class="card-body">
                    <i class="fas fa-history fa-3x mb-3"></i>
                    <h3>{{ $reservations->whereIn('status', ['completed', 'cancelled'])->count() }}</h3>
                    <small>Selesai/Dibatalkan</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light text-center shadow">
                <div class="card-body">
                    <i class="fas fa-calendar-plus fa-3x mb-3 text-primary opacity-75"></i>
                    <h3><a href="{{ route('siswa.jelajahi') }}" class="text-decoration-none">+ Reservasi</a></h3>
                    <small>Ajukan Baru</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Reservasi</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Tanggal Reservasi</th>
                        <th>Status</th>
                        <th>Berlaku Hingga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>
                                <strong>{{ $reservation->equipment->nama_peralatan }}</strong><br>
                                <small class="text-muted">{{ $reservation->equipment->merk }}</small>
                            </td>
                            <td>{{ $reservation->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @switch($reservation->status)
                                    @case('pending')
                                        <span class="badge bg-info">Menunggu</span>
                                    @break

                                    @case('ready')
                                        <span class="badge bg-success">Siap Diambil</span>
                                    @break

                                    @case('completed')
                                        <span class="badge bg-secondary">Selesai</span>
                                    @break

                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @break
                                @endswitch
                            </td>
                            <td>{{ $reservation->reserved_until->format('d/m/Y') }}</td>
                            <td>
                                @if ($reservation->status == 'pending')
                                    <form action="{{ route('siswa.reservasi.cancel', $reservation) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Batalkan reservasi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="fas fa-bookmark-slash fa-3x mb-3"></i>
                                    <p>Tidak ada reservasi aktif</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $reservations->links() }}

        <style>
            .page-header {
                background: white;
                padding: 25px;
                border-radius: 15px;
                margin-bottom: 30px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            }

            .card {
                border: none;
                border-radius: 15px;
            }
        </style>
    @endsection
