@extends('dashboard.layout')

@section('title')
    Detail Peminjaman
@endsection

@section('content')
    <div class="page-header mb-4">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-book-reader"></i> Detail Peminjaman #{{ str_pad($loan->id, 3, '0', STR_PAD_LEFT) }}</h1>
                <p>Informasi lengkap peminjaman buku</p>
            </div>
            <a href="{{ route('loans.index') }}" class="btn"
                style="background: #95a5a6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Info -->
        <div class="col-lg-8">
            <!-- Status Card -->
            <div class="card mb-4" style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px;">
                <div class="card-body" style="padding: 25px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h5
                            style="margin: 0; font-weight: 700; color: #2c3e50; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-info-circle" style="color: #e74c3c;"></i> Status Peminjaman
                        </h5>
                        <span
                            style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); 
                            color: white; padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 12px;">
                            <i class="fas fa-hourglass-half me-1"></i>
                            {{ ucfirst($loan->status) }}
                        </span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <!-- Peminjam -->
                        <div
                            style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #e74c3c;">
                            <small style="color: #7f8c8d; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-user"></i> PEMINJAM
                            </small>
                            <h6 style="margin: 8px 0 0 0; color: #2c3e50; font-weight: 700;">
                                {{ $loan->borrower->name }}
                            </h6>
                            <small style="color: #95a5a6;">{{ $loan->borrower->email ?? 'N/A' }}</small>
                        </div>

                        <!-- Petugas -->
                        <div
                            style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #3498db;">
                            <small style="color: #7f8c8d; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-user-tie"></i> PETUGAS
                            </small>
                            <h6 style="margin: 8px 0 0 0; color: #2c3e50; font-weight: 700;">
                                {{ $loan->petugas->name ?? 'N/A' }}
                            </h6>
                        </div>

                        <!-- Tgl Pinjam -->
                        <div
                            style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #27ae60;">
                            <small style="color: #7f8c8d; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-calendar-check"></i> TANGGAL PINJAM
                            </small>
                            <h6 style="margin: 8px 0 0 0; color: #2c3e50; font-weight: 700;">
                                {{ $loan->loan_date->format('d/m/Y') }}
                            </h6>
                            <small style="color: #95a5a6;">{{ $loan->loan_date->format('H:i') }}</small>
                        </div>

                        <!-- Tgl Kembali -->
                        <div
                            style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #e74c3c;">
                            <small style="color: #7f8c8d; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-calendar-times"></i> TANGGAL KEMBALI
                            </small>
                            <h6 style="margin: 8px 0 0 0; color: #2c3e50; font-weight: 700;">
                                {{ $loan->due_date->format('d/m/Y') }}
                            </h6>
                            @if ($loan->return_date)
                                <small style="color: #27ae60;">
                                    <i class="fas fa-check-circle me-1"></i> Dikembalikan
                                </small>
                            @else
                                <small style="color: {{ now() > $loan->due_date ? '#e74c3c' : '#95a5a6' }};">
                                    {{ now() > $loan->due_date ? '⚠️ Terlambat!' : '✓ Tepat waktu' }}
                                </small>
                            @endif
                        </div>

                        <!-- Durasi -->
                        <div
                            style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #9b59b6;">
                            <small style="color: #7f8c8d; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                <i class="fas fa-clock"></i> DURASI
                            </small>
                            <h6 style="margin: 8px 0 0 0; color: #2c3e50; font-weight: 700;">
                                {{ $loan->due_date->diffInDays($loan->loan_date) }} hari
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Equipment List -->
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px;">
                <div class="card-header"
                    style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 20px;">
                    <h5 style="margin: 0; display: flex; align-items: center; gap: 10px; font-weight: 700;">
                        <i class="fas fa-book"></i> Daftar Buku ({{ $loan->loanItems->count() }} item)
                    </h5>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if ($loan->loanItems && $loan->loanItems->count() > 0)
                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background: #f8f9fa; border-bottom: 2px solid #ecf0f1;">
                                        <th
                                            style="padding: 15px; text-align: left; color: #2c3e50; font-weight: 700; font-size: 13px;">
                                            <i class="fas fa-tools me-2"></i> NAMA BUKU
                                        </th>
                                        <th
                                            style="padding: 15px; text-align: center; color: #2c3e50; font-weight: 700; font-size: 13px;">
                                            <i class="fas fa-barcode me-2"></i> NOMOR IDENTITAS
                                        </th>
                                        <th
                                            style="padding: 15px; text-align: center; color: #2c3e50; font-weight: 700; font-size: 13px;">
                                            <i class="fas fa-tag me-2"></i> MERK
                                        </th>
                                        <th
                                            style="padding: 15px; text-align: center; color: #2c3e50; font-weight: 700; font-size: 13px;">
                                            <i class="fas fa-list me-2"></i> KATEGORI
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($loan->loanItems as $item)
                                        <tr style="border-bottom: 1px solid #ecf0f1; transition: background 0.3s ease;">
                                            <td style="padding: 15px; color: #2c3e50; font-weight: 500;">
                                                {{ $item->book->title }}
                                            </td>
                                            <td style="padding: 15px; text-align: center; color: #7f8c8d;">
                                                <span
                                                    style="background: #ecf0f1; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                                    {{ $item->book->isbn ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td style="padding: 15px; text-align: center; color: #7f8c8d;">
                                                {{ $item->book->author ?? 'N/A' }}
                                            </td>
                                            <td style="padding: 15px; text-align: center; color: #7f8c8d;">
                                                {{ $item->book->category ?? ($item->book->kategori ?? 'N/A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="padding: 40px; text-align: center; color: #95a5a6;">
                            <i class="fas fa-inbox"
                                style="font-size: 40px; margin-bottom: 15px; display: block; opacity: 0.5;"></i>
                            <p style="margin: 0;">Tidak ada buku dalam peminjaman ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="col-lg-4">
            <!-- Action Buttons -->
            <div class="card mb-4" style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px;">
                <div class="card-body" style="padding: 20px;">
                    <h6
                        style="font-weight: 700; color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-cogs" style="color: #e74c3c;"></i> Aksi
                    </h6>

                    @if ($loan->status === 'dipinjam')
                        <form action="{{ route('loans.returnLoan', $loan->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn w-100"
                                style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;">
                                <i class="fas fa-check-circle me-2"></i> Kembalikan Buku
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('loans.edit', $loan->id) }}" class="btn w-100"
                        style="background: #3498db; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; display: block; text-align: center; margin-bottom: 8px;">
                        <i class="fas fa-edit me-2"></i> Edit
                    </a>

                    <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn w-100"
                            style="background: #e74c3c; color: white; padding: 12px; border: none; border-radius: 8px; font-weight: 600; transition: all 0.3s ease;"
                            onclick="return confirm('Yakin ingin menghapus peminjaman ini?')">
                            <i class="fas fa-trash me-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>

            <!-- Pengajuan Perpanjangan Section -->
            @php
                $pendingExtension = $loan->extensions()->where('status', 'pending')->first();
            @endphp
            @if ($pendingExtension)
                <div class="card mb-4"
                    style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px; border-left: 4px solid #f39c12;">
                    <div class="card-header"
                        style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; border: none; padding: 15px 20px;">
                        <h6 style="margin: 0; display: flex; align-items: center; gap: 8px; font-weight: 700;">
                            <i class="fas fa-sync-alt"></i> Pengajuan Perpanjangan <span
                                class="badge bg-light text-dark ms-2">{{ $pendingExtension->created_at->diffForHumans() }}</span>
                        </h6>
                    </div>
                    <div class="card-body" style="padding: 20px;">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alasan Siswa:</label>
                            <p class="text-muted">{{ $pendingExtension->reason }}</p>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Lama:</label>
                                <p><strong>{{ $pendingExtension->old_due_date->format('d/m/Y') }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tanggal Baru:</label>
                                <p><strong>{{ $pendingExtension->new_due_date->format('d/m/Y') }}</strong></p>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <form action="{{ route('loans.extension.approve', $loan->id) }}" method="POST"
                                class="me-md-2 mb-2 mb-md-0">
                                @csrf
                                <input type="hidden" name="admin_notes" value="Disetujui otomatis">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check me-2"></i> Setujui
                                </button>
                            </form>
                            <form action="{{ route('loans.extension.reject', $loan->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="reject_reason" value="Tidak disetujui">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-times me-2"></i> Tolak
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Timeline -->
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px;">
                <div class="card-body" style="padding: 20px;">
                    <h6
                        style="font-weight: 700; color: #2c3e50; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-history" style="color: #9b59b6;"></i> Timeline
                    </h6>

                    <div style="position: relative; padding-left: 20px;">
                        <!-- Loan Date -->
                        <div style="margin-bottom: 20px; position: relative;">
                            <div
                                style="position: absolute; left: -20px; width: 12px; height: 12px; background: #27ae60; border-radius: 50%; top: 4px;">
                            </div>
                            <small style="color: #95a5a6; font-weight: 600;">
                                <i class="fas fa-check-circle me-1"></i> Peminjaman Dibuat
                            </small>
                            <p style="margin: 5px 0 0 0; color: #2c3e50; font-weight: 600;">
                                {{ $loan->loan_date->format('d M Y - H:i') }}
                            </p>
                        </div>

                        <!-- Due Date -->
                        <div style="margin-bottom: 20px; position: relative;">
                            <div
                                style="position: absolute; left: -20px; width: 12px; height: 12px; background: 
                                {{ now() > $loan->due_date && !$loan->return_date ? '#e74c3c' : '#3498db' }}; 
                                border-radius: 50%; top: 4px;">
                            </div>
                            <small style="color: #95a5a6; font-weight: 600;">
                                <i class="fas fa-calendar-alt me-1"></i> Tanggal Kembali
                            </small>
                            <p style="margin: 5px 0 0 0; color: #2c3e50; font-weight: 600;">
                                {{ $loan->due_date->format('d M Y') }}
                            </p>
                        </div>

                        <!-- Return Date -->
                        @if ($loan->return_date)
                            <div style="position: relative;">
                                <div
                                    style="position: absolute; left: -20px; width: 12px; height: 12px; background: #27ae60; border-radius: 50%; top: 4px;">
                                </div>
                                <small style="color: #95a5a6; font-weight: 600;">
                                    <i class="fas fa-check me-1"></i> Dikembalikan
                                </small>
                                <p style="margin: 5px 0 0 0; color: #2c3e50; font-weight: 600;">
                                    {{ $loan->return_date->format('d M Y - H:i') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        tr:hover {
            background: #f8f9fa !important;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column-reverse;
            }

            [style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endsection
