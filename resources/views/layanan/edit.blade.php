@extends('layouts.app')

@section('title', 'Edit Layanan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold py-2">
                Edit Paket Layanan: {{ $layanan->nama_layanan }}
            </div>
            <form action="{{ route('layanan.update', $layanan->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Nama Layanan</label>
                        <input type="text" class="form-control form-control-sm" name="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Harga per Kg (Rp)</label>
                        <input type="number" class="form-control form-control-sm" name="harga_per_kg" value="{{ old('harga_per_kg', $layanan->harga_per_kg) }}" min="500" step="500" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Estimasi Waktu</label>
                        <input type="text" class="form-control form-control-sm" name="estimasi_waktu" value="{{ old('estimasi_waktu', $layanan->estimasi_waktu) }}" required>
                    </div>
                </div>
                <div class="card-footer bg-white text-end">
                    <a href="{{ route('layanan.index') }}" class="btn btn-secondary btn-sm">Batal</a>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
