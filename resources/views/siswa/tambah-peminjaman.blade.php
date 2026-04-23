@extends('dashboard.siswa-layout')

@section('title')
    Tambah Peminjaman
@endsection

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-plus-circle"></i> Tambah Peminjaman</h1>
        Pilih buku yang ingin Anda pinjam dari perpustakaan kami
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i>
            <strong>Terjadi Kesalahan!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="borrowForm" method="POST" action="{{ route('siswa.pinjam-multiple') }}">
        @csrf
        <div class="row">
            <!-- Left Column - Equipment Selection -->
            <div class="col-lg-8">
                <!-- Search and Filter Bar -->
                <div class="card mb-4" style="border: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
                    <div class="card-body">
                        <h5 class="card-title mb-3"><i class="fas fa-search"></i> Cari Buku</h5>
                        <div class="input-group mb-3">
                            <input type="text" id="searchInput" class="form-control"
                                placeholder="Cari berdasarkan judul, penulis, kategori, atau penerbit..."
                                style="border-radius: 8px 0 0 8px; border: 2px solid #e9ecef;">
                            <button class="btn btn-light" type="button"
                                style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <select id="categoryFilter" class="form-select"
                                    style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 12px;">
                                    <option value="">Semua Kategori</option>
                                    @foreach (($categories ?? []) as $category)
                                        <option value="{{ strtolower($category) }}">{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-light mb-0"
                                    style="border: 1px solid #e9ecef; border-radius: 8px; padding: 10px 12px; font-size: 13px;">
                                    <i class="fas fa-tags"></i> Pilih kategori untuk menemukan buku yang sesuai
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipment List -->
                <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
                    <div class="card-body">
                        <i class="fas fa-book"></i> Daftar Buku Tersedia

                        @if ($equipment->count() > 0)
                            <div id="equipmentList">
                                @foreach ($equipment as $item)
                                    @php($categoryStyle = \App\Models\Book::categoryBadgeStyle($item->category))
                                    <div class="equipment-selection-item" data-equipment-id="{{ $item->id }}"
                                        data-title="{{ $item->title }}" data-author="{{ $item->author }}"
                                        data-publisher="{{ $item->publisher }}" data-category="{{ strtolower($item->category ?? 'lainnya') }}"
                                        data-book-id="{{ $item->id }}">
                                        <div class="d-flex align-items-start">
                                            <!-- Checkbox -->
                                            <div class="form-check mt-2">
                                                <input class="form-check-input equipment-checkbox" type="checkbox"
                                                    value="{{ $item->id }}" id="equipment_{{ $item->id }}"
                                                    data-input-name="books[{{ $item->id }}][id]"
                                                    data-quantity-name="books[{{ $item->id }}][quantity]"
                                                    data-book-title="{{ $item->title }}"
                                                    data-book-author="{{ $item->author }}"
                                                    data-book-publisher="{{ $item->publisher }}">
                                            </div>

                                            <!-- Book Info -->
                                            <label for="equipment_{{ $item->id }}"
                                                class="form-check-label flex-grow-1 ms-3" style="cursor: pointer;">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1" style="color: #333; font-weight: 600;">
                                                            {{ $item->title }}
                                                        </h6>
                                                        <p class="mb-2" style="color: #666; font-size: 14px;">
                                                            <i class="fas fa-user"></i> {{ $item->author }}
                                                        </p>
                                                        <p class="mb-2" style="color: #4f6fad; font-size: 13px;">
                                                            <i class="fas fa-tag"></i> Kategori:
                                                            <span class="badge"
                                                                style="background: {{ $categoryStyle['background'] }}; color: {{ $categoryStyle['color'] }};">{{ $item->category ?? 'Lainnya' }}</span>
                                                        </p>
                                                        @if ($item->publisher)
                                                            <p class="mb-2" style="color: #999; font-size: 13px;">
                                                                <i class="fas fa-building"></i> Penerbit:
                                                                {{ $item->publisher }}
                                                            </p>
                                                        @endif
                                                        @if ($item->year)
                                                            <p class="mb-0" style="color: #999; font-size: 13px;">
                                                                <i class="fas fa-calendar"></i> Tahun: {{ $item->year }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="text-end ms-3">
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle"></i> Stok: {{ $item->stock }}
                                                        </span>
                                                        <div class="mt-2 quantity-wrapper" data-quantity-wrapper="{{ $item->id }}"
                                                            style="display: none;">
                                                            <label class="form-label mb-1"
                                                                style="font-size: 12px; color: #64748b;">Jumlah</label>
                                                            <select class="form-select form-select-sm quantity-select"
                                                                data-book-id="{{ $item->id }}"
                                                                data-book-title="{{ $item->title }}">
                                                                @for ($qty = 1; $qty <= $item->stock; $qty++)
                                                                    <option value="{{ $qty }}">{{ $qty }} buku</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        <hr class="my-3">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 15px;"></i>
                                <h5 style="color: #666;">Tidak ada buku tersedia</h5>
                                <p style="color: #999;">Semua buku sedang dalam peminjaman. Silakan coba lagi nanti.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Summary and Duration -->
            <div class="col-lg-4">
                <div class="card mb-4"
                    style="border: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); position: sticky; top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-user-graduate"></i> Data Siswa
                        </h5>

                        <div class="mb-3">
                            <label for="borrower_name" class="form-label">Nama Siswa</label>
                            <input type="text" id="borrower_name" class="form-control"
                                value="{{ auth()->user()->name }}" readonly
                                style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 12px; background: #f8f9fa;">
                        </div>

                        <div class="mb-0">
                            <label for="borrower_class" class="form-label">Kelas Siswa</label>
                            <select class="form-select" id="borrower_class" name="borrower_class"
                                style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 12px;" required>
                                <option value="">-- Pilih Kelas --</option>
                                @foreach (($classOptions ?? []) as $classOption)
                                    <option value="{{ $classOption }}"
                                        {{ old('borrower_class', $borrower->class ?? '') == $classOption ? 'selected' : '' }}>
                                        {{ $classOption }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Pilih kelas Anda sebelum meminjam buku
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Duration Selection Card -->
                <div class="card mb-4"
                    style="border: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-calendar-days"></i> Durasi Peminjaman
                        </h5>

                        <div class="mb-3">
                            <label for="duration_days" class="form-label">Pilih Durasi (Hari)</label>
                            <select class="form-select" id="duration_days" name="duration_days"
                                style="border: 2px solid #e9ecef; border-radius: 8px; padding: 10px 12px;" required>
                                <option value="">-- Pilih Durasi --</option>
                                <option value="1">1 Hari</option>
                                <option value="3">3 Hari</option>
                                <option value="7" selected>7 Hari (Default)</option>
                                <option value="14">14 Hari</option>
                                <option value="21">21 Hari</option>
                                <option value="30">30 Hari</option>
                            </select>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle"></i> Durasi maksimal 30 hari
                            </small>
                        </div>

                        <div class="alert alert-info mb-0"
                            style="border: 1px solid #bee5eb; background-color: #e7f3ff; border-radius: 8px;">
                            <small>
                                <i class="fas fa-calendar-alt"></i>
                                <strong>Jadwal Pengembalian:</strong><br>
                                <span id="returnDate" style="color: #004085; font-weight: 600;">-- Pilih durasi --</span>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Summary Card -->
                <div class="card" style="border: none; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
                    <div class="card-body">
                        <h5 class="card-title mb-3">
                            <i class="fas fa-clipboard-list"></i> Ringkasan Peminjaman
                        </h5>

                        <!-- Selected Books Count -->
                        <div class="summary-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-book"></i> Judul Dipilih</span>
                                <span class="badge bg-primary" id="selectedCount">0</span>
                            </div>
                        </div>

                        <div class="summary-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-layer-group"></i> Total Eksemplar</span>
                                <span class="badge bg-info" id="selectedQuantity">0</span>
                            </div>
                        </div>

                        <hr>

                        <!-- Selected Equipment List -->
                        <div id="selectedEquipmentList" class="mb-3" style="max-height: 300px; overflow-y: auto;">
                            <p class="text-muted mb-0" style="font-size: 13px;">
                                <i class="fas fa-info-circle"></i> Belum ada buku yang dipilih
                            </p>
                        </div>

                        <hr>

                        <!-- Borrower Info -->
                        <div class="summary-item mb-3">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> <strong>Peminjam:</strong><br>
                                {{ auth()->user()->name }}<br>
                                <i class="fas fa-school"></i> <strong>Kelas:</strong>
                                <span id="selectedClassLabel">{{ old('borrower_class', $borrower->class ?? '-') ?: '-' }}</span>
                            </small>
                        </div>

                        <!-- Active Loans Info -->
                        @if ($activeLoans > 0)
                            <div class="alert alert-warning mb-3"
                                style="border-radius: 8px; padding: 10px 12px; font-size: 13px;">
                                <i class="fas fa-exclamation-triangle"></i>
                                Anda memiliki <strong>{{ $activeLoans }} peminjaman aktif</strong>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 mb-2" id="submitBtn"
                            style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border: none; border-radius: 8px; padding: 12px; font-weight: 600; cursor: not-allowed; opacity: 0.5;"
                            disabled>
                            <i class="fas fa-check-circle"></i> Pinjam Sekarang
                        </button>

                        <button type="reset" class="btn btn-outline-secondary w-100"
                            style="border-radius: 8px; padding: 12px;">
                            <i class="fas fa-redo"></i> Bersihkan Pilihan
                        </button>

                        <!-- Info -->
                        <div class="alert alert-info mt-3"
                            style="border: 1px solid #bee5eb; background-color: #e7f3ff; border-radius: 8px; font-size: 12px;">
                            <i class="fas fa-lightbulb"></i> Pastikan Anda memilih buku sebelum melanjutkan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .page-header {
            background: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
        }

        .page-header h1 {
            color: #333;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #666;
            margin: 0;
            font-size: 15px;
        }

        .equipment-selection-item {
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .equipment-selection-item:hover {
            background-color: #f8f9ff;
            border-radius: 8px;
            padding: 15px;
            padding-left: 10px;
            margin: 0 -15px;
            padding-right: 10px;
        }

        .equipment-checkbox:checked {
            background-color: #00f2fe;
            border-color: #00f2fe;
        }

        .equipment-checkbox:checked:after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='white' d='M6.564.75l-3.59 3.612-1.538-1.55L0 4.26l2.974 2.99L8 2.193z'/%3e%3c/svg%3e");
        }

        .form-check-input {
            width: 1.5em;
            height: 1.5em;
            border: 2px solid #ccc;
            border-radius: 0.35em;
            cursor: pointer;
        }

        .form-check-input:focus {
            border-color: #00f2fe;
            box-shadow: 0 0 0 0.25rem rgba(0, 242, 254, 0.25);
        }

        .summary-item {
            font-size: 14px;
            color: #333;
        }

        .summary-item strong {
            color: #333;
        }

        #selectedEquipmentList small {
            display: block;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        #selectedEquipmentList small:last-child {
            border-bottom: none;
        }

        .card {
            border-radius: 12px !important;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }

        @media (max-width: 992px) {
            .card {
                margin-bottom: 20px;
                position: static !important;
            }
        }

        .alert {
            border-radius: 8px;
        }

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:not(:disabled):hover {
            background: linear-gradient(135deg, #3a9dfd 0%, #00d9e9 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 242, 254, 0.3);
        }

        #searchInput {
            transition: all 0.3s ease;
        }

        #searchInput:focus {
            border-color: #00f2fe !important;
            box-shadow: 0 0 0 3px rgba(0, 242, 254, 0.1) !important;
        }

        .form-select {
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: #00f2fe !important;
            box-shadow: 0 0 0 3px rgba(0, 242, 254, 0.1) !important;
        }
    </style>

    <script>
        // Update handle untuk checkbox
        document.querySelectorAll('.equipment-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                syncBookInputs(this);
                updateSummary();
            });
        });

        document.querySelectorAll('.quantity-select').forEach(select => {
            select.addEventListener('change', function() {
                syncQuantityInput(this.dataset.bookId, this.value);
                updateSummary();
            });
        });

        // Update handle untuk search
        document.getElementById('searchInput').addEventListener('keyup', function() {
            filterBooks();
        });

        document.getElementById('categoryFilter').addEventListener('change', function() {
            filterBooks();
        });

        // Update handle untuk duration
        document.getElementById('duration_days').addEventListener('change', function() {
            updateReturnDate();
        });

        document.getElementById('borrower_class').addEventListener('change', function() {
            const classLabel = document.getElementById('selectedClassLabel');
            classLabel.textContent = this.value || '-';
            updateSubmitButtonState();
        });

        // Update durasi kembali pada load
        window.addEventListener('load', function() {
            updateReturnDate();
            document.querySelectorAll('.equipment-checkbox').forEach((checkbox) => syncBookInputs(checkbox));
            updateSummary();
        });

        function syncBookInputs(checkbox) {
            const itemContainer = checkbox.closest('.equipment-selection-item');
            const hiddenIdName = checkbox.dataset.inputName;
            const hiddenQtyName = checkbox.dataset.quantityName;
            const quantityWrapper = itemContainer.querySelector('.quantity-wrapper');
            const quantitySelect = itemContainer.querySelector('.quantity-select');

            itemContainer.querySelectorAll('.generated-book-input').forEach((input) => input.remove());

            if (checkbox.checked) {
                quantityWrapper.style.display = 'block';

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = hiddenIdName;
                idInput.value = checkbox.value;
                idInput.className = 'generated-book-input';

                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = hiddenQtyName;
                quantityInput.value = quantitySelect.value;
                quantityInput.className = 'generated-book-input generated-book-quantity';

                itemContainer.appendChild(idInput);
                itemContainer.appendChild(quantityInput);
            } else {
                quantityWrapper.style.display = 'none';
                quantitySelect.value = '1';
            }
        }

        function syncQuantityInput(bookId, quantity) {
            const itemContainer = document.querySelector(`.equipment-selection-item[data-book-id="${bookId}"]`);
            const quantityInput = itemContainer.querySelector('.generated-book-quantity');

            if (quantityInput) {
                quantityInput.value = quantity;
            }
        }

        function filterBooks() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const selectedCategory = document.getElementById('categoryFilter').value;
            const items = document.querySelectorAll('.equipment-selection-item');

            items.forEach(item => {
                const title = item.getAttribute('data-title').toLowerCase();
                const author = item.getAttribute('data-author').toLowerCase();
                const publisher = item.getAttribute('data-publisher').toLowerCase();
                const category = item.getAttribute('data-category').toLowerCase();

                const matches = title.includes(searchTerm) ||
                    author.includes(searchTerm) ||
                    publisher.includes(searchTerm) ||
                    category.includes(searchTerm);

                const matchesCategory = selectedCategory === '' || category === selectedCategory;

                item.style.display = matches && matchesCategory ? '' : 'none';
            });
        }

        function updateReturnDate() {
            const durationSelect = document.getElementById('duration_days');
            const durationDays = parseInt(durationSelect.value);
            const returnDateSpan = document.getElementById('returnDate');

            if (durationDays && !isNaN(durationDays)) {
                const today = new Date();
                const returnDate = new Date(today.getTime() + durationDays * 24 * 60 * 60 * 1000);
                const formattedDate = returnDate.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                returnDateSpan.textContent = formattedDate;
            } else {
                returnDateSpan.textContent = '-- Pilih durasi --';
            }

            updateSubmitButtonState();
        }

        function updateSummary() {
            const selectedCheckboxes = document.querySelectorAll('.equipment-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;
            const selectedEquipmentList = document.getElementById('selectedEquipmentList');
            const selectedCountBadge = document.getElementById('selectedCount');
            const selectedQuantityBadge = document.getElementById('selectedQuantity');
            let totalQuantity = 0;

            // Update count
            selectedCountBadge.textContent = selectedCount;

            // Update list
            if (selectedCount === 0) {
                selectedEquipmentList.innerHTML = `
                    <p class="text-muted mb-0" style="font-size: 13px;">
                        <i class="fas fa-info-circle"></i> Belum ada buku yang dipilih
                    </p>
                `;
            } else {
                let html = '';
                selectedCheckboxes.forEach(checkbox => {
                    const bookTitle = checkbox.getAttribute('data-book-title');
                    const bookAuthor = checkbox.getAttribute('data-book-author');
                    const quantitySelect = checkbox.closest('.equipment-selection-item').querySelector('.quantity-select');
                    const quantity = parseInt(quantitySelect.value, 10) || 1;
                    totalQuantity += quantity;

                    html += `
                        <small style="display: block; padding: 8px 0; border-bottom: 1px solid #f0f0f0;">
                            <strong>${bookTitle}</strong><br>
                            <span style="color: #999;">${bookAuthor}</span><br>
                            <span style="color: #2563eb;">Jumlah: ${quantity} buku</span>
                        </small>
                    `;
                });
                selectedEquipmentList.innerHTML = html;
            }

            selectedQuantityBadge.textContent = totalQuantity;

            updateSubmitButtonState();
        }

        function updateSubmitButtonState() {
            const selectedCheckboxes = document.querySelectorAll('.equipment-checkbox:checked');
            const durationDays = document.getElementById('duration_days').value;
            const borrowerClass = document.getElementById('borrower_class').value;
            const submitBtn = document.getElementById('submitBtn');

            const isValid = selectedCheckboxes.length > 0 && durationDays !== '' && borrowerClass !== '';

            if (isValid) {
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor = 'pointer';
                submitBtn.style.background = 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
            } else {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.style.background = 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)';
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const bookItems = document.querySelectorAll('.book-selection-item');

            bookItems.forEach(item => {
                const title = item.getAttribute('data-title').toLowerCase();
                const author = item.getAttribute('data-author').toLowerCase();
                const isbn = item.getAttribute('data-isbn').toLowerCase();

                if (title.includes(searchTerm) || author.includes(searchTerm) || isbn.includes(
                        searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Form submit confirmation
        document.getElementById('borrowForm').addEventListener('submit', function(e) {
            const selectedCheckboxes = document.querySelectorAll('.equipment-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;
            let totalQuantity = 0;
            const durationDays = document.getElementById('duration_days').value;
            const submitBtn = document.getElementById('submitBtn');

            selectedCheckboxes.forEach((checkbox) => {
                const quantitySelect = checkbox.closest('.equipment-selection-item').querySelector('.quantity-select');
                totalQuantity += parseInt(quantitySelect.value, 10) || 1;
            });

            const today = new Date();
            const returnDate = new Date(today.getTime() + parseInt(durationDays) * 24 * 60 * 60 * 1000);
            const formattedDate = returnDate.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const confirmed = confirm(
                `Anda akan meminjam ${selectedCount} judul dengan total ${totalQuantity} buku selama ${durationDays} hari.\n\n` +
                `Tanggal kembali: ${formattedDate}\n\n` +
                `Lanjutkan?`
            );

            if (!confirmed) {
                e.preventDefault();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses Peminjaman...';
        });
    </script>
@endsection
