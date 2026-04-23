@extends('dashboard.siswa-layout')

@section('title', 'Jelajahi Buku')

@section('content')
    <div class="page-header mb-4">
        <h1><i class="fas fa-book-open"></i> Jelajahi Buku</h1>
        <p>Temukan buku perpustakaan yang tersedia untuk dipinjam</p>
    </div>

    <!-- Search & Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari buku, penulis, atau kategori..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach (['Fiksi', 'Non-Fiksi', 'Sains', 'Sejarah', 'Biografi', 'Pendidikan'] as $kategori)
                            <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>
                                {{ $kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="available" id="available"
                            {{ request('available') ? 'checked' : '' }} value="1">
                        <label class="form-check-label" for="available">
                            Tersedia
                        </label>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Buku Grid -->
    <div class="row g-4">
        @forelse($equipment as $book)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm hover-card book-card">
                    @if ($book->image)
                        <img src="{{ asset('storage/' . $book->image) }}" class="card-img-top" alt="{{ $book->title }}"
                            style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-placeholder bg-gradient" style="height: 200px;">
                            <i class="fas fa-book fa-3x text-white"></i>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-2">{{ Str::limit($book->title, 40) }}</h6>
                        <div class="mb-2">
                            <span class="badge bg-dark me-1">{{ Str::limit($book->author, 20) }}</span>
                            <span class="badge bg-secondary">{{ $book->category ?? 'Umum' }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Stok: <strong>{{ $book->stock }}</strong></small>
                            @if ($book->isbn)
                                <small class="text-muted d-block">ISBN: {{ $book->isbn }}</small>
                            @endif
                        </div>
                        <div class="mt-auto">
                            @if ($book->stock > 0)
                                <form action="{{ route('siswa.pinjam') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <button type="submit" class="btn btn-primary w-100 mb-1">
                                        <i class="fas fa-handshake me-1"></i> Pinjam
                                    </button>
                                </form>
                                <a href="#" class="btn btn-outline-danger btn-sm w-100 wishlist-btn"
                                    data-id="{{ $book->id }}">
                                    <i class="fas fa-heart me-1"></i> Wishlist
                                </a>
                            @else
                                <button class="btn btn-secondary w-100 disabled" disabled>
                                    <i class="fas fa-ban me-1"></i> Habis
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-4"></i>
                    <h4>Buku tidak ditemukan</h4>
                    <p class="text-muted mb-4">Coba ubah kata kunci pencarian atau filter</p>
                    <a href="{{ route('siswa.jelajahi') }}" class="btn btn-primary btn-lg">Cari Lagi</a>
                </div>
            </div>
        @endforelse
    </div>

    {{ $equipment->appends(request()->query())->links() }}

    <script>
        document.querySelectorAll('.wishlist-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const bookId = this.dataset.id;

                fetch('{{ route('siswa.wishlist.add') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            book_id: bookId
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.innerHTML =
                                '<i class="fas fa-heart text-danger me-1"></i> Ditambahkan!';
                            this.classList.remove('btn-outline-danger');
                            this.classList.add('btn-danger');
                            setTimeout(() => location.reload(), 1500);
                        }
                    });
            });
        });
    </script>

    <style>
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .hover-card {
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .card-img-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px 10px 0 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>

@endsection
