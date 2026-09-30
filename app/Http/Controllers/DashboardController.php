<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Stats hari ini
        $todayTrx = Transaksi::whereDate('tanggal_masuk', $today);

        $stats = [
            'total_hari_ini'  => $todayTrx->count(),
            'pesanan_masuk'   => Transaksi::where('status', 'Baru')->count(),
            'sedang_proses'   => Transaksi::whereIn('status', ['Diproses','Dicuci','Disetrika'])->count(),
            'siap_diambil'    => Transaksi::where('status', 'Siap Diambil')->count(),
            'selesai_hari_ini'=> (clone $todayTrx)->where('status', 'Selesai')->count(),
            'pendapatan_hari_ini' => (clone $todayTrx)->where('status_pembayaran', 'Lunas')->sum('total'),
            'total_pelanggan' => Pelanggan::where('aktif', true)->count(),
            'pendapatan_bulan'=> Transaksi::whereMonth('tanggal_masuk', now()->month)
                                          ->whereYear('tanggal_masuk', now()->year)
                                          ->where('status_pembayaran', 'Lunas')
                                          ->sum('total'),
        ];

        // Chart pendapatan 7 hari
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $chartData[] = [
                'label'  => now()->subDays($i)->translatedFormat('D, d M'),
                'total'  => Transaksi::whereDate('tanggal_masuk', $date)
                                     ->where('status_pembayaran', 'Lunas')
                                     ->sum('total'),
                'count'  => Transaksi::whereDate('tanggal_masuk', $date)->count(),
            ];
        }

        // Status summary (semua)
        $statusSummary = Transaksi::select('status', DB::raw('count(*) as total'))
                                   ->groupBy('status')
                                   ->get()
                                   ->keyBy('status');

        // Transaksi terbaru
        $recentTrx = Transaksi::with(['pelanggan', 'layanan'])
                               ->orderBy('created_at', 'desc')
                               ->limit(8)
                               ->get();

        // Layanan terlaris
        $layananTerlaris = Layanan::withCount('transaksi')
                                   ->orderBy('transaksi_count', 'desc')
                                   ->limit(5)
                                   ->get();

        // Transaksi belum lunas
        $belumLunas = Transaksi::whereIn('status_pembayaran', ['DP','Belum Bayar'])
                                ->where('status', '!=', 'Dibatalkan')
                                ->count();

        return view('dashboard', compact(
            'stats', 'chartData', 'statusSummary',
            'recentTrx', 'layananTerlaris', 'belumLunas'
        ));
    }
}
