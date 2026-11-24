@extends('layouts.app')

@section('title', 'Pencatatan Transaksi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold py-2">
                <i class="bi bi-cart-plus me-1"></i> Form Pencatatan Transaksi Cucian
            </div>
            <form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="nama_pelanggan" class="form-label small fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('nama_pelanggan') is-invalid @enderror" id="nama_pelanggan" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" placeholder="Contoh: Budi Santoso" required autofocus>
                        @error('nama_pelanggan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label small fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="Contoh: 08123456789" required>
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12 col-sm-6">
                            <label for="layanan_id" class="form-label small fw-semibold">Jenis Layanan <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm @error('layanan_id') is-invalid @enderror" id="layanan_id" name="layanan_id" required>
                                <option value="" disabled selected>-- Pilih Layanan --</option>
                                @foreach($layanans as $layanan)
                                    <option value="{{ $layanan->id }}" 
                                            data-harga="{{ $layanan->harga_per_kg }}"
                                            data-estimasi="{{ $layanan->estimasi_waktu }}"
                                            {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}>
                                        {{ $layanan->nama_layanan }} ({{ $layanan->formatted_harga }}/kg)
                                    </option>
                                @endforeach
                            </select>
                            @error('layanan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="berat" class="form-label small fw-semibold">Berat Cucian (kg) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.1" min="0.1" max="500" class="form-control @error('berat') is-invalid @enderror" id="berat" name="berat" value="{{ old('berat') }}" placeholder="0.0" required>
                                <span class="input-group-text">kg</span>
                            </div>
                            @error('berat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Perhitungan Total Biaya Otomatis -->
                    <div class="alert alert-secondary py-2 mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted d-block">Estimasi Waktu: <strong id="previewEstimasi">-</strong></small>
                                <span class="fw-semibold">Total Biaya:</span>
                            </div>
                            <h4 class="fw-bold text-primary mb-0" id="previewTotal">Rp 0</h4>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white text-end">
                    <a href="{{ route('transaksi.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const layananSelect = document.getElementById('layanan_id');
        const beratInput = document.getElementById('berat');
        const previewEstimasi = document.getElementById('previewEstimasi');
        const previewTotal = document.getElementById('previewTotal');

        function formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        }

        function hitung() {
            const opt = layananSelect.options[layananSelect.selectedIndex];
            const harga = opt && opt.value ? parseFloat(opt.getAttribute('data-harga')) || 0 : 0;
            const estimasi = opt && opt.value ? opt.getAttribute('data-estimasi') : '-';
            const berat = parseFloat(beratInput.value) || 0;
            const total = Math.round(harga * berat);

            previewEstimasi.textContent = estimasi;
            previewTotal.textContent = formatRupiah(total);
        }

        layananSelect.addEventListener('change', hitung);
        beratInput.addEventListener('input', hitung);
        beratInput.addEventListener('change', hitung);
        hitung();
    });
</script>
@endpush
