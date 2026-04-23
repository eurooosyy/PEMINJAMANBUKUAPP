<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Petugas Dashboard</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            justify-content: space-between;
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

        .sidebar-menu .dropdown-toggle::after {
            content: '';
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid rgba(255, 255, 255, 0.6);
            transition: transform 0.3s;
            margin-left: auto;
        }

        .sidebar-menu .dropdown-toggle[aria-expanded="true"]::after {
            transform: rotate(180deg);
        }

        .sidebar-menu .collapse {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .sidebar-menu .collapse.show {
            max-height: 500px;
        }

        .sidebar-menu .collapse .sidebar-menu {
            padding: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu .collapse a {
            padding: 12px 20px 12px 50px;
            font-size: 14px;
            border-left: 3px solid transparent;
        }

        .sidebar-menu .collapse a:hover {
            border-left-color: #f5576c;
            background-color: rgba(245, 87, 108, 0.1);
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

        .navbar-petugas {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0;
            border-bottom: 3px solid #f5576c;
        }

        .navbar-petugas .container-fluid {
            padding: 15px 25px;
        }

        .navbar-petugas .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #f5576c;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
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
            color: #f5576c;
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.16);
            z-index: 1100;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: left 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .desktop-sidebar-toggle:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.2);
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
            border-left: 4px solid #f5576c;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card.primary {
            border-left-color: #f5576c;
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
            border-bottom: 2px solid #f5576c;
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
            color: #f5576c;
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
                <p>Petugas Panel</p>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('petugas.dashboard') }}"
                        class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('petugas.buku') }}"
                        class="{{ request()->routeIs('petugas.buku') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Data Buku</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('books.create') }}"
                        class="{{ request()->routeIs('books.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>Tambah Buku</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('petugas.peminjaman') }}"
                        class="{{ request()->routeIs('petugas.peminjaman') ? 'active' : '' }}">
                        <i class="fas fa-hand-holding-heart"></i>
                        <span>Peminjaman</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('petugas.pengembalian') }}"
                        class="{{ request()->routeIs('petugas.pengembalian') ? 'active' : '' }}">
                        <i class="fas fa-undo"></i>
                        <span>Pengembalian</span>
                    </a>
                </li>
                <li>
                    <a href="#reportMenu" class="dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#reportMenu"
                        role="button" aria-expanded="false" aria-controls="reportMenu">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                    <div class="collapse" id="reportMenu">
                        <ul class="sidebar-menu">
                            <li><a href="{{ route('reports.statistics') }}"><i class="fas fa-chart-line"></i>
                                    <span>Statistik</span></a></li>
                            <li><a href="{{ route('reports.borrowing') }}"><i class="fas fa-file-alt"></i>
                                    <span>Peminjaman</span></a></li>
                            <li><a href="{{ route('reports.overdue') }}"><i class="fas fa-clock"></i>
                                    <span>Keterlambatan</span></a></li>
                            <li><a href="{{ route('reports.returns') }}"><i class="fas fa-check-circle"></i>
                                    <span>Pengembalian</span></a></li>
                            <li><a href="{{ route('reports.popular-books') }}"><i class="fas fa-star"></i> <span>Buku
                                        Populer</span></a></li>
                            <li><a href="{{ route('reports.borrowers') }}"><i class="fas fa-users"></i>
                                    <span>Peminjam</span></a></li>
                        </ul>
                    </div>
                </li>
                <li style="border-bottom: 2px solid rgba(255, 255, 255, 0.2); margin-top: 10px;">
                    <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                        @csrf
                        <button type="submit"
                            style="background: none; border: none; width: 100%; text-align: left; padding: 0; cursor: pointer;">
                            <span
                                style="display: flex; align-items: center; padding: 15px 20px; color: rgba(255, 255, 255, 0.8); text-decoration: none;">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </span>
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar-petugas">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <button class="btn btn-link d-md-none p-0 me-3 sidebar-toggle" type="button"
                            style="color: #333; font-size: 1.5rem;">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h5 class="m-0">@yield('title')</h5>
                        <div class="user-info">
                            <span>{{ auth()->user()->name }}</span>
                            <div class="user-avatar">
                                {{ substr(auth()->user()->name, 0, 1) }}
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
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            if (desktopSidebarToggle) {
                desktopSidebarToggle.addEventListener('click', function() {
                    if (mobileBreakpoint.matches) {
                        sidebar.classList.toggle('show');
                        return;
                    }

                    body.classList.toggle('sidebar-collapsed');
                    syncSidebarState();
                });
            }

            document.addEventListener('click', function(e) {
                if (
                    mobileBreakpoint.matches &&
                    sidebarToggle &&
                    !sidebar.contains(e.target) &&
                    !sidebarToggle.contains(e.target)
                ) {
                    sidebar.classList.remove('show');
                }
            });

            window.addEventListener('resize', syncSidebarState);
            syncSidebarState();
        });
    </script>
</body>

</html>
