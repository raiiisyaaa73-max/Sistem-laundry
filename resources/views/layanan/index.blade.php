@extends('layouts.app')

@section('title', 'Data Layanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0">Data Jenis Layanan</h4>
        <small class="text-muted">Kelola paket tarif dan estimasi waktu pengerjaan</small>
    </div>
    <div>
        @if(Auth::user()->isAdmin())
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLayanan">
            <i class="bi bi-plus-lg me-1"></i> Tambah Layanan
        </button>
        @else
        <span class="badge bg-secondary">Akses Kasir (Hanya Lihat)</span>
        @endif
    </div>
</div>

<!-- Form Pencarian Sederhana -->
<div class="row mb-3">
    <div class="col-12 col-md-4">
        <form action="{{ route('layanan.index') }}" method="GET" class="input-group input-group-sm">
            <input type="text" name="search" class="form-control" placeholder="Cari nama layanan..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if($search)
            <a href="{{ route('layanan.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </form>
    </div>
</div>

<!-- Tabel Standar -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama Layanan</th>
                        <th>Harga per Kg</th>
                        <th>Estimasi Waktu</th>
                        <th>Total Pemakaian</th>
                        @if(Auth::user()->isAdmin())
                        <th class="text-center" style="width: 140px;">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($layanans as $index => $layanan)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="fw-semibold">{{ $layanan->nama_layanan }}</td>
                        <td>{{ $layanan->formatted_harga }} / kg</td>
                        <td>{{ $layanan->estimasi_waktu }}</td>
                        <td>{{ $layanan->transaksis_count }} kali</td>
                        @if(Auth::user()->isAdmin())
                        <td class="text-center">
                            <a href="{{ route('layanan.edit', $layanan->id) }}" class="btn btn-warning btn-sm py-0 px-2" title="Edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('layanan.destroy', $layanan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus layanan {{ $layanan->nama_layanan }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm py-0 px-2" title="Hapus">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->isAdmin() ? '6' : '5' }}" class="text-center py-3 text-muted">
                            Data layanan tidak ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Layanan (Admin Only) -->
@if(Auth::user()->isAdmin())
<div class="modal fade" id="modalTambahLayanan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-6 fw-bold">Tambah Layanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('layanan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Layanan</label>
                        <input type="text" class="form-control form-control-sm" name="nama_layanan" placeholder="Contoh: Superkilat" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Harga per Kg (Rp)</label>
                        <input type="number" class="form-control form-control-sm" name="harga_per_kg" placeholder="Contoh: 15000" min="500" step="500" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Estimasi Waktu</label>
                        <input type="text" class="form-control form-control-sm" name="estimasi_waktu" placeholder="Contoh: 1 hari selesai" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
