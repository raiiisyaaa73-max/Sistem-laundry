<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }} - SIM Laundry</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            padding: 1.5rem 1rem;
            color: #0f172a;
        }

        .receipt-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            padding: 1.5rem;
            font-size: 0.875rem;
        }

        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 0.75rem;
            margin-bottom: 0.75rem;
        }

        .dashed-line {
            border-top: 1px dashed #cbd5e1;
            margin: 0.75rem 0;
        }

        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .receipt-card {
                border: none;
                max-width: 100%;
                padding: 0.5rem;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <!-- Tombol Aksi (Tidak tercetak) -->
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali</a>
            <button onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bi bi-printer me-1"></i> Cetak Struk
            </button>
        </div>

        <!-- Header Struk -->
        <div class="receipt-header">
            <h5 class="fw-bold mb-1">SIM LAUNDRY</h5>
            <div class="text-secondary small">Jasa Cuci & Setrika</div>
            <div class="text-secondary small">Telp: 0812-3456-7890</div>
        </div>

        <!-- Info Order -->
        <div class="d-flex justify-content-between">
            <span class="text-secondary">No. Nota:</span>
            <span class="fw-bold">#{{ str_pad($transaksi->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-secondary">Tanggal:</span>
            <span>{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-secondary">Pelanggan:</span>
            <span class="fw-semibold">{{ $transaksi->nama_pelanggan }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-secondary">No. HP:</span>
            <span>{{ $transaksi->no_hp }}</span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-secondary">Petugas:</span>
            <span>{{ Auth::user()->name ?? 'Kasir' }}</span>
        </div>

        <div class="dashed-line"></div>

        <!-- Rincian Cucian -->
        <div class="d-flex justify-content-between">
            <div>
                <div class="fw-semibold">{{ $transaksi->layanan->nama_layanan ?? '-' }}</div>
                <small class="text-secondary">{{ number_format($transaksi->berat, 2) }} kg x Rp {{ number_format($transaksi->layanan->harga_per_kg ?? 0, 0, ',', '.') }}</small>
            </div>
            <div class="fw-bold align-self-center">
                {{ $transaksi->formatted_total }}
            </div>
        </div>

        <div class="dashed-line"></div>

        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold">TOTAL BIAYA:</span>
            <span class="fw-bold fs-6 text-primary">{{ $transaksi->formatted_total }}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <span class="text-secondary">Status:</span>
            <span class="badge {{ $transaksi->status === 'Selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                {{ $transaksi->status }}
            </span>
        </div>

        <div class="dashed-line"></div>

        <div class="text-center text-secondary small" style="font-size: 0.75rem;">
            <div>* Pengambilan cucian harap membawa nota ini.</div>
            <div class="mt-2 fw-semibold text-dark">Terima Kasih Atas Kunjungan Anda</div>
        </div>
    </div>
</body>
</html>
