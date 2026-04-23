<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Peminjaman Buku</title>
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

        /* ===================== SIDEBAR ===================== */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
        }

        .sidebar-header {
            padding: 25px 20px;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            border-bottom: 3px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .sidebar-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .sidebar-header h3 i {
            font-size: 24px;
        }

        .sidebar-header p {
            margin: 8px 0 0 0;
            font-size: 12px;
            opacity: 0.85;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 20px;
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 14px;
            position: relative;
        }

        .sidebar-menu a:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(180deg, #e74c3c 0%, #c0392b 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar-menu a:hover {
            color: white;
            background: rgba(231, 76, 60, 0.15);
            padding-left: 28px;
        }

        .sidebar-menu a:hover:before {
            transform: scaleY(1);
        }

        .sidebar-menu a.active {
            color: white;
            background: linear-gradient(90deg, rgba(231, 76, 60, 0.25) 0%, transparent 100%);
            font-weight: 600;
        }

        .sidebar-menu a.active:before {
            transform: scaleY(1);
        }

        .sidebar-menu a.active span {
            text-shadow: 0 0 4px rgba(231, 76, 60, 0.5);
        }

        .sidebar-menu i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover i {
            transform: scale(1.15);
            color: #e74c3c;
        }

        .sidebar-menu a.active i {
            color: #e74c3c;
            text-shadow: 0 0 8px rgba(231, 76, 60, 0.6);
        }

        /* Submenu Styling */
        .sidebar-menu .collapse {
            background: rgba(0, 0, 0, 0.2);
            border-left: 3px solid rgba(231, 76, 60, 0.3);
            max-height: 400px;
            transition: all 0.3s ease;
        }

        .sidebar-menu .collapse ul {
            padding: 8px 0 !important;
            margin: 0 !important;
            list-style: none;
        }

        .sidebar-menu .collapse li {
            border-bottom: none;
        }

        .sidebar-menu .collapse a {
            padding: 11px 20px 11px 45px !important;
            font-size: 13px !important;
            color: rgba(255, 255, 255, 0.65) !important;
            font-weight: 400 !important;
        }

        .sidebar-menu .collapse a:hover {
            background: rgba(231, 76, 60, 0.2) !important;
            color: rgba(255, 255, 255, 0.95) !important;
            padding-left: 50px !important;
        }

        .sidebar-menu .collapse a.active {
            background: rgba(231, 76, 60, 0.25) !important;
            color: rgba(255, 255, 255, 0.95) !important;
            border-left: 2px solid #e74c3c;
            padding-left: 43px !important;
        }

        .sidebar-menu .collapse a:before {
            display: none !important;
        }

        .sidebar-menu [data-bs-toggle="collapse"] {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-menu [data-bs-toggle="collapse"] span {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .sidebar-menu [data-bs-toggle="collapse"] .fa-chevron-down {
            transition: transform 0.3s ease;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-left: auto;
        }

        .sidebar-menu [data-bs-toggle="collapse"][aria-expanded="true"] .fa-chevron-down {
            transform: rotate(-180deg);
            color: #e74c3c;
        }

        /* User Info Section */
        .sidebar-user {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .sidebar-user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
        }

        .sidebar-user-text {
            flex: 1;
            line-height: 1.3;
        }

        .sidebar-user-text small {
            display: block;
            opacity: 0.8;
            font-size: 11px;
        }

        .sidebar-user-text strong {
            display: block;
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Logout Button */
        .sidebar-menu li:last-child {
            border-top: 2px solid rgba(255, 255, 255, 0.1);
            margin-top: 10px;
        }

        .sidebar-menu li:last-child form {
            margin: 0;
        }

        .sidebar-menu li:last-child button {
            background: none !important;
            border: none !important;
            width: 100% !important;
            text-align: left !important;
            padding: 0 !important;
            cursor: pointer;
        }

        .sidebar-menu li:last-child a {
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            position: relative;
        }

        .sidebar-menu li:last-child a:before {
            background: #e74c3c;
        }

        .sidebar-menu li:last-child button:hover a {
            color: #ff6b6b;
            background: linear-gradient(90deg, rgba(255, 107, 107, 0.15) 0%, transparent 100%);
            padding-left: 28px;
        }

        .sidebar-menu li:last-child button:hover a:before {
            transform: scaleY(1);
        }

        .sidebar-menu li:last-child button:hover i {
            color: #ff6b6b;
            transform: scale(1.2);
        }

        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(231, 76, 60, 0.4);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(231, 76, 60, 0.6);
        }

        /* ===================== MAIN CONTENT ===================== */
        .main-content {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .navbar-top {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 0;
            border-bottom: 3px solid #e74c3c;
            backdrop-filter: blur(10px);
        }

        .navbar-top .container-fluid {
            padding: 18px 30px;
        }

        .navbar-top h5 {
            font-weight: 700;
            color: #2c3e50;
            font-size: 18px;
            letter-spacing: 0.3px;
            margin: 0;
        }

        .navbar-top .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: #333;
        }

        .user-avatar-top {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-avatar-top:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4);
        }

        .page-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .page-content::-webkit-scrollbar {
            width: 8px;
        }

        .page-content::-webkit-scrollbar-track {
            background: #f0f0f0;
        }

        .page-content::-webkit-scrollbar-thumb {
            background: #e74c3c;
            border-radius: 4px;
        }

        .page-content::-webkit-scrollbar-thumb:hover {
            background: #c0392b;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 14px;
            margin: 0;
        }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                padding: 0;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            }

            .sidebar.show {
                transform: translateX(0);
                width: 280px;
            }

            .main-content {
                margin-left: 0;
            }

            .page-content {
                padding: 20px 15px;
            }

            .navbar-top .container-fluid {
                padding: 15px 15px;
            }

            .navbar-top h5 {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <!-- Header -->
            <div class="sidebar-header">
                <h3><i class="fas fa-book"></i> PeminjamanBuku</h3>
                <p>Manajemen Sistem</p>
            </div>

            <!-- Navigation Menu -->
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('siswa.dashboard') ?? '#' }}"
                        {{ request()->routeIs('siswa.dashboard') ? 'class=active' : '' }}>
                        <span>
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('loans.index') ?? '#' }}"
                        {{ request()->routeIs('loans.*') ? 'class=active' : '' }}>
                        <span>
                            <i class="fas fa-exchange-alt"></i>
                            <span>Kelola Peminjaman</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#dataMenu">
                        <span>
                            <i class="fas fa-database"></i>
                            <span>Data Master</span>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="dataMenu">
                        <ul>
                            <li>
                                <a href="{{ route('books.index') ?? '#' }}"
                                    {{ request()->routeIs('books.*') ? 'class=active' : '' }}>
                                    <i class="fas fa-cogs"></i> <span>Data Buku</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('petugas.index') ?? '#' }}"
                                    {{ request()->routeIs('petugas.*') ? 'class=active' : '' }}>
                                    <i class="fas fa-users"></i> <span>Data Petugas</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#reportMenu">
                        <span>
                            <i class="fas fa-file-chart-line"></i>
                            <span>Laporan</span>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="reportMenu">
                        <ul>
                            <li>
                                <a href="{{ route('reports.borrowing') ?? '#' }}"
                                    {{ request()->routeIs('reports.borrowing') ? 'class=active' : '' }}>
                                    <i class="fas fa-list"></i> <span>Peminjaman</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.returns') ?? '#' }}"
                                    {{ request()->routeIs('reports.returns') ? 'class=active' : '' }}>
                                    <i class="fas fa-undo"></i> <span>Pengembalian</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li style="border-top: 2px solid rgba(255, 255, 255, 0.1); margin-top: auto;">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit"
                            style="background: none; border: none; width: 100%; text-align: left; padding: 0; cursor: pointer;">
                            <a
                                style="display: flex; align-items: center; padding: 15px 20px; color: rgba(255, 255, 255, 0.7); text-decoration: none; font-weight: 600; transition: all 0.3s ease; position: relative; font-size: 14px;">
                                <i class="fas fa-sign-out-alt"
                                    style="margin-right: 12px; width: 20px; text-align: center; font-size: 16px;"></i>
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
            <nav class="navbar-top">
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5><i class="fas fa-home"></i> @yield('title', 'Dashboard')</h5>
                        <div class="user-info">
                            <span>{{ auth()->user()->name ?? 'User' }}</span>
                            <div class="user-avatar-top">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <div class="page-content">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-exclamation-circle"></i> Error!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
