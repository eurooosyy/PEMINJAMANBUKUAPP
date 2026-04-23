@extends('dashboard.layout')

@section('title')
    Edit Peminjaman
@endsection

@section('content')
    <div class="page-header mb-4">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-edit"></i> Edit Peminjaman #{{ str_pad($loan->id, 3, '0', STR_PAD_LEFT) }}</h1>
                <p>Ubah informasi peminjaman</p>
            </div>
            <a href="{{ route('loans.show', $loan->id) }}" class="btn"
                style="background: #95a5a6; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card" style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px;">
                <div class="card-header"
                    style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 20px;">
                    <h5 style="margin: 0; display: flex; align-items: center; gap: 10px; font-weight: 700;">
                        <i class="fas fa-form"></i> Form Edit Peminjaman
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

                    <form action="{{ route('loans.update', $loan->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Peminjam (Read-only) -->
                        <div class="mb-4">
                            <label for="borrower" class="form-label"
                                style="font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-user"></i> Peminjam <span class="badge bg-secondary ms-2">Tidak Dapat
                                    Diubah</span>
                            </label>
                            <input type="text" class="form-control" id="borrower" value="{{ $loan->borrower->name }}"
                                disabled
                                style="border-radius: 8px; border: 2px solid #ecf0f1; padding: 10px 12px; background: #f8f9fa;">
                        </div>

                        <!-- Buku yang Dipinjam (Read-only) -->
                        <div class="mb-4">
                            <label class="form-label"
                                style="font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-book"></i> Buku yang Dipinjam <span class="badge bg-secondary ms-2">Tidak
                                    Dapat Diubah</span>
                            </label>
                            <div style="background: #f0f8ff; border: 2px solid #bee5eb; border-radius: 8px; padding: 15px;">
                                <ul style="margin: 0; padding-left: 20px;">
                                    @foreach ($loan->loanItems as $item)
                                        <li style="margin-bottom: 8px; color: #2c3e50;">
                                            <strong>{{ $item->book->title }}</strong>
                                            <br>
                                            <small style="color: #7f8c8d;">
                                                <i class="fas fa-pen me-1"></i>{{ $item->book->author ?? 'N/A' }} •
                                                <i class="fas fa-barcode me-1"></i>{{ $item->book->isbn ?? 'N/A' }}
                                            </small>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <!-- Tanggal Kembali (Editable) -->
                        <div class="mb-4">
                            <label for="due_date" class="form-label"
                                style="font-weight: 600; color: #2c3e50; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calendar-alt"></i> Tanggal Kembali <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                id="due_date" name="due_date"
                                value="{{ old('due_date', $loan->due_date->format('Y-m-d')) }}" required
                                style="border-radius: 8px; border: 2px solid #ecf0f1; padding: 10px 12px; font-size: 14px;">
                            @error('due_date')
                                <small class="text-danger d-block mt-2"><i
                                        class="fas fa-info-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Info Peminjaman (Read-only) -->
                        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                            <h6
                                style="font-weight: 700; color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-info-circle"></i> Informasi Peminjaman
                            </h6>
                            <div
                                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                                <div>
                                    <small style="color: #7f8c8d; font-weight: 600;">Tanggal Pinjam</small>
                                    <p style="margin: 5px 0 0 0; color: #2c3e50; font-weight: 600;">
                                        {{ $loan->loan_date->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <small style="color: #7f8c8d; font-weight: 600;">Status</small>
                                    <p style="margin: 5px 0 0 0; color: #2c3e50; font-weight: 600;">
                                        <span
                                            style="background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; padding: 4px 8px; border-radius: 4px;">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn"
                                style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; flex: 1;">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('loans.show', $loan->id) }}" class="btn"
                                style="background: #95a5a6; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; text-decoration: none; flex: 1; text-align: center;">
                                <i class="fas fa-times me-2"></i> Batalkan
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Warning Card -->
        <div class="col-lg-4">
            <div class="card mb-4"
                style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px; border-left: 4px solid #e74c3c;">
                <div class="card-body" style="padding: 20px;">
                    <h6
                        style="font-weight: 700; color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-exclamation-triangle" style="color: #e74c3c;"></i> Catatan Penting
                    </h6>
                    <ul style="margin-bottom: 0; padding-left: 20px; line-height: 1.8; font-size: 13px; color: #7f8c8d;">
                        <li>Hanya tanggal kembali yang dapat diubah</li>
                        <li>Peminjam dan buku tidak dapat diubah</li>
                        <li>Untuk mengubah buku, buat peminjaman baru</li>
                        <li>Perubahan akan disimpan secara otomatis</li>
                    </ul>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card"
                style="border: none; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); border-radius: 12px; border-left: 4px solid #3498db;">
                <div class="card-body" style="padding: 20px;">
                    <h6
                        style="font-weight: 700; color: #2c3e50; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-question-circle" style="color: #3498db;"></i> Bantuan
                    </h6>
                    <p style="margin-bottom: 0; font-size: 13px; color: #7f8c8d; line-height: 1.6;">
                        Gunakan form ini untuk merubah tanggal kembali peminjaman jika diperlukan perpanjangan atau
                        penyesuaian.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #e74c3c !important;
            box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25) !important;
        }

        button:hover,
        a:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        @media (max-width: 768px) {
            .row {
                flex-direction: column-reverse;
            }

            .d-flex {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
@endsection
