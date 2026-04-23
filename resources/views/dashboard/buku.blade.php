@extends('dashboard.layout')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">📚 Data Buku Perpustakaan</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse(\App\Models\Book::all() as $book)
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <div class="card-img-top"
                                            style="height: 200px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-size: 48px; overflow: hidden;">
                                            @if ($book->image)
                                                <img src="{{ asset($book->image) }}" alt="{{ $book->title }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                📖
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $book->title }}</h5>
                                            <p class="card-text">
                                                <strong>Penulis:</strong> {{ $book->author }}<br>
                                                @if ($book->publisher)
                                                    <strong>Penerbit:</strong> {{ $book->publisher }}<br>
                                                @endif
                                                <strong>Stok:</strong> {{ $book->stock }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h5>Tidak ada buku tersedia</h5>
                                        <p>Belum ada buku yang ditambahkan ke perpustakaan.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
