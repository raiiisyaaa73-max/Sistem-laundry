@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <small class="text-muted">Selamat datang, <strong>{{ Auth::user()->name }}</strong> ({{ strtoupper(Auth::user()->role) }})</small>
    </div>
    <div>
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Transaksi Baru
        </a>
    </div>
</div>

<!-- 4 Card Ringkasan Statistik Standar Bootstrap -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body p-3">
                <div class="small text-white-50 text-uppercase fw-semibold">Total Transaksi</div>
                <h3 class="fw-bold mt-1 mb-0">{{ number_format($totalTransaksi) }}</h3>
                <small class="text-white-50">Semua pesanan</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body p-3">
                <div class="small text-dark text-uppercase fw-semibold">Sedang Proses</div>
                <h3 class="fw-bold mt-1 mb-0">{{ number_format($transaksiProses) }}</h3>
                <small class="text-dark">Belum selesai</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body p-3">
                <div class="small text-white-50 text-uppercase fw-semibold">Cucian Selesai</div>
                <h3 class="fw-bold mt-1 mb-0">{{ number_format($transaksiSelesai) }}</h3>
                <small class="text-white-50">Siap diambil</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body p-3">
                <div class="small text-white-50 text-uppercase fw-semibold">Total Pendapatan</div>
                <h4 class="fw-bold mt-1 mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                <small class="text-white-50">Status selesai</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Tabel Transaksi Terbaru -->
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                <span class="fw-semibold">Transaksi Terbaru</span>
                <a href="{{ route('transaksi.index') }}" class="btn btn-sm btn-link text-decoration-none">Semua Transaksi &rarr;</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Nota</th>
                                <th>Pelanggan</th>
                                <th>Layanan</th>
                                <th>Berat</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksiTerbaru as $trx)
                            <tr>
                                <td class="fw-bold">#{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <strong>{{ $trx->nama_pelanggan }}</strong><br>
                                    <span class="text-muted">{{ $trx->no_hp }}</span>
                                </td>
                                <td>{{ $trx->layanan->nama_layanan ?? '-' }}</td>
                                <td>{{ number_format($trx->berat, 2) }} kg</td>
                                <td class="fw-semibold">{{ $trx->formatted_total }}</td>
                                <td>
                                    <span class="badge {{ $trx->status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $trx->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @if($trx->status === 'Proses')
                                        <form action="{{ route('transaksi.update-status', $trx->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Selesai">
                                            <button type="submit" class="btn btn-success btn-sm" title="Tandai Selesai">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <a href="{{ route('transaksi.nota', $trx->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" title="Cetak Nota">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">Belum ada transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Paket Layanan -->
    <div class="col-12 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                <span class="fw-semibold">Daftar Layanan</span>
                @if(Auth::user()->isAdmin())
                <a href="{{ route('layanan.index') }}" class="btn btn-sm btn-link text-decoration-none">Kelola &rarr;</a>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th>Layanan</th>
                            <th>Tarif</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($layananList as $lay)
                        <tr>
                            <td class="fw-semibold">{{ $lay->nama_layanan }}</td>
                            <td>{{ $lay->formatted_harga }}/kg</td>
                            <td>{{ $lay->estimasi_waktu }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-center p-2">
                <a href="{{ route('transaksi.create') }}" class="btn btn-outline-primary btn-sm w-100">
                    + Catat Cucian Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
