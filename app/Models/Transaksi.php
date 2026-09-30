<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'no_transaksi', 'pelanggan_id', 'layanan_id', 'user_id',
        'berat', 'harga_per_kg', 'subtotal', 'diskon', 'total',
        'metode_pembayaran', 'status_pembayaran', 'jumlah_dp', 'sisa_bayar',
        'status', 'tanggal_masuk', 'estimasi_selesai', 'tanggal_selesai',
        'catatan', 'catatan_batal',
    ];

    protected $casts = [
        'berat'          => 'decimal:2',
        'harga_per_kg'   => 'decimal:2',
        'subtotal'       => 'decimal:2',
        'diskon'         => 'decimal:2',
        'total'          => 'decimal:2',
        'jumlah_dp'      => 'decimal:2',
        'sisa_bayar'     => 'decimal:2',
        'tanggal_masuk'  => 'date',
        'estimasi_selesai' => 'date',
        'tanggal_selesai'  => 'date',
    ];

    // Status flow
    const STATUSES = [
        'Baru', 'Diproses', 'Dicuci', 'Disetrika', 'Siap Diambil', 'Selesai', 'Dibatalkan'
    ];

    const STATUS_COLORS = [
        'Baru'         => 'primary',
        'Diproses'     => 'warning',
        'Dicuci'       => 'info',
        'Disetrika'    => 'purple',
        'Siap Diambil' => 'success',
        'Selesai'      => 'dark',
        'Dibatalkan'   => 'danger',
    ];

    const STATUS_ICONS = [
        'Baru'         => '📥',
        'Diproses'     => '🔄',
        'Dicuci'       => '🌀',
        'Disetrika'    => '✨',
        'Siap Diambil' => '✅',
        'Selesai'      => '🏆',
        'Dibatalkan'   => '❌',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function kasir(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function getStatusIconAttribute(): string
    {
        return self::STATUS_ICONS[$this->status] ?? '📋';
    }

    public static function generateNomor(): string
    {
        $today    = now()->format('Ymd');
        $prefix   = 'TRX-' . $today . '-';
        $last     = self::where('no_transaksi', 'like', $prefix . '%')
                        ->orderBy('no_transaksi', 'desc')
                        ->first();
        $seq      = $last ? ((int) substr($last->no_transaksi, -3)) + 1 : 1;
        return $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
    }
}
