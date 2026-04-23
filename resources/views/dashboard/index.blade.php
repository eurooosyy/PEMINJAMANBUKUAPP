@extends('dashboard.layout')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body bg-primary text-white rounded">
                        <h1 class="card-title">📚 Selamat Datang, {{ Auth::user()->name }}!</h1>
                        <p class="card-text">Kelola peminjaman buku Anda dengan mudah melalui dashboard ini.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-left-primary">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Buku Tersedia
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalBooks }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-book fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-left-success">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Buku Tersedia
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableBooks }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-left-info">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Peminjaman Aktif
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userActiveLoans }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-left-warning">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Peminjaman
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userTotalLoans }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-history fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Loans Section -->
        @if ($activeLoans->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fas fa-book-reader"></i> Buku yang Sedang Dipinjam</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Judul Buku</th>
                                            <th>Tanggal Pinjam</th>
                                            <th>Tanggal Kembali</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activeLoans as $loan)
                                            @foreach ($loan->loanItems as $item)
                                                <tr>
                                                    <td>{{ $item->book->title }}</td>
                                                    <td>{{ $loan->loan_date->format('d/m/Y') }}</td>
                                                    <td>{{ $loan->due_date->format('d/m/Y') }}</td>
                                                    <td>
                                                        <span class="badge badge-info">Aktif</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Books Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-star"></i> Buku Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($recentBooks as $book)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $book->title }}</h6>
                                            <p class="card-text text-muted">Penulis: {{ $book->author }}</p>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    Stok: {{ $book->stock }} | ISBN: {{ $book->isbn }}
                                                </small>
                                            </p>
                                            @if ($book->stock > 0)
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="borrowBook({{ $book->id }})">
                                                    <i class="fas fa-plus"></i> Pinjam
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="fas fa-times"></i> Stok Habis
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function borrowBook(bookId) {
            if (confirm('Apakah Anda ingin meminjam buku ini?')) {
                // Redirect to catalog with intended book
                window.location.href = '{{ route('catalog') }}?borrow=' + bookId;
            }
        }
    </script>
@endsection
