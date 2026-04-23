@extends('admin.layout')

@section('title', 'Konfirmasi Perpanjangan Peminjaman')

@section('content')
    <div class="page-header">
        <h1><i class="fas fa-sync-alt"></i> Konfirmasi Perpanjangan</h1>
        <p>Kelola permintaan perpanjangan peminjaman siswa</p>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5><i class="fas fa-hourglass-half"></i> Permintaan Pending ({{ $pendingExtensions->total() ?? 0 }})
                    </h5>
                    <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list"></i> Semua Peminjaman
                    </a>
                </div>
                <div class="card-body">
                    @if ($pendingExtensions && $pendingExtensions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Loan</th>
                                        <th>Siswa</th>
                                        <th>Peralatan</th>
                                        <th>Tgl Lama</th>
                                        <th>Tgl Baru</th>
                                        <th>Alasan</th>
                                        <th>Tanggal Ajukan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingExtensions as $loan)
                                        @foreach ($loan->extensions as $extension)
                                            <tr>
                                                <td><strong>#{{ $loan->id }}</strong></td>
                                                <td>
                                                    {{ $loan->borrower->user->name ?? 'N/A' }}
                                                    <br><small
                                                        class="text-muted">{{ $loan->borrower->nis ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    @foreach ($loan->loanItems as $item)
                                                        {{ $item->equipment->title ?? ($item->book->title ?? 'N/A') }}<br>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    @php
                                                        $daysLeft = \Carbon\Carbon::parse(
                                                            $extension->old_due_date,
                                                        )->diffInDays(now(), false);
                                                        if ($daysLeft < 0) {
                                                            $totalHours = now()->diffInHours(
                                                                \Carbon\Carbon::parse($extension->old_due_date),
                                                                false,
                                                            );
                                                            $lateDays = floor(abs($totalHours / 24));
                                                            $lateHours = abs($totalHours % 24);
                                                            $display =
                                                                $lateDays > 0
                                                                    ? 'Terlambat ' . $lateDays . 'h ' . $lateHours . 'j'
                                                                    : 'Terlambat ' . $lateHours . ' jam';
                                                            echo '<span class="badge bg-danger text-white fs-6">' .
                                                                $display .
                                                                '</span>';
                                                        } else {
                                                            echo '<span class="badge bg-secondary">' .
                                                                \Carbon\Carbon::parse($extension->old_due_date)->format(
                                                                    'd/m/Y',
                                                                ) .
                                                                '</span>';
                                                        }
                                                    @endphp
                                                </td>
                                                <td><span
                                                        class="badge bg-info">{{ \Carbon\Carbon::parse($extension->new_due_date)->format('d/m/Y') }}</span>
                                                </td>
                                                <td>{{ Str::limit($extension->reason, 50) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($extension->created_at)->diffForHumans() }}
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('loans.show', $loan) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <form action="{{ route('loans.extension.approve', $loan) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                onclick="return confirm('Setujui?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('loans.extension.reject', $loan) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Tolak?')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $pendingExtensions->links() }}
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-hourglass-half fa-3x text-muted mb-3"></i>
                            <h5>Tidak ada permintaan perpanjangan</h5>
                            <p class="text-muted">Semua permintaan sudah diproses atau belum ada yang diajukan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endsection
