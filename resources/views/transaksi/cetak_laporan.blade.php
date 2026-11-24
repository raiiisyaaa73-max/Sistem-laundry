<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - SIM Laundry</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #ffffff;
            color: #0f172a;
            padding: 1.5rem;
            font-size: 0.875rem;
        }

        .report-header {
            border-bottom: 2px solid #0f172a;
            padding-bottom: 0.75rem;
            margin-bottom: 1.25rem;
        }

        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Tombol Aksi -->
    <div class="d-flex justify-content-between align-items-center mb-3 no-print pb-2 border-bottom">
        <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Kembali ke Aplikasi</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i> Cetak / Simpan PDF
        </button>
    </div>

    <!-- Header Laporan -->
    <div class="report-header d-flex justify-content-between align-items-end">
        <div>
            <h4 class="fw-bold mb-0">SIM LAUNDRY</h4>
            <div class="text-secondary small fw-semibold">LAPORAN TRANSAKSI LAUNDRY</div>
            <div class="text-secondary small">Periode: {{ $startDate ?? 'Semua' }} s/d {{ $endDate ?? 'Sekarang' }}</div>
        </div>
        <div class="text-end small text-secondary">
            <div>Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
            <div>Petugas: {{ Auth::user()->name ?? 'Admin' }}</div>
        </div>
    </div>

    <!-- Ringkasan Angka -->
    <div class="row g-2 mb-3">
        <div class="col-4">
            <div class="border rounded p-2 text-center">
                <div class="small text-secondary">Total Pesanan</div>
                <div class="fw-bold fs-6">{{ count($transaksis) }} Transaksi</div>
            </div>
        </div>
        <div class="col-4">
            <div class="border rounded p-2 text-center">
                <div class="small text-secondary">Total Berat</div>
                <div class="fw-bold fs-6">{{ number_format($totalBerat, 2) }} kg</div>
            </div>
        </div>
        <div class="col-4">
            <div class="border rounded p-2 text-center">
                <div class="small text-secondary">Total Pemasukan (Selesai)</div>
                <div class="fw-bold fs-6 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Laporan -->
    <table class="table table-bordered table-sm align-middle mb-4">
        <thead class="table-light text-center">
            <tr>
                <th style="width: 40px;">No</th>
                <th>Nota</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>No. HP</th>
                <th>Layanan</th>
                <th>Berat (kg)</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $idx => $trx)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td class="fw-semibold">#{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $trx->created_at->format('d/m/Y') }}</td>
                <td>{{ $trx->nama_pelanggan }}</td>
                <td>{{ $trx->no_hp }}</td>
                <td>{{ $trx->layanan->nama_layanan ?? '-' }}</td>
                <td class="text-center">{{ number_format($trx->berat, 2) }}</td>
                <td class="text-end fw-semibold">{{ $trx->formatted_total }}</td>
                <td class="text-center">{{ $trx->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center py-3 text-secondary">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="table-light fw-bold">
            <tr>
                <td colspan="6" class="text-end">TOTAL KESELURUHAN:</td>
                <td class="text-center">{{ number_format($totalBerat, 2) }} kg</td>
                <td class="text-end">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- Tanda Tangan -->
    <div class="row mt-5 pt-3">
        <div class="col-8"></div>
        <div class="col-4 text-center">
            <div class="small text-secondary mb-5">Penanggung Jawab,</div>
            <div class="fw-bold text-decoration-underline">{{ Auth::user()->name ?? 'Administrator' }}</div>
            <div class="small text-secondary">{{ strtoupper(Auth::user()->role ?? 'Admin') }}</div>
        </div>
    </div>
</body>
</html>
