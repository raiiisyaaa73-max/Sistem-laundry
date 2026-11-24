<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans';

    protected $fillable = [
        'nama_layanan',
        'harga_per_kg',
        'estimasi_waktu',
    ];

    /**
     * Get the transactions associated with the service.
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class, 'layanan_id');
    }

    /**
     * Helper to get formatted price in IDR.
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp '.number_format($this->harga_per_kg, 0, ',', '.');
    }
}
