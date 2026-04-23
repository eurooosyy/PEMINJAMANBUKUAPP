@extends('dashboard.admin-layout')

@section('title', 'Tambah Buku')

@section('content')
    <style>
        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }

        .page-header p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 14px;
        }

        .form-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .form-section {
            padding: 30px;
        }

        .form-section h3 {
            color: #2d3748;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f4f8;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #2d3748;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-text.text-muted {
            font-size: 12px;
            color: #a0aec0 !important;
            margin-top: 6px;
            display: block;
        }

        .required {
            color: #f56565;
            font-weight: bold;
        }

        .alert-danger {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        .alert-danger li {
            margin-bottom: 5px;
        }

        .image-preview {
            margin-top: 15px;
        }

        .image-preview-box {
            width: 150px;
            height: 200px;
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7fafc;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-preview-box:hover {
            border-color: #667eea;
            background: #edf2f7;
        }

        .image-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview-placeholder {
            text-align: center;
            color: #cbd5e0;
        }

        .image-preview-placeholder i {
            font-size: 32px;
            margin-bottom: 10px;
            display: block;
        }

        .image-preview-placeholder p {
            margin: 0;
            font-size: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            padding: 0 30px 30px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            text-decoration: none;
            color: white;
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
            text-decoration: none;
            color: #2d3748;
        }

        .info-box {
            background: #edf2f7;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #2d3748;
        }

        .info-box i {
            margin-right: 8px;
            color: #667eea;
        }
    </style>

    <div class="form-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-plus-circle"></i> Tambah Buku Baru</h1>
            <p>Tambahkan buku baru ke perpustakaan dengan mengisi formulir di bawah</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <strong>Tips:</strong> Pastikan semua informasi buku sudah benar sebelum disimpan. Anda dapat mengedit data buku
            kapan saja.
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert-danger">
                <strong><i class="fas fa-exclamation-circle"></i> Validasi Gagal!</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="form-card">
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" id="bookForm">
                @csrf

                <!-- Informasi Dasar -->
                <div class="form-section">
                    <h3><i class="fas fa-book"></i> Informasi Dasar</h3>

                    <div class="form-row">
                        <div>
                            <label for="title" class="form-label">Judul <span class="required">*</span></label>
                            <input type="text" class="form-control" id="title" name="title"
                                placeholder="Masukkan judul buku" value="{{ old('title') }}" required>
                            <span class="form-text text-muted">Judul lengkap buku</span>
                        </div>
                        <div>
                            <label for="author" class="form-label">Penulis <span class="required">*</span></label>
                            <input type="text" class="form-control" id="author" name="author"
                                placeholder="Nama penulis" value="{{ old('author') }}" required>
                            <span class="form-text text-muted">Penulis buku</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label for="category" class="form-label">Kategori <span class="required">*</span></label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="form-text text-muted">Kategori buku untuk pengelompokan</span>
                        </div>
                        <div>
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn"
                                placeholder="978-3-16-148410-0" value="{{ old('isbn') }}" maxlength="20">
                            <span class="form-text text-muted">ISBN buku (opsional, unik)</span>
                        </div>
                    </div>
                </div>

                <!-- Detail Publikasi -->
                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Detail Publikasi</h3>

                    <div class="form-row">
                        <div>
                            <label for="publisher" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" id="publisher" name="publisher"
                                placeholder="Gramedia / Erlangga" value="{{ old('publisher') }}">
                            <span class="form-text text-muted">Penerbit buku</span>
                        </div>
                        <div>
                            <label for="year" class="form-label">Tahun Terbit</label>
                            <input type="number" class="form-control" id="year" name="year"
                                value="{{ old('year', date('Y')) }}" min="1900" max="{{ date('Y') + 1 }}">
                            <span class="form-text text-muted">Tahun penerbitan buku</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label for="stock" class="form-label">Jumlah Stok <span class="required">*</span></label>
                            <input type="number" class="form-control" id="stock" name="stock"
                                placeholder="Jumlah eksemplar" value="{{ old('stock', 1) }}" min="1" required>
                            <span class="form-text text-muted">Jumlah eksemplar buku yang tersedia</span>
                        </div>
                        <div>
                            <label for="shelf" class="form-label">Rak Buku</label>
                            <input type="text" class="form-control" id="shelf" name="shelf"
                                placeholder="Rak A1 / Rak Fiksi 3" value="{{ old('shelf') }}">
                            <span class="form-text text-muted">Lokasi rak buku di perpustakaan</span>
                        </div>
                    </div>
                </div>

                <!-- Gambar Buku -->
                <div class="form-section">
                    <h3><i class="fas fa-image"></i> Gambar Sampul</h3>

                    <div>
                        <label for="image" class="form-label">Upload Sampul Buku</label>
                        <div style="display: flex; gap: 30px; align-items: flex-start;">
                            <!-- Preview Area -->
                            <div>
                                <div class="image-preview-box" id="imagePreview">
                                    <div class="image-preview-placeholder">
                                        <i class="fas fa-image"></i>
                                        <p>Klik untuk upload</p>
                                    </div>
                                </div>
                                <small class="form-text text-muted" style="display: block; margin-top: 10px;">
                                    Format: JPG, PNG, WebP, GIF <br> Maksimal: 5MB
                                </small>
                            </div>

                            <!-- Upload Input -->
                            <div style="flex: 1;">
                                <input type="file" class="form-control" id="image" name="image"
                                    accept="image/jpeg,image/png,image/jpg,image/webp,image/gif" style="display: none;">
                                <button type="button" class="btn btn-secondary"
                                    onclick="document.getElementById('image').click()">
                                    <i class="fas fa-cloud-upload-alt"></i> Pilih File
                                </button>
                                <p class="form-text text-muted" style="margin-top: 10px;">
                                    Gambar sampul akan ditampilkan sebagai thumbnail di daftar buku.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="form-section">
                    <h3><i class="fas fa-align-left"></i> Deskripsi Buku</h3>
                    <div class="form-group">
                        <label for="description" class="form-label">Deskripsi (Opsional)</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                            placeholder="Sinopsis singkat atau informasi tambahan tentang buku">{{ old('description') }}</textarea>
                        <span class="form-text text-muted">Deskripsi buku untuk informasi lebih detail</span>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Buku
                    </button>
                    <a href="{{ route('books.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Click on preview to upload
        document.getElementById('imagePreview').addEventListener('click', function() {
            document.getElementById('image').click();
        });
    </script>
@endsection
