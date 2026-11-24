<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Display the financial and performance report (Admin only).
     */
    public function index(Request $request): View
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));
        $layananId = $request->query('layanan_id');

        $query = Transaksi::with('layanan')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($layananId) {
            $query->where('layanan_id', $layananId);
        }

        $transaksis = (clone $query)->orderBy('created_at', 'desc')->get();

        $totalPendapatan = $transaksis->where('status', 'Selesai')->sum('total_harga');
        $potensiPendapatan = $transaksis->where('status', 'Proses')->sum('total_harga');
        $totalTransaksi = $transaksis->count();
        $totalBerat = $transaksis->sum('berat');

        $layanans = Layanan::all();

        // Breakdown per layanan
        $breakdownLayanan = Layanan::withCount(['transaksis' => function ($q) use ($startDate, $endDate) {
            $q->whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate);
        }])->get();

        return view('laporan.index', compact(
            'transaksis',
            'layanans',
            'startDate',
            'endDate',
            'layananId',
            'totalPendapatan',
            'potensiPendapatan',
            'totalTransaksi',
            'totalBerat',
            'breakdownLayanan'
        ));
    }
}
