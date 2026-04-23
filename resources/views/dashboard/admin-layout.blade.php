<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Dashboard</title>
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
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 0;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar-header {
            padding: 25px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-bottom: 3px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
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
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar-menu a:hover {
            color: white;
            background: rgba(102, 126, 234, 0.15);
            padding-left: 28px;
        }

        .sidebar-menu a:hover:before {
            transform: scaleY(1);
        }

        .sidebar-menu a.active {
            color: white;
            background: linear-gradient(90deg, rgba(102, 126, 234, 0.25) 0%, transparent 100%);
            font-weight: 600;
        }

        .sidebar-menu a.active:before {
            transform: scaleY(1);
        }

        .sidebar-menu a.active span {
            text-shadow: 0 0 4px rgba(102, 126, 234, 0.5);
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
            color: #667eea;
        }

        .sidebar-menu a.active i {
            color: #667eea;
            text-shadow: 0 0 8px rgba(102, 126, 234, 0.6);
        }

        /* Submenu Styling */
        .sidebar-menu .collapse {
            background: rgba(0, 0, 0, 0.2);
            border-left: 3px solid rgba(102, 126, 234, 0.3);
            max-height: 400px;
            transition: all 0.3s ease;
        }

        .sidebar-menu .collapse ul {
            padding: 8px 0 !important;
            margin: 0 !important;
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
            background: rgba(102, 126, 234, 0.2) !important;
            color: rgba(255, 255, 255, 0.95) !important;
            padding-left: 50px !important;
        }

        .sidebar-menu .collapse a.active {
            background: rgba(102, 126, 234, 0.25) !important;
            color: rgba(255, 255, 255, 0.95) !important;
            border-left: 2px solid #667eea;
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

        .sidebar-menu [data-bs-toggle="collapse"] .fas.fa-chevron-down {
            transition: transform 0.3s ease;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            margin-left: auto;
        }

        .sidebar-menu [data-bs-toggle="collapse"][aria-expanded="true"] .fas.fa-chevron-down {
            transform: rotate(-180deg);
            color: #667eea;
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
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: #ff6b6b;
            transform: scaleY(0);
            transition: transform 0.3s ease;
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

        .navbar-admin {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 0;
            border-bottom: 3px solid #667eea;
            backdrop-filter: blur(10px);
        }

        .navbar-admin .container-fluid {
            padding: 18px 30px;
        }

        .navbar-admin h5 {
            font-weight: 700;
            color: #2c3e50;
            font-size: 18px;
            letter-spacing: 0.3px;
        }

        .navbar-admin .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: #333;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-avatar:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
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
            color: #667eea;
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

        .page-content::-webkit-scrollbar {
            width: 8px;
        }

        .page-content::-webkit-scrollbar-track {
            background: #f0f0f0;
        }

        .page-content::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        .page-content::-webkit-scrollbar-thumb:hover {
            background: #764ba2;
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
            border-left: 4px solid #667eea;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .stat-card.primary {
            border-left-color: #667eea;
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
            border-bottom: 2px solid #667eea;
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
            color: #667eea;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
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

            .navbar-admin .container-fluid {
                padding: 15px 15px;
            }

            .navbar-admin h5 {
                font-size: 16px;
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

        /* Custom Scrollbar untuk Sidebar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.4);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.6);
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
                <h3 style="animation: glow 2s ease-in-out infinite alternate;"><i class="fas fa-tools"
                        style="color: #ffd700;"></i> Perpustakaan</h3>
                <style>
                    @keyframes glow {
                        0% {
                            text-shadow: 0 0 5px #667eea;
                        }

                        100% {
                            text-shadow: 0 0 20px #667eea, 0 0 30px #764ba2;
                        }
                    }
                </style>
                <p style="font-size: 11px; opacity: 0.8;">Sistem Perpustakaan Buku Sekolah</p>
            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>📊 Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#user-menu" aria-expanded="false">
                        <span>
                            <i class="fas fa-users"></i>
                            <span>👥 User Management</span>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="user-menu">
                        <ul>
                            <li>
                                <a href="{{ route('loans.index') }}"
                                    class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>
                                    <span>Daftar Siswa</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('petugas.index') }}"
                                    class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}">
                                    <i class="fas fa-user-tie"></i>
                                    <span>Daftar Petugas</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('petugas.create') }}"
                                    class="{{ request()->routeIs('petugas.create') ? 'active' : '' }}">
                                    <i class="fas fa-user-plus"></i>
                                    <span>Tambah Petugas</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#equipment-menu" aria-expanded="false"
                        aria-controls="equipment-menu" class="{{ request()->routeIs('books.*') ? 'active' : '' }}">
                        <span>
                            <i class="fas fa-cogs"></i>
                            <span>🔧 Perpustakaan</span>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="equipment-menu">
                        <ul>
                            <li>
                                <a href="{{ route('books.index') }}"
                                    class="{{ request()->routeIs('books.index') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>
                                    <span>Daftar Buku</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('books.create') }}"
                                    class="{{ request()->routeIs('books.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Tambah buku</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#loans-menu" aria-expanded="false"
                        aria-controls="loans-menu" class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">
                        <span>
                            <i class="fas fa-handshake"></i>
                            <span>📋 Peminjaman</span>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="loans-menu">
                        <ul>
                            <li>
                                <a href="{{ route('loans.index') }}"
                                    class="{{ request()->routeIs('loans.index') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>
                                    <span>Daftar Peminjaman</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loans.create') }}"
                                    class="{{ request()->routeIs('loans.create') ? 'active' : '' }}">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>Tambah Peminjaman</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#reports-menu" aria-expanded="false"
                        aria-controls="reports-menu" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <span>
                            <i class="fas fa-chart-bar"></i>
                            <span>📈 Laporan</span>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="reports-menu">
                        <ul>
                            <li>
                                <a href="{{ route('reports.statistics') }}"
                                    class="{{ request()->routeIs('reports.statistics') ? 'active' : '' }}">
                                    <i class="fas fa-chart-pie"></i>
                                    <span>Statistik</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.borrowing') }}"
                                    class="{{ request()->routeIs('reports.borrowing') ? 'active' : '' }}">
                                    <i class="fas fa-file-alt"></i>
                                    <span>Peminjaman</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.overdue') }}"
                                    class="{{ request()->routeIs('reports.overdue') ? 'active' : '' }}">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Terlambat</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.returns') }}"
                                    class="{{ request()->routeIs('reports.returns') ? 'active' : '' }}">
                                    <i class="fas fa-undo"></i>
                                    <span>Pengembalian</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.popular-equipment') }}"
                                    class="{{ request()->routeIs('reports.popular-equipment') ? 'active' : '' }}">
                                    <i class="fas fa-star"></i>
                                    <span>Buku Populer</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.borrowers') }}"
                                    class="{{ request()->routeIs('reports.borrowers') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>
                                    <span>Peminjam</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- User Profile Section -->
                <li class="sidebar-user" style="border-top: 1px solid rgba(255, 255, 255, 0.1); padding: 0;">
                    <div class="sidebar-user-info"
                        style="padding: 15px 20px; display: flex; align-items: center; gap: 12px;">
                        <div class="sidebar-user-avatar"
                            style="width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 16px;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="sidebar-user-text" style="flex: 1; line-height: 1.3;">
                            <strong
                                style="display: block; font-size: 14px; color: white;">{{ auth()->user()->name }}</strong>
                            <small
                                style="color: rgba(255, 255, 255, 0.7); font-size: 12px;">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                </li>

                <!-- Settings Section -->
                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#settings-menu"
                        aria-expanded="false">
                        <span>
                            <i class="fas fa-cog"></i>
                            <span>⚙️ Settings</span>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="settings-menu">
                        <ul>
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-sliders-h"></i>
                                    <span>System Settings</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-download"></i>
                                    <span>Backup Data</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.dashboard') }}"
                                    class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                    <i class="fas fa-broom"></i>
                                    <span>Clear Cache</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('loans.index') }}"
                                    class="{{ request()->routeIs('loans.*') ? 'active' : '' }}">
                                    <i class="fas fa-bell"></i>
                                    <span>Manage Notifications</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li style="border-top: 2px solid rgba(255, 255, 255, 0.1); margin-top: auto; padding-top: 15px;">
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit"
                            style="background: none; border: none; width: 100%; text-align: left; padding: 0; cursor: pointer;">
                            <a
                                style="display: flex; align-items: center; padding: 15px 20px; color: rgba(255, 255, 255, 0.8); text-decoration: none; transition: all 0.3s ease; font-weight: 500; font-size: 14.5px;">
                                <i class="fas fa-sign-out-alt"
                                    style="margin-right: 12px; width: 20px; text-align: center; font-size: 16px; color: #ff6b6b;"></i>
                                <span style="color: rgba(255, 255, 255, 0.8);">🚪 Logout</span>
                            </a>
                        </button>
                    </form>
                </li>

            </ul>

        </aside>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar-admin">
                <div class="container-fluid">
                    <button class="btn btn-link d-md-none p-1 sidebar-toggle" type="button"
                        style="color: #333; font-size: 1.4rem; line-height: 1;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="d-flex justify-content-between align-items-center w-100 flex-grow-1">
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
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div id="welcome-banner-admin" class="welcome-banner"
                        style="animation: slideInDown 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);">
                        <div
                            style="display: flex; align-items: center; gap: 15px; padding: 20px 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3); border-left: 5px solid rgba(255,255,255,0.3);">
                            <div
                                style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div style="flex: 1;">
                                <h5 style="margin: 0 0 5px 0; font-weight: 700; font-size: 18px;">Selamat Datang Admin!
                                </h5>
                                <p style="margin: 0; opacity: 0.95; font-size: 14px;">{{ session('success') }}</p>
                            </div>
                            <button onclick="dismissWelcomeAdmin()"
                                style="background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 35px; height: 35px; color: white; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.3s;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <script>
                        function dismissWelcomeAdmin() {
                            document.getElementById('welcome-banner-admin').style.animation = 'slideOutUp 0.4s forwards';
                            setTimeout(() => {
                                document.getElementById('welcome-banner-admin').remove();
                            }, 400);
                        }
                        setTimeout(dismissWelcomeAdmin, 6000);
                    </script>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <style>
        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideOutUp {
            from {
                transform: translateY(0);
                opacity: 1;
            }

            to {
                transform: translateY(-100%);
                opacity: 0;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        // Sidebar Toggle
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
    </script>

</body>

</html>
