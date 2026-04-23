@extends('dashboard.layout')

@section('title')
    Daftar Peminjaman
@endsection

@section('content')
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-list-check"></i> Daftar Peminjaman</h1>
                <p>Kelola dan pantau semua peminjaman buku di sistem</p>
            </div>
            <a href="{{ route('loans.create') }}" class="btn btn-primary"
                style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600;">
                <i class="fas fa-plus-circle"></i> Tambah Peminjaman
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert"
            style="background: linear-gradient(90deg, #27ae60 0%, #229954 100%); border: none; color: white; border-radius: 8px;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card"
        style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px; overflow: hidden;">
        <div class="card-header"
            style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 20px;">
            <h5 style="margin: 0; display: flex; align-items: center; gap: 10px; font-weight: 700;">
                <i class="fas fa-file-lines"></i> Daftar Lengkap Peminjaman
            </h5>
        </div>

        <div class="card-body p-0">
            @if ($loans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size: 13px;">
                        <thead style="background-color: #f8f9fa; border-bottom: 2px solid #e74c3c;">
                            <tr>
                                <th
                                    style="padding: 15px; color: #2c3e50; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fas fa-hashtag"></i> ID
                                </th>
                                <th
                                    style="padding: 15px; color: #2c3e50; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fas fa-user"></i> Peminjam
                                </th>
                                <th
                                    style="padding: 15px; color: #2c3e50; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fas fa-user-tie"></i> Petugas
                                </th>
                                <th
                                    style="padding: 15px; color: #2c3e50; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fas fa-calendar-alt"></i> Tanggal Pinjam
                                </th>
                                <th
                                    style="padding: 15px; color: #2c3e50; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fas fa-calendar-check"></i> Tanggal Kembali
                                </th>
                                <th
                                    style="padding: 15px; color: #2c3e50; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">
                                    <i class="fas fa-tag"></i> Status
                                </th>
                                <th
                                    style="padding: 15px; color: #2c3e50; font-weight: 700; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: center;">
                                    <i class="fas fa-cog"></i> Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loans as $loan)
                                <tr style="border-bottom: 1px solid #e9ecef; transition: all 0.3s ease;">
                                    <td style="padding: 15px; color: #2c3e50; font-weight: 600;">
                                        <span
                                            style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                            #{{ str_pad($loan->id, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                    <td style="padding: 15px; color: #2c3e50;">
                                        <strong>{{ $loan->borrower->name }}</strong>
                                    </td>
                                    <td style="padding: 15px; color: #7f8c8d;">
                                        {{ $loan->petugas->name ?? '-' }}
                                    </td>
                                    <td style="padding: 15px; color: #7f8c8d;">
                                        <i class="fas fa-calendar me-2" style="color: #e74c3c;"></i>
                                        {{ $loan->loan_date->format('d/m/Y') }}
                                    </td>
                                    <td style="padding: 15px; color: #7f8c8d;">
                                        <i class="fas fa-calendar me-2" style="color: #e74c3c;"></i>
                                        {{ $loan->due_date->format('d/m/Y') }}
                                    </td>
                                    <td style="padding: 15px;">
                                        @if ($loan->status === 'dipinjam')
                                            <span
                                                style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                                                <i class="fas fa-hourglass-half me-1"></i> Dipinjam
                                            </span>
                                        @elseif($loan->status === 'dikembalikan')
                                            <span
                                                style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                                                <i class="fas fa-check-circle me-1"></i> Dikembalikan
                                            </span>
                                        @else
                                            <span
                                                style="background: #95a5a6; color: white; padding: 6px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block;">
                                                <i class="fas fa-info-circle me-1"></i> {{ ucfirst($loan->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 15px; text-align: center;">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm"
                                                style="background: #3498db; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                            @if ($loan->status === 'dipinjam')
                                                <form action="{{ route('loans.return', $loan) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="btn btn-sm"
                                                        style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; border: none; padding: 6px 12px; border-radius: 4px; font-weight: 600; transition: all 0.3s ease; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; margin-left: 4px;"
                                                        onclick="return confirm('Apakah Anda yakin ingin mengembalikan peminjaman ini?')">
                                                        <i class="fas fa-undo"></i> Kembalikan
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div style="padding: 20px; border-top: 1px solid #e9ecef; display: flex; justify-content: center;">
                    {{ $loans->links('pagination::bootstrap-4') }}
                </div>
            @else
                <div style="padding: 60px 20px; text-align: center;">
                    <i class="fas fa-inbox"
                        style="font-size: 64px; color: #bdc3c7; margin-bottom: 20px; display: block;"></i>
                    <h5 style="color: #7f8c8d; margin-bottom: 10px;">Belum ada data peminjaman</h5>
                    <p style="color: #95a5a6; margin-bottom: 20px;">Silakan tambahkan peminjaman baru untuk memulai</p>
                    <a href="{{ route('loans.create') }}" class="btn btn-primary"
                        style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; padding: 10px 20px; border-radius: 6px; text-decoration: none; color: white; font-weight: 600;">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Peminjaman
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0 0 5px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 14px;
            margin: 0;
        }

        tr:hover {
            background-color: #f8f9fa !important;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%) !important;
        }

        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .page-header h1 {
                font-size: 20px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            table {
                font-size: 12px;
            }

            td,
            th {
                padding: 10px !important;
            }
        }
    </style>
@endsection
