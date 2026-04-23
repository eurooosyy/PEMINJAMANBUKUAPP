<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Aplikasi - Peminjaman Buku</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .setup-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 700px;
            width: 100%;
        }

        .setup-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .setup-header h1 {
            color: #667eea;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .setup-header p {
            color: #666;
            font-size: 16px;
        }

        .step-container {
            margin-bottom: 30px;
        }

        .step-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            flex-shrink: 0;
        }

        .step-header h3 {
            margin: 0;
            color: #333;
            font-size: 18px;
            font-weight: 600;
        }

        .step-content {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-left: 55px;
        }

        .step-content p {
            color: #666;
            margin: 0 0 15px 0;
            font-size: 14px;
            line-height: 1.6;
        }

        .btn-setup {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }

        .btn-setup:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-setup.secondary {
            background: #6c757d;
            color: white;
        }

        .btn-setup.secondary:hover {
            background: #5a6268;
            color: white;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-badge.success {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.warning {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.danger {
            background: #f8d7da;
            color: #721c24;
        }

        .credentials-box {
            background: #e8f4f8;
            border-left: 4px solid #17a2b8;
            border-radius: 8px;
            padding: 15px;
            margin: 10px 0;
            font-family: 'Monaco', 'Courier New', monospace;
        }

        .credentials-box .label {
            color: #666;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .credentials-box .value {
            color: #333;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .credentials-box .value:last-child {
            margin-bottom: 0;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            font-size: 13px;
            color: #1565c0;
            line-height: 1.6;
        }

        .info-box i {
            margin-right: 8px;
        }

        .divider {
            height: 1px;
            background: #ddd;
            margin: 30px 0;
        }

        @media (max-width: 600px) {
            .setup-container {
                padding: 25px;
            }

            .setup-header h1 {
                font-size: 24px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn-setup {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="setup-container">
        <div class="setup-header">
            <h1><i class="fas fa-book"></i> Setup Aplikasi</h1>
            <p>Peminjaman Buku - Perpustakaan Digital</p>
        </div>

        <!-- Step 1: Setup Seeder -->
        <div class="step-container">
            <div class="step-header">
                <div class="step-number">1</div>
                <h3>Jalankan Database Seeder</h3>
            </div>
            <div class="step-content">
                <p>Klik tombol di bawah untuk menjalankan seeder dan membuat data user (Admin, Petugas, Siswa) beserta
                    data buku contoh.</p>
                <div class="btn-group">
                    <button type="button" class="btn-setup" onclick="runSeeder()">
                        <i class="fas fa-database"></i> Jalankan Seeder
                    </button>
                </div>
                <div id="seeder-status"></div>
            </div>
        </div>

        <!-- Step 2: Cek Database -->
        <div class="step-container">
            <div class="step-header">
                <div class="step-number">2</div>
                <h3>Verifikasi Data di Database</h3>
            </div>
            <div class="step-content">
                <p>Klik tombol di bawah untuk melihat apakah user petugas sudah terbuat dengan role yang benar.</p>
                <div class="btn-group">
                    <button type="button" class="btn-setup" onclick="checkDatabase()">
                        <i class="fas fa-search"></i> Cek Database
                    </button>
                </div>
                <div id="database-status"></div>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Credentials -->
        <div class="step-container">
            <div class="step-header">
                <div class="step-number">3</div>
                <h3>Login dengan Akun Berikut</h3>
            </div>
            <div class="step-content">
                <p><strong>Akun Admin:</strong></p>
                <div class="credentials-box">
                    <div class="label">Email</div>
                    <div class="value">admin@mail.com</div>
                    <div class="label">Password</div>
                    <div class="value">12345678</div>
                </div>

                <p style="margin-top: 20px;"><strong>Akun Petugas:</strong></p>
                <div class="credentials-box">
                    <div class="label">Email</div>
                    <div class="value">petugas@mail.com</div>
                    <div class="label">Password</div>
                    <div class="value">12345678</div>
                </div>

                <p style="margin-top: 20px;"><strong>Akun Siswa:</strong></p>
                <div class="credentials-box">
                    <div class="label">Email</div>
                    <div class="value">siswa@mail.com</div>
                    <div class="label">Password</div>
                    <div class="value">12345678</div>
                </div>

                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> Setelah login berhasil, sistem akan otomatis mengarahkan Anda ke dashboard
                    sesuai role Anda.
                </div>

                <div class="btn-group" style="margin-top: 20px;">
                    <a href="/login" class="btn-setup">
                        <i class="fas fa-sign-in-alt"></i> Pergi ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function runSeeder() {
            const button = event.target.closest('.btn-setup');
            const statusDiv = document.getElementById('seeder-status');

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sedang memproses...';

            fetch('/setup-seeder')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        statusDiv.innerHTML = `
                            <div class="status-badge success">
                                <i class="fas fa-check-circle"></i> Seeder berhasil dijalankan!
                            </div>
                        `;
                        button.innerHTML = '<i class="fas fa-check"></i> Seeker Sudah Dijalankan';
                    } else {
                        statusDiv.innerHTML = `
                            <div class="status-badge danger">
                                <i class="fas fa-exclamation-circle"></i> Error: ${data.error}
                            </div>
                        `;
                        button.innerHTML = '<i class="fas fa-database"></i> Jalankan Seeder';
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    statusDiv.innerHTML = `
                        <div class="status-badge danger">
                            <i class="fas fa-exclamation-circle"></i> Error: ${error.message}
                        </div>
                    `;
                    button.innerHTML = '<i class="fas fa-database"></i> Jalankan Seeder';
                    button.disabled = false;
                });
        }

        function checkDatabase() {
            const button = event.target.closest('.btn-setup');
            const statusDiv = document.getElementById('database-status');

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sedang memeriksa...';

            fetch('/debug/petugas-user')
                .then(response => response.json())
                .then(data => {
                    const petugas = data.petugas_user;

                    if (petugas === 'PETUGAS USER NOT FOUND') {
                        statusDiv.innerHTML = `
                            <div class="status-badge danger">
                                <i class="fas fa-exclamation-circle"></i> User Petugas belum ada di database. Jalankan seeder terlebih dahulu!
                            </div>
                        `;
                    } else if (petugas.role_name === 'Petugas') {
                        statusDiv.innerHTML = `
                            <div class="status-badge success">
                                <i class="fas fa-check-circle"></i> User Petugas sudah terbuat dengan role yang benar!
                            </div>
                            <div style="background: #f0f0f0; border-radius: 8px; padding: 12px; margin-top: 10px; font-size: 12px;">
                                <strong>Detail:</strong><br>
                                ID: ${petugas.id}<br>
                                Nama: ${petugas.name}<br>
                                Email: ${petugas.email}<br>
                                Role: <span style="color: #28a745; font-weight: 600;">${petugas.role_name}</span>
                            </div>
                        `;
                    } else {
                        statusDiv.innerHTML = `
                            <div class="status-badge warning">
                                <i class="fas fa-exclamation-triangle"></i> User Petugas ada tapi role tidak benar. Role saat ini: ${petugas.role_name}
                            </div>
                        `;
                    }

                    button.innerHTML = '<i class="fas fa-search"></i> Cek Database';
                    button.disabled = false;
                })
                .catch(error => {
                    statusDiv.innerHTML = `
                        <div class="status-badge danger">
                            <i class="fas fa-exclamation-circle"></i> Error: ${error.message}
                        </div>
                    `;
                    button.innerHTML = '<i class="fas fa-search"></i> Cek Database';
                    button.disabled = false;
                });
        }
    </script>
</body>

</html>
