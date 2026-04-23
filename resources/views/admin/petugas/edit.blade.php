@extends('dashboard.admin-layout')

@section('title')
    Edit Petugas
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Data Petugas</h1>
        <p>Perbarui informasi petugas {{ $petugas->name }}</p>
    </div>

    <div class="form-container">
        <form action="{{ route('petugas.update', $petugas) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-section">
                <h3><i class="fas fa-user"></i> Informasi Pribadi</h3>

                <div class="form-row">
                    <div>
                        <label for="name" class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" placeholder="Masukkan nama lengkap" value="{{ old('name', $petugas->name) }}"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div>
                        <label for="email" class="form-label">Email <span class="required">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" placeholder="Masukkan email" value="{{ old('email', $petugas->email) }}"
                            required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Email digunakan untuk login</small>
                    </div>

                    <div>
                        <label for="phone" class="form-label">Telepon</label>
                        <input type="text" class="form-control" id="phone" name="phone"
                            placeholder="Masukkan nomor telepon" value="{{ old('phone', $petugas->phone) }}">
                        <small class="form-text text-muted">Contoh: 081234567890</small>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-lock"></i> Ubah Password</h3>

                <div class="form-row">
                    <div>
                        <label for="password" class="form-label">Password Baru (Opsional)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimal 8 karakter. Biarkan kosong jika tidak ingin mengubah
                            password.</small>
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            placeholder="Konfirmasi password baru">
                        <small class="form-text text-muted">Ketik ulang password yang sama</small>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('petugas.show', $petugas) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
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

        .form-container {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-section {
            margin-bottom: 30px;
            padding-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .form-section h3 {
            color: #333;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h3 i {
            color: #667eea;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-row>div:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
        }

        .required {
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }

        .form-text {
            display: block;
            font-size: 12px;
            margin-top: 5px;
        }

        .text-muted {
            color: #999;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .btn {
            padding: 12px 25px;
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

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endsection
