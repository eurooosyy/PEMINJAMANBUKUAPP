<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Siswa Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 0;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .sidebar-header p {
            margin: 5px 0 0 0;
            font-size: 13px;
            opacity: 0.9;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 30px;
        }

        .sidebar-menu i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .sidebar-menu span span {
            display: inline-block;
            margin-left: auto;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
        }

        .notification-badge {
            background: #ff4757 !important;
            color: white !important;
            padding: 2px 6px !important;
            border-radius: 10px !important;
            font-size: 10px !important;
            font-weight: 700 !important;
            margin-left: 5px !important;
            animation: pulse 2s infinite;
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 0;
        }

        .navbar-siswa {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0;
            border-bottom: 3px solid #00f2fe;
        }

        .navbar-siswa .container-fluid {
            padding: 15px 25px;
        }

        .navbar-siswa .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4facfe;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .dropdown-toggle::after {
            display: none;
        }

        .dropdown-menu {
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 10px 16px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: #f0f8ff;
            color: #4facfe;
        }

        .dropdown-item i {
            margin-right: 8px;
            min-width: 16px;
        }

        .page-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .desktop-sidebar-toggle {
            position: fixed;
            top: 18px;
            left: 248px;
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 999px;
            background: #ffffff;
            color: #1d4ed8;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.14);
            z-index: 1100;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: left 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .desktop-sidebar-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.18);
        }

        body.sidebar-collapsed .desktop-sidebar-toggle {
            left: 14px;
        }

        .desktop-sidebar-toggle .icon-open {
            display: none;
        }

        body.sidebar-collapsed .desktop-sidebar-toggle .icon-open {
            display: inline-block;
        }

        body.sidebar-collapsed .desktop-sidebar-toggle .icon-close {
            display: none;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #666;
            font-size: 14px;
        }

        /* Stats Card */
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 4px solid #4facfe;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card.primary {
            border-left-color: #4facfe;
        }

        .stat-card.success {
            border-left-color: #28a745;
        }

        .stat-card.info {
            border-left-color: #17a2b8;
        }

        .stat-card.warning {
            border-left-color: #ffc107;
        }

        .stat-card.danger {
            border-left-color: #dc3545;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            color: #333;
            margin: 10px 0;
        }

        .stat-label {
            font-size: 13px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-icon {
            font-size: 32px;
            opacity: 0.2;
            text-align: right;
        }

        /* Table */
        .table-wrapper {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .table-wrapper h5 {
            margin-bottom: 20px;
            font-weight: 700;
            color: #333;
        }

        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #4facfe;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            font-size: 12px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-sm-custom {
            padding: 5px 10px;
            font-size: 12px;
            margin: 2px;
        }

        /* Book Card */
        .book-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .book-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
        }

        .book-info {
            padding: 15px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .book-title {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .book-author {
            color: #666;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .book-stock {
            font-size: 12px;
            color: #999;
            margin-bottom: 10px;
        }

        .book-actions {
            margin-top: auto;
        }

        /* Quick Actions Buttons */
        .table-wrapper .btn {
            padding: 10px 18px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .table-wrapper .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table-wrapper .d-flex {
            gap: 12px !important;
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .page-header h1 {
            color: #00f2fe;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .page-content {
                padding: 15px;
            }

            .desktop-sidebar-toggle {
                display: none;
            }
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hidden {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body>
    <button class="desktop-sidebar-toggle" type="button" aria-label="Toggle sidebar">
        <i class="fas fa-chevron-left icon-close"></i>
        <i class="fas fa-chevron-right icon-open"></i>
    </button>

    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar scrollbar-hidden">
            <div class="sidebar-header">
                <h3><i class="fas fa-book"></i> PeminjamanBuku</h3>
                <p>Siswa/User</p>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('siswa.dashboard') }}"
                        class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.riwayat') }}"
                        class="{{ request()->routeIs('siswa.riwayat') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Riwayat Peminjaman</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.jelajahi') }}"
                        class="{{ request()->routeIs('siswa.jelajahi') ? 'active' : '' }}">
                        <i class="fas fa-search"></i>
                        <span>Jelajahi Buku</span>

                    </a>
                </li>
                <li>
                    <a href="{{ route('siswa.tambah-peminjaman') }}"
                        class="{{ request()->routeIs('siswa.tambah-peminjaman') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Peminjaman</span>
                    </a>
                </li>

                <!-- Divider -->
                <li style="border-bottom: 2px solid rgba(255, 255, 255, 0.3); margin: 10px 0;">
                    <span
                        style="padding: 15px 20px; display: block; color: rgba(255, 255, 255, 0.5); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">
                        Fitur Tambahan
                    </span>
                </li>

                <!-- Profil Siswa -->
                <li>
                    <a href="{{ route('siswa.profil') }}"
                        class="{{ request()->routeIs('siswa.profil') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>Profil Saya</span>
                    </a>
                </li>

                <!-- Wishlist -->
                <li>
                    <a href="{{ route('siswa.wishlist') }}"
                        class="{{ request()->routeIs('siswa.wishlist') ? 'active' : '' }}">
                        <i class="fas fa-heart"></i>
                        <span>Wishlist Buku</span>
                    </a>
                </li>

                <!-- Reservasi -->
                <li>
                    <a href="{{ route('siswa.reservasi') }}"
                        class="{{ request()->routeIs('siswa.reservasi') ? 'active' : '' }}">
                        <i class="fas fa-bookmark"></i>
                        <span>Reservasi Buku</span>

                    </a>
                </li>

                <!-- Perpanjangan -->
                <li>
                    <a href="{{ route('siswa.perpanjangan') }}"
                        class="{{ request()->routeIs('siswa.perpanjangan') ? 'active' : '' }}">
                        <i class="fas fa-sync-alt"></i>
                        <span>Perpanjangan</span>
                    </a>
                </li>

                <!-- Notifikasi -->
                <li>
                    <a href="{{ route('siswa.notifikasi') }}"
                        class="{{ request()->routeIs('siswa.notifikasi') ? 'active' : '' }}">
                        <i class="fas fa-bell"></i>
                        <span>
                            Notifikasi
                            @php
                                $unreadCount = \App\Models\StudentNotification::where('user_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            @if ($unreadCount > 0)
                                <span
                                    style="margin-left: 5px; background: #ff4757; padding: 2px 6px; border-radius: 10px; font-size: 10px; font-weight: 700;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </span>
                    </a>
                </li>

                <!-- Denda -->
                <li>
                    <a href="{{ route('siswa.denda') }}"
                        class="{{ request()->routeIs('siswa.denda') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Daftar Denda</span>
                    </a>
                </li>

                <!-- Download Riwayat -->
                <li>
                    <a href="{{ route('siswa.download-riwayat') }}"
                        class="{{ request()->routeIs('siswa.download-riwayat') ? 'active' : '' }}">
                        <i class="fas fa-download"></i>
                        <span>Download Riwayat</span>
                    </a>
                </li>

                <li style="border-bottom: 2px solid rgba(255, 255, 255, 0.2); margin-top: 10px;">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; width: 100%; text-align: left;">
                            <a
                                style="display: flex; align-items: center; padding: 15px 20px; color: rgba(255, 255, 255, 0.8); text-decoration: none; cursor: pointer;">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar-siswa">
                <div class="container-fluid">
                    <button class="btn btn-link d-md-none p-0 me-3 sidebar-toggle" type="button"
                        style="color: #333; font-size: 1.5rem;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex justify-content-between align-items-center w-100 flex-grow-1">

                        <div style="display: flex; align-items: center; gap: 20px;">
                            <h5 class="m-0">@yield('title')</h5>
                            @php
                                $currentBorrower = \App\Models\Borrower::firstOrCreate(
                                    ['user_id' => auth()->id()],
                                    ['nis' => 'NIS-' . auth()->id(), 'class' => '-', 'phone' => '-'],
                                );
                                $activeLoans = \App\Models\Loan::where('borrower_id', $currentBorrower->id)
                                    ->where('status', 'active')
                                    ->count();
                                $pendingExtensions = \App\Models\LoanExtension::whereHas('loan', function ($q) use ($currentBorrower) {
                                    $q->where('borrower_id', $currentBorrower->id);
                                })
                                    ->where('status', 'pending')
                                    ->count();
                            @endphp
                            @if ($activeLoans > 0 || $pendingExtensions > 0)
                                <span
                                    style="font-size: 11px; color: #666; background: #fff3cd; padding: 6px 10px; border-radius: 6px;">
                                    @if ($activeLoans > 0)
                                        <i class="fas fa-book" style="color: #4facfe;"></i> {{ $activeLoans }}
                                        Peminjaman Aktif
                                    @endif
                                    @if ($pendingExtensions > 0 && $activeLoans > 0)
                                        |
                                    @endif
                                    @if ($pendingExtensions > 0)
                                        <i class="fas fa-hourglass-half" style="color: #f39c12;"></i>
                                        {{ $pendingExtensions }} Perpanjangan Menunggu
                                    @endif
                                </span>
                            @endif
                        </div>
                        <div class="user-info" style="display: flex; align-items: center; gap: 15px;">
                            @php
                                $unreadNotifications = \App\Models\StudentNotification::where('user_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp
                            @if ($unreadNotifications > 0)
                                <a href="{{ route('siswa.notifikasi') }}"
                                    style="position: relative; color: #333; text-decoration: none;">
                                    <i class="fas fa-bell" style="font-size: 18px;"></i>
                                    <span
                                        style="position: absolute; top: -5px; right: -8px; background: #ff4757; color: white; font-size: 10px; padding: 2px 5px; border-radius: 10px; font-weight: 700;">
                                        {{ $unreadNotifications }}
                                    </span>
                                </a>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-link dropdown-toggle p-0" type="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false"
                                    style="color: #333; text-decoration: none;">
                                    <span>{{ auth()->user()->name }}</span>
                                    <div class="user-avatar" style="width: 35px; height: 35px; font-size: 14px;">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </div>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="{{ route('siswa.profil') }}"><i
                                                class="fas fa-user"></i> Profil Saya</a></li>
                                    <li><a class="dropdown-item" href="{{ route('siswa.notifikasi') }}"><i
                                                class="fas fa-bell"></i> Notifikasi</a></li>
                                    <li><a class="dropdown-item" href="{{ route('siswa.denda') }}"><i
                                                class="fas fa-file-invoice-dollar"></i> Daftar Denda</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i
                                                    class="fas fa-sign-out-alt"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="page-content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="m-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const desktopSidebarToggle = document.querySelector('.desktop-sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const body = document.body;
            const mobileBreakpoint = window.matchMedia('(max-width: 768px)');

            const syncSidebarState = () => {
                if (mobileBreakpoint.matches) {
                    mainContent.style.marginLeft = '0';
                    return;
                }

                mainContent.style.marginLeft = body.classList.contains('sidebar-collapsed') ? '0' : '280px';
            };

            const toggleDesktopSidebar = () => {
                body.classList.toggle('sidebar-collapsed');
                syncSidebarState();
            };

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    mainContent.style.marginLeft = sidebar.classList.contains('show') ? '280px' : '0';
                });
            }

            if (desktopSidebarToggle) {
                desktopSidebarToggle.addEventListener('click', function() {
                    if (mobileBreakpoint.matches) {
                        sidebar.classList.toggle('show');
                        return;
                    }

                    toggleDesktopSidebar();
                });
            }

            // Close sidebar on outside click
            document.addEventListener('click', function(e) {
                if (
                    mobileBreakpoint.matches &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle.contains(e.target)
                ) {
                    sidebar.classList.remove('show');
                    mainContent.style.marginLeft = '0';
                }
            });

            window.addEventListener('resize', syncSidebarState);
            syncSidebarState();
        });
    </script>

</body>

</html>
