<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    /**
     * Display a listing of the services.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $layanans = Layanan::when($search, function ($query, $search) {
            return $query->where('nama_layanan', 'like', "%{$search}%");
        })
            ->withCount('transaksis')
            ->orderBy('harga_per_kg', 'asc')
            ->get();

        return view('layanan.index', compact('layanans', 'search'));
    }

    /**
     * Store a newly created service in storage (Admin only).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255', 'unique:layanans,nama_layanan'],
            'harga_per_kg' => ['required', 'numeric', 'min:500'],
            'estimasi_waktu' => ['required', 'string', 'max:100'],
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.unique' => 'Nama layanan ini sudah terdaftar.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.numeric' => 'Harga per kg harus berupa angka.',
            'harga_per_kg.min' => 'Harga per kg minimal Rp 500.',
            'estimasi_waktu.required' => 'Estimasi waktu pengerjaan wajib diisi.',
        ]);

        Layanan::create($validated);

        return redirect()->route('layanan.index')
            ->with('success', 'Layanan berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified service (Admin only).
     */
    public function edit(Layanan $layanan): View
    {
        return view('layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified service in storage (Admin only).
     */
    public function update(Request $request, Layanan $layanan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255', 'unique:layanans,nama_layanan,'.$layanan->id],
            'harga_per_kg' => ['required', 'numeric', 'min:500'],
            'estimasi_waktu' => ['required', 'string', 'max:100'],
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'nama_layanan.unique' => 'Nama layanan ini sudah digunakan.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.numeric' => 'Harga per kg harus berupa angka.',
            'harga_per_kg.min' => 'Harga per kg minimal Rp 500.',
            'estimasi_waktu.required' => 'Estimasi waktu pengerjaan wajib diisi.',
        ]);

        $layanan->update($validated);

        return redirect()->route('layanan.index')
            ->with('success', 'Data layanan berhasil diperbarui!');
    }

    /**
     * Remove the specified service from storage (Admin only).
     */
    public function destroy(Layanan $layanan): RedirectResponse
    {
        $nama = $layanan->nama_layanan;
        $layanan->delete();

        return redirect()->route('layanan.index')
            ->with('success', "Layanan '{$nama}' berhasil dihapus.");
    }
}
