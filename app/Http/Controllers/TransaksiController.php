<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    /**
     * Display a listing of transactions with filtering.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $layananId = $request->query('layanan_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $search = $request->query('search');

        $query = Transaksi::with('layanan');

        if ($status && in_array($status, ['Proses', 'Selesai'], true)) {
            $query->where('status', $status);
        }

        if ($layananId) {
            $query->where('layanan_id', $layananId);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelanggan', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $transaksis = (clone $query)->latest()->paginate(10)->withQueryString();

        // Calculate summary from the current filtered query
        $totalItems = (clone $query)->count();
        $totalBerat = (clone $query)->sum('berat');
        $totalPemasukan = (clone $query)->where('status', 'Selesai')->sum('total_harga');
        $totalPemasukanSemua = (clone $query)->sum('total_harga');
        $countProses = (clone $query)->where('status', 'Proses')->count();
        $countSelesai = (clone $query)->where('status', 'Selesai')->count();

        $layanans = Layanan::orderBy('nama_layanan')->get();

        return view('transaksi.index', compact(
            'transaksis',
            'layanans',
            'status',
            'layananId',
            'startDate',
            'endDate',
            'search',
            'totalItems',
            'totalBerat',
            'totalPemasukan',
            'totalPemasukanSemua',
            'countProses',
            'countSelesai'
        ));
    }

    /**
     * Show the form for creating a new transaction.
     */
    public function create(): View
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();

        return view('transaksi.create', compact('layanans'));
    }

    /**
     * Store a newly created transaction in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'layanan_id' => ['required', 'exists:layanans,id'],
            'berat' => ['required', 'numeric', 'min:0.1', 'max:1000'],
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'no_hp.required' => 'Nomor HP pelanggan wajib diisi.',
            'layanan_id.required' => 'Silakan pilih jenis layanan.',
            'layanan_id.exists' => 'Jenis layanan yang dipilih tidak valid.',
            'berat.required' => 'Berat cucian wajib diisi.',
            'berat.numeric' => 'Berat harus berupa angka desimal (contoh: 2.5).',
            'berat.min' => 'Berat cucian minimal 0.1 kg.',
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);

        // Kalkulasi total_harga di backend controller (berat * harga_per_kg)
        $totalHarga = (int) round($validated['berat'] * $layanan->harga_per_kg);

        $transaksi = Transaksi::create([
            'nama_pelanggan' => $validated['nama_pelanggan'],
            'no_hp' => $validated['no_hp'],
            'layanan_id' => $layanan->id,
            'berat' => $validated['berat'],
            'total_harga' => $totalHarga,
            'status' => 'Proses',
        ]);

        return redirect()->route('transaksi.index')
            ->with('success', "Transaksi untuk {$transaksi->nama_pelanggan} berhasil dibuat! Total: Rp ".number_format($totalHarga, 0, ',', '.'));
    }

    /**
     * Update the status of the specified transaction.
     */
    public function updateStatus(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:Proses,Selesai'],
        ]);

        $statusLama = $transaksi->status;
        $statusBaru = $request->input('status');

        $transaksi->update([
            'status' => $statusBaru,
        ]);

        return redirect()->back()
            ->with('success', "Status transaksi #{$transaksi->id} ({$transaksi->nama_pelanggan}) berhasil diubah menjadi '{$statusBaru}'.");
    }

    /**
     * Print receipt (Struk/Nota Laundry) for a transaction.
     */
    public function cetakNota(Transaksi $transaksi): View
    {
        $transaksi->load('layanan');

        return view('transaksi.nota', compact('transaksi'));
    }

    /**
     * Print transaction report page.
     */
    public function cetakLaporan(Request $request): View
    {
        $status = $request->query('status');
        $layananId = $request->query('layanan_id');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $query = Transaksi::with('layanan');

        if ($status && in_array($status, ['Proses', 'Selesai'], true)) {
            $query->where('status', $status);
        }

        if ($layananId) {
            $query->where('layanan_id', $layananId);
        }

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $transaksis = $query->orderBy('created_at', 'asc')->get();

        $totalPendapatan = $transaksis->where('status', 'Selesai')->sum('total_harga');
        $totalSemua = $transaksis->sum('total_harga');
        $totalBerat = $transaksis->sum('berat');

        return view('transaksi.cetak_laporan', compact(
            'transaksis',
            'status',
            'startDate',
            'endDate',
            'totalPendapatan',
            'totalSemua',
            'totalBerat'
        ));
    }

    /**
     * Remove the specified transaction (Admin only).
     */
    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        $id = $transaksi->id;
        $pelanggan = $transaksi->nama_pelanggan;
        $transaksi->delete();

        return redirect()->route('transaksi.index')
            ->with('success', "Data transaksi #{$id} ({$pelanggan}) berhasil dihapus.");
    }
}
