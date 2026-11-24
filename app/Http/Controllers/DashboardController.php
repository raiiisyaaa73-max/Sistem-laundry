<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request): View
    {
        $totalTransaksi = Transaksi::count();
        $transaksiProses = Transaksi::where('status', 'Proses')->count();
        $transaksiSelesai = Transaksi::where('status', 'Selesai')->count();
        $totalPendapatan = Transaksi::where('status', 'Selesai')->sum('total_harga');
        $totalBerat = Transaksi::sum('berat');

        // Transaksi terbaru
        $transaksiTerbaru = Transaksi::with('layanan')
            ->latest()
            ->take(5)
            ->get();

        // Data layanan untuk ringkasan
        $layananList = Layanan::withCount('transaksis')->get();

        return view('dashboard.index', compact(
            'totalTransaksi',
            'transaksiProses',
            'transaksiSelesai',
            'totalPendapatan',
            'totalBerat',
            'transaksiTerbaru',
            'layananList'
        ));
    }
}
