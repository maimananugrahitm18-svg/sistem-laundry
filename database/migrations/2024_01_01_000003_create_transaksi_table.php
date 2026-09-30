<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique(); // TRX-20260930-001
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('restrict');
            $table->foreignId('layanan_id')->constrained('layanan')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // kasir yg input
            $table->decimal('berat', 8, 2); // kg
            $table->decimal('harga_per_kg', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('metode_pembayaran', ['Tunai', 'Transfer', 'QRIS'])->default('Tunai');
            $table->enum('status_pembayaran', ['Lunas', 'DP', 'Belum Bayar'])->default('Lunas');
            $table->decimal('jumlah_dp', 12, 2)->default(0);
            $table->decimal('sisa_bayar', 12, 2)->default(0);
            $table->enum('status', [
                'Baru', 'Diproses', 'Dicuci', 'Disetrika', 'Siap Diambil', 'Selesai', 'Dibatalkan'
            ])->default('Baru');
            $table->date('tanggal_masuk');
            $table->date('estimasi_selesai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_batal')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
