@extends('dashboard.petugas-layout')

@section('title')
    Data Buku
@endsection

@section('content')
    <style>
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

        /* Statistik Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border-left: 4px solid;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-box.total {
            border-color: #667eea;
        }

        .stat-box.available {
            border-color: #48bb78;
        }

        .stat-box.borrowed {
            border-color: #f6ad55;
        }

        .stat-box.overdue {
            border-color: #f56565;
        }

        .stat-box .stat-icon {
            font-size: 32px;
            opacity: 0.3;
            margin-right: 15px;
        }

        .stat-box .stat-info h3 {
            margin: 0;
            font-size: 14px;
            color: #7c8fa0;
            font-weight: 600;
        }

        .stat-box .stat-info .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #2d3748;
            margin-top: 5px;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .filter-section .row {
            align-items: center;
        }

        .filter-section .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .filter-section .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Books Grid */
        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .book-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .book-card .book-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .book-card .book-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-card .book-info {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .book-card .book-title {
            font-size: 15px;
            font-weight: 700;
            color: #2d3748;
            margin: 0 0 8px 0;
            line-height: 1.3;
            min-height: 40px;
        }

        .book-card .book-author {
            font-size: 13px;
            color: #7c8fa0;
            margin: 0 0 8px 0;
        }

        .book-card .book-meta {
            font-size: 12px;
            color: #a0aec0;
            margin: 8px 0;
        }

        .book-card .book-meta span {
            display: inline-block;
            margin-right: 8px;
            background: #f0f4f8;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .book-card .book-footer {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .book-card .stock-badge {
            align-self: flex-start;
            margin-bottom: 10px;
            font-size: 13px;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .book-card .stock-badge.available {
            background: #c6f6d5;
            color: #22543d;
        }

        .book-card .stock-badge.unavailable {
            background: #fed7d7;
            color: #742a2a;
        }

        /* Table View */
        .table-wrapper {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .table-wrapper h5 {
            margin: 0 0 20px 0;
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
        }

        .table thead th {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            color: #4a5568;
            font-weight: 600;
            font-size: 13px;
        }

        .table tbody td {
            border-color: #e2e8f0;
            vertical-align: middle;
            font-size: 13px;
        }

        .table tbody tr:hover {
            background: #f7fafc;
        }

        /* Action Buttons */
        .btn-group.btn-group-sm {
            display: flex;
            gap: 5px;
        }

        .btn-group.btn-group-sm .btn {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 4px;
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .view-toggle .btn {
            padding: 8px 15px;
            border: 1px solid #e2e8f0;
            background: white;
            color: #4a5568;
            cursor: pointer;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s;
        }

        .view-toggle .btn.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        /* Pagination */
        .pagination {
            margin-top: 30px;
            justify-content: center;
        }

        .pagination .page-link {
            color: #667eea;
            border-color: #e2e8f0;
        }

        .pagination .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }

        .pagination .page-link:hover {
            color: #764ba2;
            background-color: #f7fafc;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: #cbd5e0;
            margin-bottom: 20px;
            display: block;
        }

        .empty-state p {
            color: #7c8fa0;
            font-size: 16px;
            margin: 0;
        }

        .empty-state a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .table-wrapper {
                overflow-x: auto;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-book"></i> Data Buku</h1>
        <p>Kelola dan lihat informasi semua buku di perpustakaan</p>
    </div>

    <!-- Statistik Cards -->
    <div class="stats-container">
        <div class="stat-box total">
            <div>
                <div class="stat-info">
                    <h3>Total Buku</h3>
                    <div class="stat-number">{{ \App\Models\Book::count() }}</div>
                </div>
            </div>
            <i class="fas fa-book stat-icon"></i>
        </div>

        <div class="stat-box available">
            <div>
                <div class="stat-info">
                    <h3>Buku Tersedia</h3>
                    <div class="stat-number">{{ \App\Models\Book::where('stock', '>', 0)->count() }}</div>
                </div>
            </div>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>

        <div class="stat-box borrowed">
            <div>
                <div class="stat-info">
                    <h3>Sedang Dipinjam</h3>
                    <div class="stat-number">
                        {{ \App\Models\LoanItem::whereHas('loan', function ($q) {$q->where('status', 'active');})->count() }}
                    </div>
                </div>
            </div>
            <i class="fas fa-hand-holding stat-icon"></i>
        </div>

        <div class="stat-box overdue">
            <div>
                <div class="stat-info">
                    <h3>Peminjaman Terlambat</h3>
                    <div class="stat-number">
                        {{ \App\Models\Loan::where('status', 'active')->where('due_date', '<', now())->count() }}</div>
                </div>
            </div>
            <i class="fas fa-exclamation-circle stat-icon"></i>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="row">
            <div class="col-md-8">
                <input type="text" class="form-control"
                    placeholder="🔍 Cari buku berdasarkan judul, penulis, atau ISBN...">
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <a href="{{ route('books.create') }}" class="btn btn-primary w-100">
                    <i class="fas fa-plus"></i> Tambah Buku Baru
                </a>
            </div>
        </div>
    </div>

    <!-- View Toggle & Sorting -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="view-toggle">
            <button class="btn active" onclick="toggleView('grid')">
                <i class="fas fa-th"></i> Grid View
            </button>
            <button class="btn" onclick="toggleView('table')">
                <i class="fas fa-table"></i> Table View
            </button>
        </div>
        <div>
            <p style="margin: 0; color: #7c8fa0; font-size: 13px;">
                Total: <strong>{{ $books->total() }}</strong> buku
            </p>
        </div>
    </div>

    <!-- Grid View -->
    <div id="gridView">
        @if ($books->count() > 0)
            <div class="books-grid">
                @foreach ($books as $book)
                    <div class="book-card">
                        <div class="book-image">
                            @if ($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}">
                            @else
                                <div
                                    style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #e2e8f0;">
                                    <i class="fas fa-book" style="font-size: 48px; color: #cbd5e0;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="book-info">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div>
                                    <h3 class="book-title">{{ $book->title }}</h3>
                                    <p class="book-author">{{ $book->author ?? 'Penulis tidak diketahui' }}</p>
                                </div>
                                @if ($book->stock > 0)
                                    <span class="stock-badge available">{{ $book->stock }} stok</span>
                                @else
                                    <span class="stock-badge unavailable">Habis</span>
                                @endif
                            </div>
                            <div class="book-meta">
                                <span><i class="fas fa-barcode"></i> {{ $book->isbn ?? 'N/A' }}</span>
                            </div>
                            <div class="book-meta">
                                <span><i class="fas fa-building"></i>
                                    {{ $book->publisher ?? 'Penerbit tidak diketahui' }}</span>
                            </div>
                            <div class="book-meta">
                                <span><i class="fas fa-calendar"></i> {{ $book->year ?? 'Tahun tidak diketahui' }}</span>
                            </div>
                            <div class="book-footer" style="margin-top: auto;">
                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-warning flex-fill"
                                    style="font-size: 12px;">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="flex: 1;"
                                    onsubmit="return confirm('Yakin hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger w-100" style="font-size: 12px;">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data buku.</p>
                <p><a href="{{ route('books.create') }}">Tambah buku sekarang →</a></p>
            </div>
        @endif
    </div>

    <!-- Table View (Hidden by default) -->
    <div id="tableView" style="display: none;">
        @if ($books->count() > 0)
            <div class="table-wrapper">
                <h5><i class="fas fa-table"></i> Daftar Lengkap Buku</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 80px;">Gambar</th>
                                <th>Judul</th>
                                <th style="width: 150px;">Penulis</th>
                                <th style="width: 120px;">ISBN</th>
                                <th style="width: 120px;">Penerbit</th>
                                <th style="width: 80px;">Tahun</th>
                                <th style="width: 80px;">Stok</th>
                                <th style="width: 160px;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($books as $index => $book)
                                <tr>
                                    <td>{{ ($books->currentPage() - 1) * $books->perPage() + $index + 1 }}</td>
                                    <td>
                                        @if ($book->image)
                                            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}"
                                                style="height: 50px; width: 40px; object-fit: cover; border-radius: 4px;">
                                        @else
                                            <span class="badge bg-secondary">-</span>
                                        @endif
                                    </td>
                                    <td><strong>{{ $book->title }}</strong></td>
                                    <td>{{ $book->author ?? '-' }}</td>
                                    <td><code style="font-size: 11px;">{{ $book->isbn ?? '-' }}</code></td>
                                    <td>{{ $book->publisher ?? '-' }}</td>
                                    <td>{{ $book->year ?? '-' }}</td>
                                    <td>
                                        @if ($book->stock > 0)
                                            <span class="badge bg-success">{{ $book->stock }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $book->stock }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                                style="display: inline;" onsubmit="return confirm('Yakin hapus?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data buku.</p>
                <p><a href="{{ route('books.create') }}">Tambah buku sekarang →</a></p>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $books->links('pagination::bootstrap-4') }}
    </div>

    <script>
        function toggleView(view) {
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            const buttons = document.querySelectorAll('.view-toggle .btn');

            buttons.forEach(btn => btn.classList.remove('active'));

            if (view === 'grid') {
                gridView.style.display = 'block';
                tableView.style.display = 'none';
                buttons[0].classList.add('active');
            } else {
                gridView.style.display = 'none';
                tableView.style.display = 'block';
                buttons[1].classList.add('active');
            }
        }
    </script>

@endsection
