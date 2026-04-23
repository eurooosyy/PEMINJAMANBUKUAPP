@extends('dashboard.siswa-layout')

@section('title', 'Perpanjangan Peminjaman')

@section('content')
    @php
        $activeTab = request('tab', 'active');
    @endphp

    {{-- Enhanced Hero Section --}}
    <div class="page-header">
        <div class="row align-items-center g-3">
            <div class="col">
                <span class="hero-kicker">Manajemen Peminjaman</span>
                <h1><i class="fas fa-sync-alt text-primary"></i> Perpanjangan Peminjaman</h1>
                <p class="lead">Ajukan perpanjangan untuk buku aktif Anda dan pantau status persetujuan secara real-time.
                </p>
            </div>
            <div class="col-auto">
                <div class="hero-pill bg-primary text-white rounded-pill px-4 py-2 shadow-sm">
                    <i class="fas fa-book me-2"></i>
                    <strong>{{ $activeLoans->count() }}</strong> Peminjaman Aktif
                </div>
            </div>
        </div>
    </div>

    {{-- Summary Stat Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card warning">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="stat-label">Menunggu</div>
                        <div class="stat-number">{{ $extensionSummary['pending'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-half fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card success">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="stat-label">Disetujui</div>
                        <div class="stat-number">{{ $extensionSummary['approved'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card danger">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="stat-label">Ditolak</div>
                        <div class="stat-number">{{ $extensionSummary['rejected'] ?? 0 }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="mb-1">
                    <i class="fas fa-{{ $activeTab === 'history' ? 'history' : 'list' }}"></i>
                    {{ $activeTab === 'history' ? 'Riwayat Perpanjangan' : 'Peminjaman Aktif' }}
                </h5>
                <small class="text-muted">Tab:
                    <a href="{{ route('siswa.perpanjangan') }}"
                        class="{{ $activeTab !== 'history' ? 'fw-bold text-primary' : 'text-decoration-none' }}">Aktif</a>
                    |
                    <a href="{{ route('siswa.perpanjangan', ['tab' => 'history']) }}"
                        class="{{ $activeTab === 'history' ? 'fw-bold text-primary' : 'text-decoration-none' }}">Riwayat</a>
                </small>
            </div>
        </div>

        @if ($activeTab !== 'history')
            @if ($activeLoans->count() > 0)
                <div class="row g-4">
                    @foreach ($activeLoans as $loan)
                        @php
                            $daysLeft = $loan->due_date
                                ? \Carbon\Carbon::parse($loan->due_date)->diffInDays(now(), false)
                                : 0;
                            $hasPendingExtension = $loan->extensions->contains(
                                fn($extension) => $extension->status === 'pending',
                            );
                            $approvedCount = $loan->extensions->where('status', 'approved')->count();
                            $canExtend = !$hasPendingExtension && $approvedCount < 2;
                        @endphp
                        <div class="col-lg-6 col-xl-4">
                            <div class="loan-card h-100">
                                <div class="loan-header">
                                    <div>
                                        <span class="badge bg-dark fs-6 mb-2">#{{ $loan->id }}</span>
                                        <h6 class="mb-2">
                                            {{ $loan->loanItems->pluck('equipment.title', 'book.title')->filter()->join(', ') ?: 'Peralatan/Buku tidak tersedia' }}
                                        </h6>
                                    </div>
                                    <span
                                        class="due-chip {{ $daysLeft < 0 ? 'late bg-danger text-white' : ($daysLeft <= 2 ? 'soon bg-warning text-dark' : 'safe bg-success text-white') }} fs-6 px-3 py-1 rounded-pill">
                                        @php
                                            if ($daysLeft > 0) {
                                                echo $daysLeft . ' hari';
                                            } elseif ($daysLeft === 0) {
                                                echo 'Hari ini';
                                            } else {
                                                $totalHours = now()->diffInHours(
                                                    \Carbon\Carbon::parse($loan->due_date),
                                                    false,
                                                );
                                                $lateDays = floor(abs($totalHours / 24));
                                                $lateHours = abs($totalHours % 24);
                                                if ($lateDays > 0) {
                                                    echo 'Terlambat ' . $lateDays . 'h ' . $lateHours . 'j';
                                                } else {
                                                    echo 'Terlambat ' . $lateHours . ' jam';
                                                }
                                            }
                                        @endphp
                                    </span>
                                </div>

                                <div class="loan-meta mt-3 mb-3">
                                    <div class="d-flex justify-content-between text-muted small">
                                        <span><i
                                                class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d M Y') }}</span>
                                        <span><i class="fas fa-sync-alt me-1"></i>{{ $approvedCount }}/2
                                            Perpanjangan</span>
                                    </div>
                                    @if ($approvedCount > 0)
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ ($approvedCount / 2) * 100 }}%"></div>
                                        </div>
                                    @endif
                                </div>

                                <div class="book-list mb-4">
                                    @foreach ($loan->loanItems->take(3) as $item)
                                        <span
                                            class="book-pill bg-light border rounded-pill px-3 py-1 small d-inline-block me-2 mb-1">
                                            <i
                                                class="fas fa-book-open me-1 text-muted"></i>{{ Str::limit($item->equipment->title ?? ($item->book->title ?? '-'), 20) }}
                                        </span>
                                    @endforeach
                                    @if ($loan->loanItems->count() > 3)
                                        <span
                                            class="book-pill bg-light border rounded-pill px-3 py-1 small text-muted">+{{ $loan->loanItems->count() - 3 }}
                                            lainnya</span>
                                    @endif
                                </div>

                                @if ($hasPendingExtension)
                                    <div class="alert alert-warning border-0 rounded-3 mb-3 p-3">
                                        <i class="fas fa-hourglass-half me-2"></i>
                                        Permintaan perpanjangan sedang menunggu persetujuan petugas.
                                    </div>
                                @endif

                                @if ($canExtend)
                                    <form action="{{ route('siswa.perpanjangan.request') }}" method="POST"
                                        class="extension-form">
                                        @csrf
                                        <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Alasan Perpanjangan</label>
                                            <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="3" maxlength="500"
                                                placeholder="Jelaskan alasan perpanjangan (contoh: masih digunakan untuk tugas/proyek)"></textarea>
                                            @error('reason')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-paper-plane me-2"></i>Ajukan Perpanjangan (+7 Hari)
                                        </button>
                                    </form>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-lock fa-2x text-muted mb-2"></i>
                                        <div class="text-muted">Tidak dapat diperpanjang lagi</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state text-center py-5 my-5 border rounded-4 bg-light">
                    <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                    <h4 class="mb-3">Tidak Ada Peminjaman Aktif</h4>
                    <p class="text-muted mb-4">Belum ada buku yang dapat diajukan perpanjangan.</p>
                    <a href="{{ route('siswa.jelajahi') }}" class="btn btn-outline-primary">
                        <i class="fas fa-search me-2"></i>Jelajahi Buku
                    </a>
                </div>
            @endif
        @else
            @if ($extensions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>ID</th>
                                <th><i class="fas fa-calendar me-1"></i>Permintaan</th>
                                <th><i class="fas fa-calendar-times me-1"></i>Dari</th>
                                <th><i class="fas fa-calendar-plus me-1"></i>Menjadi</th>
                                <th><i class="fas fa-info-circle me-1"></i>Status</th>
                                <th><i class="fas fa-align-left me-1"></i>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($extensions as $extension)
                                <tr>
                                    <td><strong>#{{ $extension->loan_id }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($extension->created_at)->format('d M Y') }}</td>
                                    <td><span
                                            class="badge bg-secondary">{{ \Carbon\Carbon::parse($extension->old_due_date)->format('d M Y') }}</span>
                                    </td>
                                    <td><span
                                            class="badge bg-info">{{ \Carbon\Carbon::parse($extension->new_due_date)->format('d M Y') }}</span>
                                    </td>
                                    <td>
                                        @php $statusClass = $extension->status === 'approved' ? 'bg-success' : ($extension->status === 'pending' ? 'bg-warning text-dark' : 'bg-danger'); @endphp
                                        <span class="badge {{ $statusClass }} px-3 py-2 fs-6">
                                            <i
                                                class="fas {{ $extension->status === 'pending' ? 'fa-hourglass-half' : ($extension->status === 'approved' ? 'fa-check' : 'fa-times') }} me-1"></i>
                                            {{ $extension->status === 'pending' ? 'Menunggu' : ucfirst($extension->status) }}
                                        </span>
                                    </td>
                                    <td class="text-truncate" style="max-width: 200px;"
                                        title="{{ $extension->reason ?: '-' }}">
                                        {{ Str::limit($extension->reason ?: '-', 50) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $extensions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="empty-state text-center py-5 my-5 border rounded-4 bg-light">
                    <i class="fas fa-history fa-4x text-muted mb-4"></i>
                    <h4 class="mb-3">Belum Ada Riwayat</h4>
                    <p class="text-muted mb-4">Riwayat perpanjangan akan muncul setelah mengajukan permintaan.</p>
                    <a href="{{ route('siswa.perpanjangan') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Lihat Peminjaman Aktif
                    </a>
                </div>
            @endif
        @endif
    </div>

    <style>
        :root {
            --card-radius: 16px;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 24px rgba(0, 0, 0, 0.12);
            --primary-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .page-header {
            background: white;
            padding: 2.5rem;
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
        }

        .hero-kicker {
            font-size: 0.875rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 500;
        }

        .page-header h1 {
            font-size: 2.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .lead {
            color: #6b7280;
            font-size: 1.125rem;
            margin-bottom: 0;
        }

        .stat-card {
            background: white;
            border-radius: var(--card-radius);
            padding: 1.75rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            border-left: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }

        .stat-card.warning {
            border-left-color: #f59e0b;
        }

        .stat-card.success {
            border-left-color: #10b981;
        }

        .stat-card.danger {
            border-left-color: #ef4444;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
            font-weight: 600;
        }

        .loan-card {
            background: white;
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-sm);
            padding: 1.75rem;
            transition: all 0.3s ease;
            border: 1px solid #f3f4f6;
        }

        .loan-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .loan-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .due-chip {
            font-weight: 600;
            min-width: 90px;
            text-align: center;
            font-size: 0.8rem;
        }

        .book-pill {
            font-size: 0.8rem;
            border: 1px solid #e5e7eb;
        }

        .extension-form .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        }

        .table {
            --bs-table-bg: white;
            box-shadow: var(--shadow-sm);
            border-radius: var(--card-radius);
            overflow: hidden;
            margin-bottom: 0;
        }

        .table thead th {
            border: none;
            font-size: 0.85rem;
            font-weight: 700;
            color: #374151;
            padding: 1.25rem 1rem;
        }

        .table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border-color: #f9fafb;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .empty-state {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px dashed #cbd5e1;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loan-card,
        .stat-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .stat-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stat-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stat-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.75rem;
            }

            .loan-card {
                margin-bottom: 1.5rem;
            }

            .book-list {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }

        /* Loading spinner */
        .extension-form button:disabled {
            position: relative;
        }

        .extension-form button:disabled::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
