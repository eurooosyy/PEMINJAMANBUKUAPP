@extends('dashboard.admin-layout')

@section('title', 'Daftar Buku')

@section('content')
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .btn-header {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 14px;
        }

        .btn-header:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-left: 4px solid #667eea;
        }

        .stat-card h3 {
            font-size: 12px;
            color: #7c8fa0;
            margin: 0 0 10px 0;
            font-weight: 600;
            text-transform: uppercase;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
        }

        .alert-success {
            background: #c6f6d5;
            border: 1px solid #9ae6b4;
            color: #22543d;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            margin: 0;
            font-size: 18px;
            color: #2d3748;
            font-weight: 700;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table thead {
            background: #f7fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .table thead th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #4a5568;
            text-transform: uppercase;
        }

        .table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f7fafc;
        }

        .table tbody td {
            padding: 15px;
            font-size: 14px;
            color: #2d3748;
        }

        .buku-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .buku-image {
            width: 50px;
            height: 70px;
            border-radius: 4px;
            overflow: hidden;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .buku-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .buku-info {
            flex: 1;
        }

        .buku-title {
            font-weight: 600;
            color: #2d3748;
            margin: 0 0 4px 0;
            font-size: 14px;
        }

        .buku-meta {
            font-size: 12px;
            color: #a0aec0;
            margin: 0;
        }

        .stock-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .stock-badge.available {
            background: #c6f6d5;
            color: #22543d;
        }

        .stock-badge.unavailable {
            background: #fed7d7;
            color: #742a2a;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-edit:hover {
            background: #fcd34d;
            color: #78350f;
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete:hover {
            background: #fca5a5;
            color: #7f1d1d;
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
        }

        .empty-state p {
            margin: 10px 0;
            font-size: 16px;
        }

        .empty-state a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            padding: 30px;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            text-decoration: none;
            color: #667eea;
            transition: all 0.2s ease;
        }

        .pagination a:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination .active span {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1><i class="fas fa-book"></i> Daftar Buku</h1>
            <p style="margin: 5px 0 0 0; opacity: 0.95; font-size: 14px;">Kelola dan atur semua buku perpustakaan</p>
        </div>
        <a href="{{ route('books.create') }}" class="btn-header">
            <i class="fas fa-plus-circle"></i> Tambah Buku Baru
        </a>
    </div>

    <!-- Statistics -->
    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Buku</h3>
            <div class="number">{{ \App\Models\Book::count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Tersedia</h3>
            <div class="number">{{ \App\Models\Book::where('stock', '>', 0)->count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Habis Stok</h3>
            <div class="number">{{ \App\Models\Book::where('stock', '=', 0)->count() }}</div>
        </div>
        <div class="stat-card">
            <h3>Dipinjam</h3>
            <div class="number">
                {{ \App\Models\LoanItem::whereHas('loan', function ($q) {$q->where('status', 'active');})->distinct('book_id')->count() }}
            </div>
        </div>
    </div>

    @if (($hasCategoryColumn ?? false) === true)
        <div class="table-wrapper" style="margin-bottom: 24px;">
            <div class="table-header">
                <h2><i class="fas fa-tags"></i> Snapshot Kategori</h2>
            </div>
            <div style="padding: 20px 30px;">
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    @foreach ($categories ?? [] as $category)
                        @php($style = \App\Models\Book::categoryBadgeStyle($category))
                        <span
                            style="display: inline-flex; align-items: center; gap: 8px; background: {{ $style['background'] }}; color: {{ $style['color'] }}; padding: 8px 12px; border-radius: 999px; font-size: 12px; font-weight: 700;">
                            <i class="fas fa-tag"></i> {{ $category }}
                            <span style="background: rgba(255,255,255,0.7); padding: 2px 8px; border-radius: 999px;">
                                {{ $categoryCounts[$category] ?? 0 }}
                            </span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert-success">
            <span><i class="fas fa-check-circle"></i> {{ session('success') }}</span>
            <button onclick="this.parentElement.style.display='none';"
                style="background: none; border: none; cursor: pointer; color: inherit;">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    <div class="table-wrapper" style="margin-bottom: 24px;">
        <div class="table-header">
            <h2><i class="fas fa-filter"></i> Filter Buku</h2>
        </div>
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Cari judul, penulis, kategori, penerbit, atau ISBN">
            </div>
            <div class="col-md-4">
                <select name="category" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories ?? [] as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn-header" style="width: 100%; justify-content: center;">
                    <i class="fas fa-search"></i> Terapkan
                </button>
            </div>
        </form>
    </div>

    <!-- Buku Table -->
    <div class="table-wrapper">
        <div class="table-header">
            <h2><i class="fas fa-list"></i> Daftar Buku</h2>
            $books->total()

        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Buku</th>
                        <th style="width: 20%;">Informasi</th>
                        <th style="width: 15%;">Stok</th>
                        <th style="width: 25%;">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $book)
                        <tr>
                            <td>
                                <div class="buku-cell">
                                    <div class="buku-image">
                                        @if ($book->image)
                                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}">
                                        @else
                                            <i class="fas fa-book" style="font-size: 24px; color: #cbd5e0;"></i>
                                        @endif
                                    </div>
                                    <div class="buku-info">
                                        <p class="buku-title">{{ $book->title }}</p>
                                        <p class="buku-meta">{{ $book->author ?? 'Penulis tidak diketahui' }}</p>
                                        @php($categoryStyle = \App\Models\Book::categoryBadgeStyle($book->category))
                                        <p style="margin: 6px 0 0 0;">
                                            <span
                                                style="display: inline-flex; align-items: center; gap: 6px; background: {{ $categoryStyle['background'] }}; color: {{ $categoryStyle['color'] }}; border-radius: 999px; padding: 4px 10px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-tag"></i> {{ $book->category ?? 'Lainnya' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 13px;">
                                    <p style="margin: 5px 0; color: #4a5568;"><strong>ISBN:</strong>
                                        {{ $book->isbn ?? '-' }}</p>
                                    <p style="margin: 5px 0; color: #7c8fa0;"><strong>Kategori:</strong>
                                        {{ $book->category ?? '-' }}</p>
                                    <p style="margin: 5px 0; color: #7c8fa0;"><strong>Penerbit:</strong>
                                        {{ $book->publisher ?? '-' }}</p>
                                </div>
                            </td>
                            <td>
                                <span class="stock-badge {{ $book->stock > 0 ? 'available' : 'unavailable' }}">
                                    {{ $book->stock }} eksemplar
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('books.edit', $book->id) }}" class="btn-action btn-edit"
                                        title="Edit buku">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                        style="display: inline;" onsubmit="return confirm('Yakin hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action btn-delete" title="Hapus buku">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="border: none;">
                                <div class="empty-state">
                                    <i class="fas fa-book-open"></i>
                                    <p><strong>Tidak ada data buku yang cocok</strong></p>
                                    <p style="font-size: 14px; color: #cbd5e0; margin-bottom: 20px;">Coba ubah filter
                                        atau tambahkan buku baru</p>
                                    <a href="{{ route('books.create') }}"
                                        style="display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600;">
                                        <i class="fas fa-plus-circle"></i> Tambah Buku Sekarang
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($books, 'hasPages') && $books->hasPages())
            <div class="pagination">
                {{ $books->links('pagination::simple-bootstrap-4') }}
            </div>
        @endif
    @endsection
