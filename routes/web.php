<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes (Kasir & Owner)
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Transaksi (Kasir & Owner bisa buat, lihat, cetak struk, update status cuci, ubah status bayar/DP/catatan)
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}', [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::get('/transaksi/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{transaksi}', [TransaksiController::class, 'update'])->name('transaksi.update');
    Route::post('/transaksi/{transaksi}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.update-status');
    Route::get('/transaksi/{transaksi}/struk', [TransaksiController::class, 'struk'])->name('transaksi.struk');
    Route::get('/transaksi/{transaksi}/cetak-struk', [TransaksiController::class, 'cetakStruk'])->name('transaksi.cetak-struk');
    Route::post('/transaksi/{transaksi}/lunasi', [TransaksiController::class, 'lunasi'])->name('transaksi.lunasi');

    // Pelanggan (Kasir & Owner bisa cari, lihat riwayat, tambah pelanggan baru, serta EDIT nama / no HP agar bisa di-chat WA)
    Route::get('/pelanggan/search', [PelangganController::class, 'search'])->name('pelanggan.search');
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::get('/pelanggan/{pelanggan}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

    // KHUSUS OWNER (ADMIN PENUH): Hapus Transaksi, Ubah Tarif Layanan Master, Laporan Keuangan, & Pengaturan
    Route::middleware(['owner'])->group(function () {
        // Hapus Transaksi (Hanya Owner)
        Route::delete('/transaksi/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

        // Paket Layanan & Tarif (Kelola harga per kg hanya Owner)
        Route::resource('layanan', LayananController::class)->except(['create', 'show', 'edit']);
        Route::post('/layanan/{layanan}/toggle', [LayananController::class, 'toggleStatus'])->name('layanan.toggle');

        // Laporan Keuangan (Hanya Owner: Lihat Omset, Download Excel CSV, Cetak PDF)
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::get('/laporan/csv', [LaporanController::class, 'exportCsv'])->name('laporan.csv');

        // Pengaturan Sistem & Manajemen Kasir
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
        Route::post('/pengaturan/users', [PengaturanController::class, 'storeUser'])->name('pengaturan.users.store');
        Route::put('/pengaturan/users/{user}', [PengaturanController::class, 'updateUser'])->name('pengaturan.users.update');
        Route::delete('/pengaturan/users/{user}', [PengaturanController::class, 'destroyUser'])->name('pengaturan.users.destroy');
    });
});
