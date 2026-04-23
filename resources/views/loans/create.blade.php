@extends('dashboard.layout')

@section('title')
    Tambah Peminjaman
@endsection

@section('content')
    <div class="page-header mb-4">
        <h1><i class="fas fa-plus-circle"></i> Tambah Peminjaman Baru</h1>
        <p>Buat peminjaman buku baru untuk peminjam</p>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px;">
                <div class="card-header"
                    style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 20px;">
                    <h5 style="margin: 0; display: flex; align-items: center; gap: 10px; font-weight: 700;">
                        <i class="fas fa-form"></i> Form Peminjaman
                    </h5>
                </div>
                <div class="card-body" style="padding: 30px;">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"
                            style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); border: none; color: white; border-radius: 8px;">
                            <strong><i class="fas fa-exclamation-circle"></i> Terjadi Kesalahan!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('loans.store') }}" method="POST">
                        @csrf

                        <!-- Peminjam -->
                        <div class="mb-4">
                            <label for="borrower_id" class="form-label"
                                style="font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user"></i> Peminjam <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('borrower_id') is-invalid @enderror" id="borrower_id"
                                name="borrower_id" required
                                style="border-radius: 8px; border: 2px solid #ecf0f1; padding: 10px 12px; font-size: 14px;">
                                <option value="">-- Pilih Peminjam --</option>
                                @foreach ($borrowers as $borrower)
                                    <option value="{{ $borrower->id }}"
                                        {{ old('borrower_id') == $borrower->id ? 'selected' : '' }}>
                                        {{ $borrower->user->name ?? $borrower->name }} ({{ $borrower->nis }})
                                    </option>
                                @endforeach
                            </select>
                            @error('borrower_id')
                                <small class="text-danger d-block mt-2"><i
                                        class="fas fa-info-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Peralatan (Multiple Select) -->
                        <div class="mb-4">
                            <label for="equipment_ids" class="form-label"
                                style="font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-book"></i> Buku <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('equipment_ids') is-invalid @enderror" id="equipment_ids"
                                name="equipment_ids[]" multiple required
                                style="border-radius: 8px; border: 2px solid #ecf0f1; padding: 10px 12px; min-height: 150px;">
                                @foreach ($books as $item)
                                    <option value="{{ $item->id }}"
                                        {{ in_array($item->id, old('equipment_ids', [])) ? 'selected' : '' }}>
                                        {{ $item->title }} (Stok: {{ $item->stock }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i> Gunakan Ctrl+Click untuk memilih multiple buku
                            </small>
                            @error('equipment_ids')
                                <small class="text-danger d-block mt-2"><i
                                        class="fas fa-info-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tanggal Kembali -->
                        <div class="mb-4">
                            <label for="due_date" class="form-label"
                                style="font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calendar-alt"></i> Tanggal Kembali <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                id="due_date" name="due_date" value="{{ old('due_date') }}" required
                                style="border-radius: 8px; border: 2px solid #ecf0f1; padding: 10px 12px; font-size: 14px;">
                            @error('due_date')
                                <small class="text-danger d-block mt-2"><i
                                        class="fas fa-info-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3 pt-3">
                            <button type="submit" class="btn"
                                style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; flex: 1;">
                                <i class="fas fa-save me-2"></i> Simpan Peminjaman
                            </button>
                            <a href="{{ route('loans.index') }}" class="btn"
                                style="background: #95a5a6; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; flex: 1; text-align: center;">
                                <i class="fas fa-times me-2"></i> Batalkan
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="col-lg-4">
            <div class="card"
                style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px; border-left: 4px solid #3498db;">
                <div class="card-body" style="padding: 20px;">
                    <h6
                        style="font-weight: 700; color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-lightbulb" style="color: #f39c12;"></i> Tips Penggunaan
                    </h6>
                    <ul style="margin-bottom: 0; padding-left: 20px; line-height: 1.8; font-size: 13px; color: #7f8c8d;">
                        <li>Pastikan peminjam sudah terdaftar dalam sistem</li>
                        <li>Pilih satu atau lebih buku yang ingin dipinjam</li>
                        <li>Tentukan tanggal kembali yang sesuai</li>
                        <li>Stok buku akan otomatis berkurang setelah peminjaman</li>
                        <li>Petugas harus login untuk membuat peminjaman</li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3"
                style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px; border-left: 4px solid #27ae60;">
                <div class="card-body" style="padding: 20px;">
                    <h6
                        style="font-weight: 700; color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-info-circle" style="color: #3498db;"></i> Informasi
                    </h6>
                    <p style="margin-bottom: 0; font-size: 13px; color: #7f8c8d; line-height: 1.6;">
                        Setiap peminjaman akan dicatat dalam sistem dan dapat dipantau melalui halaman riwayat peminjaman.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-select:focus,
        .form-control:focus {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25) !important;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column-reverse;
            }

            .btn {
                width: 100%;
            }

            .d-flex {
                flex-direction: column;
            }
        }
    </style>
@endsection
