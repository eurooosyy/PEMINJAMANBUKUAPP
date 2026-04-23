@extends('dashboard.siswa-layout')

@section('title', 'Wishlist')

@section('content')
    <div class="wishlist-hero">
        <div>
            <span class="hero-kicker">Favorit Siswa</span>
            <h1><i class="fas fa-heart"></i> Wishlist Buku</h1>
            <p>Simpan buku yang menarik untuk dipinjam nanti, lalu akses kembali dengan cepat saat stok tersedia.</p>
        </div>
    </div>

    <div class="summary-grid">
        <div class="summary-card rose">
            <span>Total Wishlist</span>
            <strong>{{ $wishlistSummary['total'] ?? 0 }}</strong>
        </div>
        <div class="summary-card emerald">
            <span>Masih Tersedia</span>
            <strong>{{ $wishlistSummary['available'] ?? 0 }}</strong>
        </div>
        <div class="summary-card slate">
            <span>Stok Habis</span>
            <strong>{{ $wishlistSummary['out_of_stock'] ?? 0 }}</strong>
        </div>
    </div>

    @if ($wishlist->count() > 0)
        <div class="wishlist-grid">
            @foreach ($wishlist as $item)
                @php
                    $book = $item->book;
                    $isAvailable = ($book->stock ?? 0) > 0;
                @endphp
                <div class="wish-card">
                    <div class="wish-cover">
                        @if ($book?->image_url)
                            <img src="{{ $book->image_url }}" alt="{{ $book->title }}">
                        @else
                            <div class="cover-placeholder">
                                <i class="fas fa-book"></i>
                            </div>
                        @endif
                        <span class="stock-badge {{ $isAvailable ? 'ready' : 'out' }}">
                            {{ $isAvailable ? 'Tersedia' : 'Stok Habis' }}
                        </span>
                    </div>

                    <div class="wish-body">
                        <h3>{{ $book->title }}</h3>
                        <p class="wish-meta">{{ $book->author ?? 'Penulis tidak diketahui' }} | {{ $book->publisher ?? 'Penerbit belum diisi' }}</p>

                        <div class="wish-details">
                            <span><i class="fas fa-box-open"></i> Stok {{ $book->stock ?? 0 }}</span>
                            @if ($book->category)
                                <span><i class="fas fa-tag"></i> {{ $book->category }}</span>
                            @endif
                        </div>

                        <div class="wish-actions">
                            <form action="{{ route('siswa.wishlist.remove', $book->id) }}" method="POST"
                                onsubmit="return confirm('Hapus buku ini dari wishlist?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-1"></i> Hapus dari Wishlist
                                </button>
                            </form>

                            <a href="{{ route('siswa.tambah-peminjaman') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-1"></i> Pinjam Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $wishlist->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-heart-broken"></i>
            <h4>Wishlist masih kosong</h4>
            <p>Tambahkan buku favorit dari halaman jelajahi agar bisa Anda simpan untuk dipinjam nanti.</p>
            <a href="{{ route('siswa.jelajahi') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-search me-2"></i> Jelajahi Buku
            </a>
        </div>
    @endif

    <style>
        .wishlist-hero,
        .wish-card,
        .empty-state {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
        }

        .wishlist-hero {
            padding: 28px;
            margin-bottom: 22px;
            background: linear-gradient(135deg, #881337 0%, #e11d48 55%, #fb7185 100%);
            color: #fff;
        }

        .hero-kicker {
            display: inline-flex;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .wishlist-hero h1 {
            margin: 0 0 8px 0;
            font-size: 30px;
            font-weight: 800;
        }

        .wishlist-hero p {
            margin: 0;
            max-width: 680px;
            line-height: 1.7;
            opacity: 0.94;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 22px;
        }

        .summary-card {
            padding: 18px 20px;
            border-radius: 18px;
            color: #fff;
        }

        .summary-card span {
            display: block;
            font-size: 13px;
        }

        .summary-card strong {
            display: block;
            margin-top: 8px;
            font-size: 30px;
            font-weight: 800;
        }

        .summary-card.rose { background: linear-gradient(135deg, #e11d48, #fb7185); }
        .summary-card.emerald { background: linear-gradient(135deg, #16a34a, #4ade80); }
        .summary-card.slate { background: linear-gradient(135deg, #334155, #64748b); }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .wish-card {
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .wish-cover {
            position: relative;
            aspect-ratio: 4 / 3;
            background: linear-gradient(135deg, #ffe4e6, #ffe4e6 35%, #ffeef2 100%);
        }

        .wish-cover img,
        .cover-placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .cover-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e11d48;
            font-size: 48px;
        }

        .stock-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }

        .stock-badge.ready { background: #dcfce7; color: #166534; }
        .stock-badge.out { background: #fee2e2; color: #991b1b; }

        .wish-body {
            padding: 18px;
        }

        .wish-body h3 {
            margin: 0 0 8px 0;
            font-size: 18px;
            color: #0f172a;
            font-weight: 800;
        }

        .wish-meta {
            margin: 0 0 12px 0;
            color: #64748b;
            font-size: 13px;
            line-height: 1.6;
        }

        .wish-details {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 16px;
        }

        .wish-details span {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-size: 12px;
            font-weight: 700;
        }

        .wish-actions {
            display: grid;
            gap: 10px;
        }

        .empty-state {
            padding: 56px 24px;
            text-align: center;
        }

        .empty-state i {
            font-size: 52px;
            color: #94a3b8;
            margin-bottom: 16px;
        }

        @media (max-width: 768px) {
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection
