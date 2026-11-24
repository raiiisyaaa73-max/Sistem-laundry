<?php

namespace Database\Seeders;

use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $layanans = [
            [
                'nama_layanan' => 'Reguler',
                'harga_per_kg' => 7000,
                'estimasi_waktu' => '3-4 hari',
            ],
            [
                'nama_layanan' => 'Ekspres',
                'harga_per_kg' => 12000,
                'estimasi_waktu' => '1-2 hari',
            ],
            [
                'nama_layanan' => 'Superkilat',
                'harga_per_kg' => 25000,
                'estimasi_waktu' => '5 jam selesai',
            ],
        ];

        foreach ($layanans as $layanan) {
            Layanan::updateOrCreate(
                ['nama_layanan' => $layanan['nama_layanan']],
                $layanan
            );
        }
    }
}
