<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku - Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px 30px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .nav {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .nav .auth-links {
            display: flex;
            gap: 15px;
        }

        .nav a {
            color: #667eea;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .nav a:hover {
            background: #667eea;
            color: white;
        }

        .logout-btn {
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            text-decoration: underline;
            font-size: 14px;
            padding: 8px 16px;
        }

        .logout-btn:hover {
            color: #5a6fd8;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
        }

        .search-bar {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            text-align: center;
        }

        .search-bar input {
            width: 100%;
            max-width: 400px;
            padding: 12px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 16px;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .book-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .book-image {
            height: 200px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            overflow: hidden;
        }

        .book-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-info {
            padding: 20px;
        }

        .book-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
        }

        .book-author {
            color: #7f8c8d;
            margin-bottom: 8px;
            font-style: italic;
        }

        .book-details {
            color: #95a5a6;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .book-quantity {
            display: inline-block;
            background: #27ae60;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .borrow-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .borrow-btn:hover {
            background: linear-gradient(135deg, #5a6fd8, #6a4190);
        }

        .borrow-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .pagination {
            margin-top: 40px;
            text-align: center;
        }

        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 4px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #e9ecef;
            transition: 0.3s;
        }

        .pagination a:hover,
        .pagination .active {
            background: #667eea;
            color: white;
        }

        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .books-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }

            .nav {
                flex-direction: column;
                gap: 10px;
            }

            .nav .auth-links {
                justify-content: center;
            }
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>📚 Perpustakaan Digital</h1>
        <p>Temukan dan pinjam buku favorit Anda</p>
    </div>

    <div class="nav">
        <div class="logo">📖 Perpus</div>
        <div class="auth-links">
            @auth
                <a href="{{ route('siswa.dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Register</a>
            @endauth
        </div>
    </div>

    <div class="container">
        <div class="search-bar">
            <input type="text" placeholder="Cari buku berdasarkan judul atau penulis..." id="searchInput">
        </div>

        <div class="books-grid">
            @forelse($books as $book)
                <div class="book-card">
                    <div class="book-image">
                        @if ($book->image)
                            <img src="{{ asset($book->image) }}" alt="{{ $book->title }}">
                        @else
                            📖
                        @endif
                    </div>
                    <div class="book-info">
                        <div class="book-title">{{ $book->title }}</div>
                        <div class="book-author">Oleh: {{ $book->author }}</div>
                        <div class="book-details">
                            @if ($book->publisher)
                                Penerbit: {{ $book->publisher }}<br>
                            @endif
                            @if ($book->isbn)
                                ISBN: {{ $book->isbn }}
                            @endif
                        </div>
                        <span class="book-quantity">Tersedia: {{ $book->stock }}</span>
                        <button class="borrow-btn" onclick="borrowBook({{ $book->id }})"
                            {{ $book->stock > 0 ? '' : 'disabled' }}>
                            {{ $book->stock > 0 ? 'Pinjam Buku' : 'Stok Habis' }}
                        </button>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <h3>Tidak ada buku tersedia saat ini</h3>
                    <p>Silakan kembali lagi nanti atau hubungi admin perpustakaan.</p>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $books->links() }}
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Perpustakaan Digital. Semua hak dilindungi.</p>
    </div>

    <script>
        function borrowBook(bookId) {
            if (!confirm('Apakah Anda ingin meminjam buku ini?')) {
                return;
            }

            // Check if user is authenticated by checking if dashboard link exists
            var isAuthenticated = document.querySelector('a[href*="dashboard"]') !== null;

            if (!isAuthenticated) {
                // Redirect to login if not authenticated
                window.location.href = '{{ route('login') }}';
                return;
            }

            // Create and submit form for authenticated users
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('borrow', ':bookId') }}'.replace(':bookId', bookId);
            form.style.display = 'none';

            // Add CSRF token
            var csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            // Add to body and submit
            document.body.appendChild(form);
            form.submit();
        }

        // Simple search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const bookCards = document.querySelectorAll('.book-card');

            bookCards.forEach(card => {
                const title = card.querySelector('.book-title').textContent.toLowerCase();
                const author = card.querySelector('.book-author').textContent.toLowerCase();

                if (title.includes(searchTerm) || author.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>

</body>

</html>
