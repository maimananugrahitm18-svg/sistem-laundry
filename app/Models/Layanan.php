<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $fillable = [
        'nama', 'ikon', 'harga_per_kg', 'estimasi_hari',
        'deskripsi', 'status', 'urutan',
    ];

    protected $casts = [
        'harga_per_kg'  => 'decimal:2',
        'estimasi_hari' => 'integer',
        'urutan'        => 'integer',
    ];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')->orderBy('urutan');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_kg, 0, ',', '.');
    }
}
