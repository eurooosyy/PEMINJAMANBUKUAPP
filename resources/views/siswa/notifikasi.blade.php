@extends('dashboard.siswa-layout')

@section('title', 'Notifikasi')

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-bell"></i> Notifikasi</h1>
        <p>Pemberitahuan penting tentang peminjaman Anda</p>
    </div>

    <!-- Counters -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <i class="fas fa-circle fa-2x mb-2 opacity-75"></i>
                    <h3>{{ $unreadCount }}</h3>
                    <small>Belum Dibaca</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-light text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                    <h3>{{ $notifications->where('is_read', true)->count() }}</h3>
                    <small>Sudah Dibaca</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi List -->
    <div class="card shadow-lg">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Daftar Notifikasi</h5>
            @if ($notifications->where('is_read', false)->count() > 0)
                <form method="POST" action="{{ route('siswa.notifikasi.read-all') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">Tandai Semua Terbaca</button>
                </form>
            @endif
        </div>
        <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
            @forelse($notifications as $notification)
                <a href="#"
                    class="list-group-item list-group-item-action {{ $notification->is_read ? 'read' : 'unread' }}"
                    onclick="markRead({{ $notification->id }})">
                    <div class="d-flex w-100 justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="notification-icon me-3">
                                @if ($notification->type == 'overdue')
                                    <i class="fas fa-exclamation-triangle text-danger"></i>
                                @elseif($notification->type == 'ready')
                                    <i class="fas fa-check-circle text-success"></i>
                                @elseif($notification->type == 'extension_approved')
                                    <i class="fas fa-clock text-warning"></i>
                                @else
                                    <i class="fas fa-info-circle text-info"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $notification->title }}</h6>
                                <p class="mb-1 small text-muted">{{ Str::limit($notification->message, 80) }}</p>
                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                    @if (!$notification->is_read)
                                        <span class="badge bg-primary ms-2">Baru</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                        @if (!$notification->is_read)
                            <span class="badge rounded-pill bg-primary">Baru</span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-bell-slash fa-3x mb-3"></i>
                    <p>Tidak ada notifikasi baru</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function markRead(id) {
            fetch(`/siswa/notifikasi/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            }).then(() => {
                location.reload();
            });
        }
    </script>

    <style>
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .unread {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }

        .unread:hover {
            background-color: #ffeaa7;
        }

        .notification-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .list-group-item-action:hover {
            text-decoration: none;
        }
    </style>
@endsection
