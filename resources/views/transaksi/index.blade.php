@extends('layouts.app')

@section('title', 'Rekap Transaksi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">Rekap Transaksi Laundry</h4>
        <small class="text-muted">Daftar transaksi cucian pelanggan dan status pengerjaan</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i> Transaksi Baru
        </a>
        <a href="{{ route('transaksi.cetak-laporan', request()->query()) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak Rekap
        </a>
    </div>
</div>

<!-- Filter Sederhana -->
<div class="card shadow-sm mb-3">
    <div class="card-body p-3">
        <form action="{{ route('transaksi.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="Proses" {{ $status === 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Selesai" {{ $status === 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
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
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate ?? '' }}">
            </div>
            <div class="col-6 col-md-2">
                <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate ?? '' }}">
            </div>
            <div class="col-12 col-md-3 d-flex gap-1">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Nama / No HP..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                @if($status || $layananId || $startDate || $endDate || $search)
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Angka Singkat -->
<div class="alert alert-light border py-2 mb-3 small d-flex flex-wrap justify-content-between align-items-center">
    <div>
        <strong>Ringkasan:</strong> Total {{ $totalItems }} data ({{ number_format($totalBerat, 2) }} kg) | 
        <span class="text-warning fw-semibold">{{ $countProses }} Proses</span> | 
        <span class="text-success fw-semibold">{{ $countSelesai }} Selesai</span>
    </div>
    <div class="fw-bold text-success">
        Total Pemasukan (Selesai): Rp {{ number_format($totalPemasukan, 0, ',', '.') }}
    </div>
</div>

<!-- Tabel Transaksi -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0 small">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Nota</th>
                        <th>Tanggal</th>
                        <th>Nama Pelanggan</th>
                        <th>No. HP</th>
                        <th>Layanan</th>
                        <th>Berat</th>
                        <th>Total Biaya</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksis as $trx)
                    <tr>
                        <td class="fw-bold">#{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-semibold">{{ $trx->nama_pelanggan }}</td>
                        <td>{{ $trx->no_hp }}</td>
                        <td>
                            {{ $trx->layanan->nama_layanan ?? '-' }}
                            <small class="text-muted d-block">{{ $trx->layanan->estimasi_waktu ?? '' }}</small>
                        </td>
                        <td>{{ number_format($trx->berat, 2) }} kg</td>
                        <td class="fw-semibold">{{ $trx->formatted_total }}</td>
                        <td>
                            <span class="badge {{ $trx->status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $trx->status }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm">
                                <!-- Tombol Update Status Cepat -->
                                @if($trx->status === 'Proses')
                                <form action="{{ route('transaksi.update-status', $trx->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Selesai">
                                    <button type="submit" class="btn btn-success btn-sm py-0 px-2" title="Tandai Selesai">
                                        <i class="bi bi-check-lg"></i> Selesai
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('transaksi.update-status', $trx->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="Proses">
                                    <button type="submit" class="btn btn-warning btn-sm py-0 px-2" title="Kembalikan ke Proses">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </button>
                                </form>
                                @endif

                                <!-- Cetak Nota -->
                                <a href="{{ route('transaksi.nota', $trx->id) }}" target="_blank" class="btn btn-secondary btn-sm py-0 px-2" title="Cetak Nota">
                                    <i class="bi bi-printer"></i>
                                </a>

                                <!-- Hapus (Admin Only) -->
                                @if(Auth::user()->isAdmin())
                                <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi #{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm py-0 px-2" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-3 text-muted">Tidak ada transaksi yang cocok</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($transaksis->hasPages())
    <div class="card-footer bg-white py-2 d-flex justify-content-center">
        {{ $transaksis->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
