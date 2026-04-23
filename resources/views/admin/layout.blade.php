<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background-color: #212529;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar h4 {
            color: white;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .nav-link {
            color: white;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border-radius: 4px;
            margin-bottom: 0.25rem;
        }

        .nav-link:hover {
            background-color: #495057;
            color: white;
            text-decoration: none;
        }

        .nav-link.active {
            background-color: #495057;
            color: #ffc107;
            border-left: 4px solid #ffc107;
        }

        .collapse-toggle {
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .chevron-icon {
            transition: transform 0.3s ease;
            font-size: 0.875rem;
            display: inline-block;
        }

        .nav-link[aria-expanded="true"] .chevron-icon {
            transform: rotate(180deg);
        }

        .collapse .nav-link {
            padding-left: 3rem;
            font-size: 14px;
        }

        .collapse .nav-link:hover {
            background-color: #6c757d;
        }

        .nav-item .collapse {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
            background-color: #343a40;
            border-radius: 4px;
            margin-top: 0.25rem;
        }

        .content-wrapper {
            flex: 1;
            padding: 2rem;
            background-color: #f8f9fa;
        }

        .header-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header-admin h3 {
            margin: 0;
            color: #212529;
        }

        .header-admin span {
            color: #495057;
        }
    </style>
</head>

<body>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <nav class="sidebar p-3">
            <h4 class="text-white">⚙️ ADMIN </h4>
            <hr class="text-white">

            <ul class="nav flex-column">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <span><i class="fas fa-home"></i> Dashboard</span>
                    </a>
                </li>

                <!-- Kelola Peralatan -->
                <li class="nav-item">
                    <a href="{{ route('books.index') }}" class="nav-link">
                        <span><i class="fas fa-book"></i> Kelola Buku</span>
                    </a>
                </li>

                <!-- Tambah Buku -->
                <li class="nav-item">
                    <a href="{{ route('books.create') }}" class="nav-link">
                        <span><i class="fas fa-plus-circle"></i> Tambah Buku</span>
                    </a>
                </li>

                <!-- Kelola Petugas -->
                <li class="nav-item">
                    <a href="{{ route('petugas.index') }}" class="nav-link">
                        <span><i class="fas fa-users"></i> Kelola Petugas</span>
                    </a>
                </li>

                <!-- Kelola User -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <span><i class="fas fa-user"></i> Kelola User</span>
                    </a>
                </li>

                <!-- Peminjaman Dropdown -->
                <li class="nav-item">
                    <a class="nav-link collapse-toggle" data-bs-toggle="collapse" data-bs-target="#loansMenu"
                        role="button" aria-expanded="false" aria-controls="loansMenu">
                        <span><i class="fas fa-handshake"></i> Peminjaman</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </a>
                    <div class="collapse" id="loansMenu">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href='{{ route('loans.index') }}' class="nav-link"><i class="fas fa-list"></i> Kelola
                                    Peminjaman</a>
                            </li>
                            <li class="nav-item">
                                <a href='{{ route('admin.extensions') }}' class="nav-link"><i class="fas fa-redo"></i>
                                    Konfirmasi Perpanjangan</a>
                            </li>
                            <li class="nav-item">
                                <a href='{{ route('reports.overdue') }}' class="nav-link"><i
                                        class="fas fa-money-bill"></i> Denda & Keterlambatan</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Laporan Dropdown -->
                <li class="nav-item">
                    <a class="nav-link collapse-toggle" data-bs-toggle="collapse" data-bs-target="#reportMenu"
                        role="button" aria-expanded="false" aria-controls="reportMenu">
                        <span><i class="fas fa-chart-bar"></i> Laporan</span>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </a>
                    <div class="collapse" id="reportMenu">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="{{ route('reports.statistics') }}" class="nav-link"><i
                                        class="fas fa-chart-line"></i> Statistik</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.borrowing') }}" class="nav-link"><i
                                        class="fas fa-clipboard-list"></i> Peminjaman</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.overdue') }}" class="nav-link"><i
                                        class="fas fa-hourglass-end"></i> Keterlambatan</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.returns') }}" class="nav-link"><i
                                        class="fas fa-check-circle"></i> Pengembalian</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.popular-equipment') }}" class="nav-link"><i>
                                        class="fas fa-star"></i> Buku Populer</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.borrowers') }}" class="nav-link"><i
                                        class="fas fa-people-carry"></i> Peminjam</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Logout -->
                <li class="nav-item mt-4">
                    <a href="{{ route('logout') }}" class="nav-link text-warning">
                        <span><i class="fas fa-sign-out-alt"></i> Logout</span>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- KONTEN -->
        <div class="content-wrapper">
            <div class="header-admin">
                <h3>📊 Dashboard Admin</h3>
                <span>Halo, <b>{{ auth()->user()->name }}</b></span>
            </div>

            @yield('content')
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize collapse elements
            const collapseElements = document.querySelectorAll('[data-bs-toggle="collapse"]');

            collapseElements.forEach(element => {
                // Update aria-expanded when collapse changes
                const targetSelector = element.getAttribute('data-bs-target');
                if (targetSelector) {
                    const targetElement = document.querySelector(targetSelector);
                    if (targetElement) {
                        targetElement.addEventListener('show.bs.collapse', function() {
                            element.setAttribute('aria-expanded', 'true');
                        });
                        targetElement.addEventListener('hide.bs.collapse', function() {
                            element.setAttribute('aria-expanded', 'false');
                        });
                    }
                }
            });
        });
    </script>

</body>

</html>
