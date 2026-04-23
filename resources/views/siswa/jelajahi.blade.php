@extends('dashboard.siswa-layout')

@section('title', 'Jelajahi Buku')

@section('content')
    @php
        $selectedCategory = request('category', request('kategori'));
        $activeFilterCount = collect([
            filled(request('search')),
            filled($selectedCategory),
            request('available') === '1',
        ])->filter()->count();
    @endphp

    <div class="browse-shell">
        <section class="browse-hero">
            <div class="browse-hero__copy">
                <span class="hero-tag">Katalog Siswa</span>
                <h1><i class="fas fa-book-open"></i> Jelajahi Buku</h1>
                <p>Cari buku, pilih jumlah yang ingin dipinjam, simpan ke wishlist, lalu pinjam langsung dari halaman ini.</p>
            </div>

            <div class="browse-hero__stats">
                <div class="hero-stat">
                    <strong>{{ $books->total() }}</strong>
                    <span>Total Judul</span>
                </div>
                <div class="hero-stat">
                    <strong>{{ $books->where('stock', '>', 0)->count() }}</strong>
                    <span>Siap Dipinjam</span>
                </div>
            </div>
        </section>

        <section class="filter-card">
            <div class="filter-card__head">
                <div>
                    <h2>Filter Buku</h2>
                    <p>Semua fitur lama tetap ada. Sekarang dibuat lebih ringkas dan mudah dipakai.</p>
                </div>
                @if ($activeFilterCount > 0)
                    <span class="filter-counter">{{ $activeFilterCount }} filter aktif</span>
                @endif
            </div>

            <form method="GET" class="filter-grid">
                <div class="filter-field filter-field--search">
                    <label class="form-label">Cari Buku</label>
                    <div class="input-icon">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" class="form-control"
                            placeholder="Judul, penulis, penerbit, kategori" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="filter-field">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ $selectedCategory == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-field">
                    <label class="form-label">Ketersediaan</label>
                    <label class="availability-toggle">
                        <input type="checkbox" name="available" value="1" {{ request('available') ? 'checked' : '' }}>
                        <span>Hanya yang tersedia</span>
                    </label>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary action-btn">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('siswa.jelajahi') }}" class="btn btn-outline-secondary action-btn">
                        <i class="fas fa-rotate-left me-1"></i> Reset
                    </a>
                </div>
            </form>

            @if ($activeFilterCount > 0)
                <div class="filter-tags">
                    @if (filled(request('search')))
                        <span class="filter-tag"><i class="fas fa-search"></i> {{ request('search') }}</span>
                    @endif
                    @if (filled($selectedCategory))
                        <span class="filter-tag"><i class="fas fa-tag"></i> {{ $selectedCategory }}</span>
                    @endif
                    @if (request('available') === '1')
                        <span class="filter-tag"><i class="fas fa-box-open"></i> Tersedia</span>
                    @endif
                </div>
            @endif
        </section>

        <section class="results-strip">
            <div>
                <strong>{{ $books->total() }} buku</strong>
                <span>ditampilkan di katalog</span>
            </div>
            <p>Fitur pinjam, wishlist, filter, dan pilihan jumlah tetap tersedia di setiap kartu.</p>
        </section>

        <div class="row g-3">
            @forelse($books as $book)
                @php
                    $categoryStyle = \App\Models\Book::categoryBadgeStyle($book->category);
                    $stockLevel = $book->stock <= 0 ? 'Habis' : ($book->stock <= 3 ? 'Terbatas' : 'Tersedia');
                    $stockClass = $book->stock <= 0 ? 'out' : ($book->stock <= 3 ? 'limited' : 'ready');
                @endphp

                <div class="col-xl-3 col-lg-3 col-md-6">
                    <article class="book-tile pressable">
                        <div class="book-tile__cover">
                            @if ($book->image_url)
                                <button type="button" class="cover-preview-trigger" data-image="{{ $book->image_url }}"
                                    data-title="{{ $book->title }}" aria-label="Lihat gambar penuh {{ $book->title }}">
                                    <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
                                </button>
                            @else
                                <div class="book-tile__placeholder">
                                    <i class="fas fa-book"></i>
                                </div>
                            @endif

                            <span class="book-stock book-stock--{{ $stockClass }}">{{ $stockLevel }}</span>
                        </div>

                        <div class="book-tile__body">
                            <div class="book-tile__meta">
                                <span class="book-author">{{ Str::limit($book->author ?? 'Penulis tidak diketahui', 22) }}</span>
                                <span class="book-category"
                                    style="background: {{ $categoryStyle['background'] }}; color: {{ $categoryStyle['color'] }};">
                                    {{ $book->category ?? 'Lainnya' }}
                                </span>
                            </div>

                            <h3>{{ Str::limit($book->title, 46) }}</h3>

                            <div class="book-facts">
                                <span><i class="fas fa-box-open"></i> Stok {{ $book->stock }}</span>
                                <span><i class="fas fa-building"></i> {{ Str::limit($book->publisher ?: 'Penerbit belum diisi', 24) }}</span>
                                @if ($book->isbn)
                                    <span><i class="fas fa-barcode"></i> {{ Str::limit($book->isbn, 16) }}</span>
                                @endif
                            </div>

                            @if ($book->description)
                                <p class="book-desc">{{ Str::limit($book->description, 72) }}</p>
                            @endif

                            <div class="book-actions">
                                @if ($book->stock > 0)
                                    <form action="{{ route('siswa.pinjam') }}" method="POST" class="borrow-box">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">

                                        <div class="borrow-controls">
                                            <div>
                                                <label class="form-label mini-label">Jumlah</label>
                                                <select name="quantity" class="form-select form-select-sm">
                                                    @for ($qty = 1; $qty <= $book->stock; $qty++)
                                                        <option value="{{ $qty }}">{{ $qty }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div>
                                                <label class="form-label mini-label">Durasi</label>
                                                <select name="duration_days" class="form-select form-select-sm">
                                                    <option value="3">3 hari</option>
                                                    <option value="7" selected>7 hari</option>
                                                    <option value="14">14 hari</option>
                                                    <option value="21">21 hari</option>
                                                    <option value="30">30 hari</option>
                                                </select>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm w-100 action-btn pressable">
                                            <i class="fas fa-handshake me-1"></i> Pinjam
                                        </button>
                                    </form>

                                    <a href="#" class="btn btn-outline-danger btn-sm w-100 wishlist-btn action-btn pressable"
                                        data-id="{{ $book->id }}">
                                        <i class="fas fa-heart me-1"></i> Wishlist
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm w-100" disabled>
                                        <i class="fas fa-ban me-1"></i> Stok Habis
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-search"></i>
                        <h4>Buku tidak ditemukan</h4>
                        <p>Coba ubah kata kunci atau filter yang sedang dipakai.</p>
                        <a href="{{ route('siswa.jelajahi') }}" class="btn btn-primary action-btn pressable">Reset Pencarian</a>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($books->hasPages())
            <div class="pagination-card">
                <div class="pagination-wrap">
                    <nav aria-label="Navigasi halaman buku">
                        <ul class="pagination pagination-sm">
                            <li class="page-item {{ $books->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $books->previousPageUrl() ?: '#' }}" aria-label="Sebelumnya">
                                    &lsaquo;
                                </a>
                            </li>

                            @foreach ($books->getUrlRange(max(1, $books->currentPage() - 1), min($books->lastPage(), $books->currentPage() + 1)) as $page => $url)
                                <li class="page-item {{ $page == $books->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            <li class="page-item {{ $books->hasMorePages() ? '' : 'disabled' }}">
                                <a class="page-link" href="{{ $books->nextPageUrl() ?: '#' }}" aria-label="Berikutnya">
                                    &rsaquo;
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <div class="image-preview-modal" id="imagePreviewModal" aria-hidden="true">
        <button type="button" class="image-preview-close" id="imagePreviewClose" aria-label="Tutup preview gambar">
            <i class="fas fa-times"></i>
        </button>
        <div class="image-preview-dialog" role="dialog" aria-modal="true" aria-labelledby="imagePreviewTitle">
            <div class="image-preview-media">
                <img id="imagePreviewTarget" src="" alt="">
            </div>
            <div class="image-preview-caption" id="imagePreviewTitle"></div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.wishlist-btn').forEach((btn) => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                fetch('{{ route('siswa.wishlist.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            book_id: this.dataset.id
                        })
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            this.innerHTML = '<i class="fas fa-heart me-1"></i> Ditambahkan';
                            this.classList.remove('btn-outline-danger');
                            this.classList.add('btn-danger');
                            setTimeout(() => location.reload(), 900);
                        }
                    });
            });
        });

        const imagePreviewModal = document.getElementById('imagePreviewModal');
        const imagePreviewTarget = document.getElementById('imagePreviewTarget');
        const imagePreviewTitle = document.getElementById('imagePreviewTitle');
        const imagePreviewClose = document.getElementById('imagePreviewClose');

        document.querySelectorAll('.cover-preview-trigger').forEach((trigger) => {
            trigger.addEventListener('click', function() {
                imagePreviewTarget.src = this.dataset.image;
                imagePreviewTarget.alt = this.dataset.title;
                imagePreviewTitle.textContent = this.dataset.title;
                imagePreviewModal.classList.add('show');
                imagePreviewModal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            });
        });

        const closeImagePreview = () => {
            imagePreviewModal.classList.remove('show');
            imagePreviewModal.setAttribute('aria-hidden', 'true');
            imagePreviewTarget.src = '';
            imagePreviewTarget.alt = '';
            document.body.style.overflow = '';
        };

        imagePreviewClose.addEventListener('click', closeImagePreview);

        imagePreviewModal.addEventListener('click', function(e) {
            if (e.target === imagePreviewModal) {
                closeImagePreview();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && imagePreviewModal.classList.contains('show')) {
                closeImagePreview();
            }
        });
    </script>

    <style>
        .browse-shell {
            display: grid;
            gap: 16px;
        }

        .browse-hero,
        .filter-card,
        .results-strip,
        .pagination-card,
        .book-tile,
        .empty-state {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
        }

        .browse-hero {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            padding: 18px 20px;
            background: linear-gradient(135deg, #0f172a 0%, #1d4ed8 60%, #38bdf8 100%);
            color: #fff;
        }

        .browse-hero__copy h1 {
            margin: 0 0 6px 0;
            font-size: 24px;
            font-weight: 800;
        }

        .browse-hero__copy p {
            margin: 0;
            max-width: 560px;
            font-size: 13px;
            line-height: 1.55;
            opacity: 0.92;
        }

        .hero-tag {
            display: inline-flex;
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .browse-hero__stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            min-width: 160px;
        }

        .hero-stat {
            padding: 10px 12px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.12);
            text-align: center;
        }

        .hero-stat strong {
            display: block;
            font-size: 20px;
            font-weight: 800;
        }

        .hero-stat span {
            font-size: 11px;
            opacity: 0.9;
        }

        .filter-card {
            padding: 18px;
        }

        .filter-card__head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .filter-card__head h2 {
            margin: 0 0 4px 0;
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
        }

        .filter-card__head p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
        }

        .filter-counter {
            display: inline-flex;
            align-items: center;
            padding: 7px 10px;
            border-radius: 999px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: 2fr 1.1fr 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .filter-field {
            display: grid;
            gap: 6px;
        }

        .filter-field--search {
            min-width: 0;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 12px;
        }

        .input-icon input {
            padding-left: 34px;
        }

        .availability-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            min-height: 38px;
            padding: 0 12px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            background: #eff6ff;
            font-size: 12px;
            color: #334155;
            font-weight: 600;
        }

        .availability-toggle input {
            margin: 0;
        }

        .filter-actions {
            display: grid;
            gap: 8px;
        }

        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #334155;
            font-size: 11px;
            font-weight: 700;
        }

        .results-strip {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            align-items: center;
            padding: 10px 14px;
        }

        .results-strip strong {
            color: #0f172a;
            font-size: 16px;
        }

        .results-strip span,
        .results-strip p {
            margin: 0;
            color: #64748b;
            font-size: 12px;
        }

        .pagination-card {
            display: flex;
            justify-content: flex-end;
            padding: 6px 8px;
            border-radius: 12px;
        }

        .pagination-wrap .pagination {
            margin: 0;
            gap: 3px;
        }

        .pagination-wrap .page-link,
        .pagination-wrap .page-item span {
            border: none;
            border-radius: 7px !important;
            padding: 4px 8px;
            font-size: 10px;
            line-height: 1.1;
            font-weight: 700;
            color: #334155;
            background: #f8fafc;
            box-shadow: none;
        }

        .pagination-wrap .page-link:hover {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .pagination-wrap .page-item.active .page-link {
            background: linear-gradient(135deg, #2563eb, #38bdf8);
            color: #fff;
        }

        .pagination-wrap .page-item.disabled .page-link,
        .pagination-wrap .page-item.disabled span {
            color: #94a3b8;
            background: #f1f5f9;
        }

        .book-tile {
            height: 100%;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .book-tile:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.11);
        }

        .book-tile__cover {
            position: relative;
            height: 120px;
            background: linear-gradient(135deg, #dbeafe 0%, #eff6ff 100%);
        }

        .cover-preview-trigger {
            width: 100%;
            height: 100%;
            padding: 0;
            border: none;
            background: transparent;
            cursor: zoom-in;
        }

        .book-tile__cover img,
        .book-tile__placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-tile__placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1d4ed8;
            font-size: 32px;
        }

        .book-stock {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.2px;
        }

        .book-stock--ready {
            background: #dcfce7;
            color: #166534;
        }

        .book-stock--limited {
            background: #fef3c7;
            color: #92400e;
        }

        .book-stock--out {
            background: #fee2e2;
            color: #991b1b;
        }

        .book-tile__body {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding: 12px;
        }

        .book-tile__meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .book-author,
        .book-category {
            display: inline-flex;
            align-items: center;
            padding: 5px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        .book-author {
            background: #0f172a;
            color: #fff;
        }

        .book-tile__body h3 {
            margin: 0;
            font-size: 14px;
            line-height: 1.35;
            font-weight: 800;
            color: #0f172a;
            min-height: 36px;
        }

        .book-facts {
            display: grid;
            gap: 6px;
            font-size: 12px;
            color: #475569;
        }

        .book-facts i {
            width: 14px;
            color: #2563eb;
        }

        .book-desc {
            margin: 0;
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
            min-height: 32px;
        }

        .book-actions {
            display: grid;
            gap: 7px;
            margin-top: auto;
        }

        .borrow-box {
            display: grid;
            gap: 7px;
        }

        .borrow-controls {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 7px;
            padding: 8px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .mini-label {
            margin-bottom: 4px;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
        }

        .action-btn {
            transition: transform 0.12s ease, box-shadow 0.12s ease;
            font-size: 11px;
        }

        .pressable:active,
        .action-btn:active {
            transform: scale(0.98);
        }

        .action-btn:hover {
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.1);
        }

        .empty-state {
            padding: 42px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 42px;
            color: #94a3b8;
            margin-bottom: 14px;
        }

        .empty-state h4 {
            margin-bottom: 8px;
            color: #0f172a;
            font-weight: 800;
        }

        .empty-state p {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .image-preview-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.82);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 3000;
        }

        .image-preview-modal.show {
            display: flex;
        }

        .image-preview-dialog {
            max-width: min(92vw, 860px);
            max-height: 90vh;
            width: 100%;
            display: grid;
            gap: 12px;
        }

        .image-preview-media {
            background: #fff;
            border-radius: 18px;
            padding: 14px;
            box-shadow: 0 20px 44px rgba(15, 23, 42, 0.25);
        }

        .image-preview-media img {
            width: 100%;
            max-height: 72vh;
            object-fit: contain;
            display: block;
            border-radius: 12px;
            background: #f8fafc;
        }

        .image-preview-caption {
            text-align: center;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
        }

        .image-preview-close {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(8px);
        }

        @media (max-width: 1199px) {
            .filter-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .filter-actions {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 768px) {
            .browse-hero,
            .results-strip,
            .filter-card__head {
                flex-direction: column;
                align-items: stretch;
            }

            .browse-hero__stats,
            .filter-grid,
            .borrow-controls {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .browse-hero,
            .filter-card,
            .results-strip,
            .book-tile__body {
                padding: 14px;
            }

            .browse-hero__copy h1 {
                font-size: 21px;
            }

            .book-tile__cover {
                height: 125px;
            }
        }
    </style>
@endsection
