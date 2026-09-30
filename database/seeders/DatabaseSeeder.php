<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Transaksi;
use App\Models\Pengaturan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Users (Hanya 2 Role: Owner dan Kasir)
        $owner = User::updateOrCreate(
            ['email' => 'owner@laundrypro.com'],
            [
                'name' => 'Pak Budi (Owner / Admin)',
                'password' => Hash::make('owner123'),
                'role' => 'owner',
                'no_hp' => '081387654321',
            ]
        );

        $kasir = User::updateOrCreate(
            ['email' => 'kasir@laundrypro.com'],
            [
                'name' => 'Siti (Kasir / Petugas)',
                'password' => Hash::make('kasir123'),
                'role' => 'kasir',
                'no_hp' => '082198765432',
            ]
        );

        // 2. Layanan
        $layanan1 = Layanan::firstOrCreate(
            ['nama' => 'Cuci Kering'],
            [
                'ikon' => '👕',
                'harga_per_kg' => 7000,
                'estimasi_hari' => 1,
                'deskripsi' => 'Cucian dicuci bersih menggunakan mesin, dikeringkan, dan dilipat rapi.',
                'status' => 'aktif',
                'urutan' => 1,
            ]
        );

        $layanan2 = Layanan::firstOrCreate(
            ['nama' => 'Cuci + Setrika'],
            [
                'ikon' => '👔',
                'harga_per_kg' => 10000,
                'estimasi_hari' => 2,
                'deskripsi' => 'Paket komplit cuci bersih, kering, disetrika uap halus dan wangi.',
                'status' => 'aktif',
                'urutan' => 2,
            ]
        );

        $layanan3 = Layanan::firstOrCreate(
            ['nama' => 'Setrika Saja'],
            [
                'ikon' => '💨',
                'harga_per_kg' => 6000,
                'estimasi_hari' => 1,
                'deskripsi' => 'Hanya setrika uap rapi untuk pakaian yang sudah Anda cuci sendiri.',
                'status' => 'aktif',
                'urutan' => 3,
            ]
        );

        $layanan4 = Layanan::firstOrCreate(
            ['nama' => 'Express Kilat 6 Jam'],
            [
                'ikon' => '⚡',
                'harga_per_kg' => 15000,
                'estimasi_hari' => 1,
                'deskripsi' => 'Layanan kilat prioritas super cepat! Siap dalam 6 jam.',
                'status' => 'aktif',
                'urutan' => 4,
            ]
        );

        $layanan5 = Layanan::firstOrCreate(
            ['nama' => 'Bed Cover & Selimut'],
            [
                'ikon' => '🛏️',
                'harga_per_kg' => 25000,
                'estimasi_hari' => 2,
                'deskripsi' => 'Perawatan khusus bed cover, selimut tebal, sprei besar wangi higienis.',
                'status' => 'aktif',
                'urutan' => 5,
            ]
        );

        // 3. Pelanggan
        $pelanggan1 = Pelanggan::firstOrCreate(
            ['kode_pelanggan' => 'PLG-001'],
            [
                'nama' => 'Iman Mendrofa',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Boulevard Raya Blok A4, Jakarta',
                'email' => 'iman@example.com',
                'jenis_kelamin' => 'L',
                'catatan' => 'Pelanggan VIP',
                'aktif' => true,
            ]
        );

        $pelanggan2 = Pelanggan::firstOrCreate(
            ['kode_pelanggan' => 'PLG-002'],
            [
                'nama' => 'Dewi Sartika',
                'no_hp' => '082187654321',
                'alamat' => 'Jl. Melati No. 15, Jakarta',
                'email' => 'dewi@example.com',
                'jenis_kelamin' => 'P',
                'catatan' => 'Pewangi lavender',
                'aktif' => true,
            ]
        );

        $pelanggan3 = Pelanggan::firstOrCreate(
            ['kode_pelanggan' => 'PLG-003'],
            [
                'nama' => 'Ahmad Fauzi',
                'no_hp' => '085712345678',
                'alamat' => 'Jl. Anggrek No. 8B, Jakarta',
                'email' => 'ahmad@example.com',
                'jenis_kelamin' => 'L',
                'catatan' => 'Tanpa pelembut',
                'aktif' => true,
            ]
        );

        $pelanggan4 = Pelanggan::firstOrCreate(
            ['kode_pelanggan' => 'PLG-004'],
            [
                'nama' => 'Rina Wijaya',
                'no_hp' => '087812348900',
                'alamat' => 'Apartemen Mediterania Tower A-12',
                'email' => 'rina@example.com',
                'jenis_kelamin' => 'P',
                'catatan' => 'Baju putih dipisah',
                'aktif' => true,
            ]
        );

        // 4. Pengaturan
        $pengaturan = [
            'nama_laundry' => 'LaundryPro Express',
            'slogan' => 'Bersih, Wangi, Rapi, & Tepat Waktu',
            'alamat' => 'Jl. Merdeka Raya No. 45, Jakarta Pusat',
            'telepon' => '0812-3456-7890',
            'email' => 'support@laundrypro.com',
            'footer_struk' => 'Terima kasih atas kepercayaan Anda! Cucian bersih hidup ceria.',
            'syarat_ketentuan' => '1. Pengambilan cucian wajib membawa struk / nota ini. 2. Kerusakan/luntur akibat sifat bahan di luar tanggung jawab kami. 3. Komplain maksimal 1x24 jam setelah cucian diambil.',
        ];

        foreach ($pengaturan as $k => $v) {
            Pengaturan::updateOrCreate(['key' => $k], ['value' => $v]);
        }

        // 5. Transaksi Sampel
        $today = Carbon::today();

        $transaksiData = [
            [
                'no_transaksi' => 'TRX-' . $today->format('Ymd') . '-001',
                'pelanggan_id' => $pelanggan1->id,
                'layanan_id' => $layanan2->id, // Cuci + Setrika
                'user_id' => $kasir->id,
                'berat' => 5.0,
                'harga_per_kg' => 10000,
                'subtotal' => 50000,
                'diskon' => 0,
                'total' => 50000,
                'metode_pembayaran' => 'Tunai',
                'status_pembayaran' => 'Lunas',
                'jumlah_dp' => 50000,
                'sisa_bayar' => 0,
                'status' => 'Diproses',
                'tanggal_masuk' => $today->format('Y-m-d'),
                'estimasi_selesai' => $today->copy()->addDays(2)->format('Y-m-d'),
                'catatan' => 'Kemeja kerja warna biru',
            ],
            [
                'no_transaksi' => 'TRX-' . $today->format('Ymd') . '-002',
                'pelanggan_id' => $pelanggan2->id,
                'layanan_id' => $layanan4->id, // Express
                'user_id' => $kasir->id,
                'berat' => 3.0,
                'harga_per_kg' => 15000,
                'subtotal' => 45000,
                'diskon' => 0,
                'total' => 45000,
                'metode_pembayaran' => 'QRIS',
                'status_pembayaran' => 'Lunas',
                'jumlah_dp' => 45000,
                'sisa_bayar' => 0,
                'status' => 'Siap Diambil',
                'tanggal_masuk' => $today->format('Y-m-d'),
                'estimasi_selesai' => $today->format('Y-m-d'),
                'catatan' => 'Mau dipakai malam ini',
            ],
            [
                'no_transaksi' => 'TRX-' . $today->format('Ymd') . '-003',
                'pelanggan_id' => $pelanggan3->id,
                'layanan_id' => $layanan1->id, // Cuci Kering
                'user_id' => $owner->id,
                'berat' => 4.0,
                'harga_per_kg' => 7000,
                'subtotal' => 28000,
                'diskon' => 0,
                'total' => 28000,
                'metode_pembayaran' => 'Tunai',
                'status_pembayaran' => 'DP',
                'jumlah_dp' => 15000,
                'sisa_bayar' => 13000,
                'status' => 'Dicuci',
                'tanggal_masuk' => $today->format('Y-m-d'),
                'estimasi_selesai' => $today->copy()->addDays(1)->format('Y-m-d'),
                'catatan' => 'Kaos polos dan celana pendek',
            ],
            [
                'no_transaksi' => 'TRX-' . $today->format('Ymd') . '-004',
                'pelanggan_id' => $pelanggan4->id,
                'layanan_id' => $layanan5->id, // Bed Cover
                'user_id' => $kasir->id,
                'berat' => 2.0,
                'harga_per_kg' => 25000,
                'subtotal' => 50000,
                'diskon' => 5000,
                'total' => 45000,
                'metode_pembayaran' => 'Transfer',
                'status_pembayaran' => 'Belum Bayar',
                'jumlah_dp' => 0,
                'sisa_bayar' => 45000,
                'status' => 'Baru',
                'tanggal_masuk' => $today->format('Y-m-d'),
                'estimasi_selesai' => $today->copy()->addDays(2)->format('Y-m-d'),
                'catatan' => 'Bed cover king size motif bunga',
            ],
            [
                'no_transaksi' => 'TRX-' . $today->copy()->subDays(1)->format('Ymd') . '-001',
                'pelanggan_id' => $pelanggan1->id,
                'layanan_id' => $layanan3->id, // Setrika Saja
                'user_id' => $kasir->id,
                'berat' => 5.0,
                'harga_per_kg' => 6000,
                'subtotal' => 30000,
                'diskon' => 0,
                'total' => 30000,
                'metode_pembayaran' => 'Tunai',
                'status_pembayaran' => 'Lunas',
                'jumlah_dp' => 30000,
                'sisa_bayar' => 0,
                'status' => 'Selesai',
                'tanggal_masuk' => $today->copy()->subDays(1)->format('Y-m-d'),
                'estimasi_selesai' => $today->format('Y-m-d'),
                'tanggal_selesai' => $today->format('Y-m-d'),
                'catatan' => 'Selesai dan sudah diambil',
            ],
        ];

        foreach ($transaksiData as $t) {
            Transaksi::firstOrCreate(['no_transaksi' => $t['no_transaksi']], $t);
        }
    }
}
