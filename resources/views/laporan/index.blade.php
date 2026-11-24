@extends('layouts.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">Laporan Keuangan</h4>
        <small class="text-muted">Rekapitulasi omzet dan rincian transaksi</small>
    </div>
    <div>
        <a href="{{ route('transaksi.cetak-laporan', request()->query()) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak Laporan
        </a>
    </div>
</div>

<!-- Filter Tanggal -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <form action="{{ route('laporan.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
            </div>
            <div class="col-6 col-md-3">
                <label class="form-label small fw-semibold mb-1">Layanan</label>
                <select name="layanan_id" class="form-select form-select-sm">
                    <option value="">Semua Layanan</option>
                    @foreach($layanans as $lay)
                        <option value="{{ $lay->id }}" {{ $layananId == $lay->id ? 'selected' : '' }}>
                            {{ $lay->nama_layanan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-3 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Tampilkan</button>
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- 4 Card Ringkasan Finansial -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-success text-white">
            <div class="card-body p-3">
                <div class="small text-white-50 text-uppercase fw-semibold">Pendapatan Masuk</div>
                <h4 class="fw-bold mt-1 mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                <small class="text-white-50">Status Selesai</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body p-3">
                <div class="small text-dark text-uppercase fw-semibold">Potensi Pendapatan</div>
                <h4 class="fw-bold mt-1 mb-0">Rp {{ number_format($potensiPendapatan, 0, ',', '.') }}</h4>
                <small class="text-dark">Status Proses</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body p-3">
                <div class="small text-white-50 text-uppercase fw-semibold">Total Transaksi</div>
                <h4 class="fw-bold mt-1 mb-0">{{ $totalTransaksi }}</h4>
                <small class="text-white-50">Pesanan</small>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card shadow-sm border-0 bg-info text-white">
            <div class="card-body p-3">
                <div class="small text-white-50 text-uppercase fw-semibold">Total Berat</div>
                <h4 class="fw-bold mt-1 mb-0">{{ number_format($totalBerat, 2) }} kg</h4>
                <small class="text-white-50">Volume cucian</small>
            </div>
        </div>
    </div>
</div>

<!-- Tabel Rincian -->
<div class="card shadow-sm">
    <div class="card-header bg-white fw-bold py-2">
        Rincian Transaksi
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th>Nota</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $trx)
                    <tr>
                        <td class="fw-bold">#{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $trx->nama_pelanggan }}</td>
                        <td>{{ $trx->layanan->nama_layanan ?? '-' }}</td>
                        <td>{{ number_format($trx->berat, 2) }} kg</td>
                        <td class="fw-semibold">{{ $trx->formatted_total }}</td>
                        <td>
                            <span class="badge {{ $trx->status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $trx->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-3 text-muted">Tidak ada data transaksi pada periode ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
