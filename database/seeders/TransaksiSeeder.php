<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reguler = Layanan::where('nama_layanan', 'Reguler')->first();
        $ekspres = Layanan::where('nama_layanan', 'Ekspres')->first();
        $superkilat = Layanan::where('nama_layanan', 'Superkilat')->first();

        if ($reguler) {
            Transaksi::create([
                'layanan_id' => $reguler->id,
                'nama_pelanggan' => 'Siti Rahma',
                'no_hp' => '081234567890',
                'berat' => 4.5,
                'total_harga' => (int) (4.5 * $reguler->harga_per_kg),
                'status' => 'Selesai',
                'created_at' => now()->subDays(2),
            ]);

            Transaksi::create([
                'layanan_id' => $reguler->id,
                'nama_pelanggan' => 'Budi Santoso',
                'no_hp' => '082198765432',
                'berat' => 3.0,
                'total_harga' => (int) (3.0 * $reguler->harga_per_kg),
                'status' => 'Proses',
                'created_at' => now()->subDay(),
            ]);
        }

        if ($ekspres) {
            Transaksi::create([
                'layanan_id' => $ekspres->id,
                'nama_pelanggan' => 'Nadia Miranti',
                'no_hp' => '085712349876',
                'berat' => 2.5,
                'total_harga' => (int) (2.5 * $ekspres->harga_per_kg),
                'status' => 'Selesai',
                'created_at' => now()->subHours(10),
            ]);
        }

        if ($superkilat) {
            Transaksi::create([
                'layanan_id' => $superkilat->id,
                'nama_pelanggan' => 'Ahmad Fauzi',
                'no_hp' => '087811223344',
                'berat' => 2.0,
                'total_harga' => (int) (2.0 * $superkilat->harga_per_kg),
                'status' => 'Proses',
                'created_at' => now()->subHours(2),
            ]);
        }
    }
}
