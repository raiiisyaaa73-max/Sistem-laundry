<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'layanan_id',
        'nama_pelanggan',
        'no_hp',
        'berat',
        'total_harga',
        'status',
    ];

    /**
     * Get the service for this transaction.
     */
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    /**
     * Helper to get formatted total price in IDR.
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Get badge color class for status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'Selesai' => 'bg-success',
            'Proses' => 'bg-warning text-dark',
            default => 'bg-secondary',
        };
    }
}
