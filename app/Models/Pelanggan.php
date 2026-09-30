<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';

    protected $fillable = [
        'kode_pelanggan', 'nama', 'no_hp', 'alamat',
        'email', 'jenis_kelamin', 'catatan', 'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function transaksi(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    public function totalTransaksi(): int
    {
        return $this->transaksi()->count();
    }

    public function totalBelanja(): float
    {
        return $this->transaksi()->where('status_pembayaran', 'Lunas')->sum('total');
    }

    public static function generateKode(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $num  = $last ? ((int) substr($last->kode_pelanggan, 4)) + 1 : 1;
        return 'PLG-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
}
