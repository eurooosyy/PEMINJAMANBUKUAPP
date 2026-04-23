@extends('dashboard.siswa-layout')

@section('title', 'Profil Siswa')

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-user-circle"></i> Profil Siswa</h1>
        <p>Kelola informasi profil dan statistik peminjaman Anda</p>
    </div>

    <div class="row">
        <!-- Profil Card -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('siswa.profil.update') }}" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="row">
                            <div class="col-md-3 text-center mb-4">
                                <div class="avatar-lg mx-auto mb-3">
                                    <div class="avatar-placeholder bg-primary">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                </div>
                                <small class="text-muted">Foto Profil (Coming Soon)</small>
                            </div>
                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Nama Lengkap</label>
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Email</label>
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                    @if (auth()->user()->phone)
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">No. Telepon</label>
                                            <input type="text" class="form-control" name="phone"
                                                value="{{ old('phone', auth()->user()->phone) }}">
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <label class="form-label fw-bold">Kata Sandi Baru (Kosongkan jika tidak ingin
                                            mengubah)</label>
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Minimal 6 karakter">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i> Update Profil
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 h-100">
                <div class="card-header bg-gradient-success text-white">
                    <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Statistik Peminjaman</h6>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Total Peminjaman</span>
                            <span class="fw-bold fs-5 text-primary">{{ $totalLoans ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Aktif</span>
                            <span class="fw-bold fs-5 text-success">{{ $activeLoans ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="stat-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Sudah Dikembalikan</span>
                            <span class="fw-bold fs-5 text-info">{{ $returnedLoans ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="d-flex justify-content-between">
                            <span>Terlambat</span>
                            <span
                                class="fw-bold fs-5 {{ ($overdueLoans ?? 0) > 0 ? 'text-danger' : 'text-muted' }}">{{ $overdueLoans ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }

        .avatar-lg {
            width: 120px;
            height: 120px;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: bold;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .stat-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }

        .page-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
    </style>
@endsection
