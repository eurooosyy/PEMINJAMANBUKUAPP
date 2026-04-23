@extends('dashboard.admin-layout')

@section('title')
    Detail Petugas
@endsection

@section('content')
    <div class="page-header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-user"></i> Detail Petugas</h1>
                <p>Informasi lengkap tentang petugas {{ $petugas->name }}</p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('petugas.edit', $petugas) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('petugas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="detail-container">
        <div class="detail-header">
            <div class="avatar-large">
                {{ substr($petugas->name, 0, 1) }}
            </div>
            <div class="info-header">
                <h2>{{ $petugas->name }}</h2>
                <p><i class="fas fa-envelope"></i> {{ $petugas->email }}</p>
                <p><i class="fas fa-phone"></i> {{ $petugas->phone ?? 'Tidak ada' }}</p>
            </div>
        </div>

        <div class="detail-content">
            <div class="info-card">
                <h4><i class="fas fa-address-card"></i> Informasi Pribadi</h4>
                <div class="info-row">
                    <span class="label">Nama Lengkap:</span>
                    <span class="value">{{ $petugas->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value">{{ $petugas->email }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Telepon:</span>
                    <span class="value">{{ $petugas->phone ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Role:</span>
                    <span class="value">
                        <span class="badge badge-role">{{ $petugas->role->name ?? 'N/A' }}</span>
                    </span>
                </div>
            </div>

            <div class="info-card">
                <h4><i class="fas fa-clock"></i> Riwayat Akun</h4>
                <div class="info-row">
                    <span class="label">Terdaftar Sejak:</span>
                    <span class="value">{{ $petugas->created_at->format('d F Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Terakhir Diperbarui:</span>
                    <span class="value">{{ $petugas->updated_at->format('d F Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="value">
                        <span class="badge badge-active">
                            <i class="fas fa-check-circle"></i> Aktif
                        </span>
                    </span>
                </div>
            </div>

            <div class="danger-zone">
                <h4><i class="fas fa-exclamation-triangle"></i> Zona Berbahaya</h4>
                <p>Tindakan berikut tidak dapat dibatalkan:</p>
                <form action="{{ route('petugas.destroy', $petugas) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger-large"
                        onclick="return confirm('Yakin ingin menghapus petugas ini? Tindakan ini tidak dapat dibatalkan!')">
                        <i class="fas fa-trash"></i> Hapus Petugas
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border-left: 5px solid #667eea;
        }

        .page-header h1 {
            color: #667eea;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .page-header p {
            color: #666;
            margin: 8px 0 0 0;
            font-size: 14px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-warning:hover {
            background-color: #e0a800;
            color: black;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            color: white;
        }

        .detail-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 30px;
        }

        .avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .info-header {
            flex: 1;
        }

        .info-header h2 {
            margin: 0;
            font-size: 28px;
            color: #333;
            font-weight: 700;
        }

        .info-header p {
            margin: 10px 0 0 0;
            color: #666;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #667eea;
        }

        .info-card h4 {
            margin: 0 0 15px 0;
            color: #333;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-card h4 i {
            color: #667eea;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .label {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .info-row .value {
            color: #666;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-role {
            background-color: #667eea;
            color: white;
        }

        .badge-active {
            background-color: #d4edda;
            color: #155724;
        }

        .danger-zone {
            background: #fff5f5;
            border-radius: 10px;
            padding: 20px;
            border-left: 4px solid #dc3545;
            grid-column: 1 / -1;
        }

        .danger-zone h4 {
            margin: 0 0 10px 0;
            color: #dc3545;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .danger-zone p {
            margin: 0 0 15px 0;
            color: #666;
            font-size: 14px;
        }

        .btn-danger-large {
            padding: 12px 25px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-danger-large:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 15px 20px;
            border: none;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        @media (max-width: 768px) {
            .detail-header {
                flex-direction: column;
                text-align: center;
            }

            .detail-content {
                grid-template-columns: 1fr;
            }

            .avatar-large {
                width: 100px;
                height: 100px;
                font-size: 40px;
            }

            .info-header h2 {
                font-size: 24px;
            }

            .info-row {
                flex-direction: column;
                gap: 5px;
            }

            .danger-zone {
                grid-column: auto;
            }
        }
    </style>
@endsection
