<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $dari   = $request->dari   ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? now()->toDateString();
        $jenis  = $request->jenis  ?? 'transaksi';

        // Normalisasi range datetime agar cocok dengan SQLite (stored as '2026-09-30 00:00:00')
        $dariDt   = $dari . ' 00:00:00';
        $sampaiDt = $sampai . ' 23:59:59';

        $data = match($jenis) {
            'pendapatan' => $this->laporanPendapatan($dariDt, $sampaiDt),
            'pelanggan'  => $this->laporanPelanggan($dariDt, $sampaiDt, $dari, $sampai),
            'layanan'    => $this->laporanLayanan($dariDt, $sampaiDt),
            default      => $this->laporanTransaksi($dariDt, $sampaiDt),
        };

        // Chart data
        $chartData = $this->getChartData($dari, $sampai);

        return view('laporan.index', array_merge(
            compact('dari', 'sampai', 'jenis', 'chartData'),
            $data
        ));
    }

    private function laporanTransaksi(string $dariDt, string $sampaiDt): array
    {
        $transaksi = Transaksi::with(['pelanggan', 'layanan'])
            ->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt])
            ->orderBy('tanggal_masuk', 'desc')
            ->get();

        return [
            'items'   => $transaksi,
            'summary' => [
                'total_trx'   => $transaksi->count(),
                'total_berat' => $transaksi->sum('berat'),
                'total_lunas' => $transaksi->where('status_pembayaran', 'Lunas')->sum('total'),
                'total_dp'    => $transaksi->where('status_pembayaran', 'DP')->sum('jumlah_dp'),
                'total_belum' => $transaksi->where('status_pembayaran', 'Belum Bayar')->count(),
            ],
        ];
    }

    private function laporanPendapatan(string $dariDt, string $sampaiDt): array
    {
        $grouped = Transaksi::select(
                DB::raw("date(tanggal_masuk) as tanggal_masuk"),
                DB::raw('SUM(total) as total_pendapatan'),
                DB::raw('COUNT(*) as jumlah_transaksi'),
                DB::raw('SUM(berat) as total_berat')
            )
            ->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt])
            ->where('status_pembayaran', 'Lunas')
            ->groupBy(DB::raw('date(tanggal_masuk)'))
            ->orderBy(DB::raw('date(tanggal_masuk)'))
            ->get();

        return [
            'items'   => $grouped,
            'summary' => [
                'total_pendapatan' => $grouped->sum('total_pendapatan'),
                'rata_harian'      => $grouped->count() ? $grouped->avg('total_pendapatan') : 0,
                'hari_aktif'       => $grouped->count(),
                'total_trx'        => $grouped->sum('jumlah_transaksi'),
            ],
        ];
    }

    private function laporanPelanggan(string $dariDt, string $sampaiDt, string $dari, string $sampai): array
    {
        $pelanggan = Pelanggan::withCount(['transaksi as total_trx' => function ($q) use ($dariDt, $sampaiDt) {
                $q->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt]);
            }])
            ->withSum(['transaksi as total_belanja' => function ($q) use ($dariDt, $sampaiDt) {
                $q->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt])->where('status_pembayaran', 'Lunas');
            }], 'total')
            ->get()
            ->filter(fn($p) => $p->total_trx > 0)
            ->sortByDesc('total_belanja')
            ->values();

        return [
            'items'   => $pelanggan,
            'summary' => [
                'total_pelanggan' => $pelanggan->count(),
                'pelanggan_baru'  => Pelanggan::whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59'])->count(),
                'top_pelanggan'   => $pelanggan->first()?->nama ?? '-',
                'total_belanja'   => $pelanggan->sum('total_belanja'),
            ],
        ];
    }

    private function laporanLayanan(string $dariDt, string $sampaiDt): array
    {
        $layanan = Layanan::withCount(['transaksi as total_order' => function ($q) use ($dariDt, $sampaiDt) {
                $q->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt]);
            }])
            ->withSum(['transaksi as total_berat' => function ($q) use ($dariDt, $sampaiDt) {
                $q->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt]);
            }], 'berat')
            ->withSum(['transaksi as total_pendapatan' => function ($q) use ($dariDt, $sampaiDt) {
                $q->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt])->where('status_pembayaran', 'Lunas');
            }], 'total')
            ->get()
            ->filter(fn($l) => $l->total_order > 0)
            ->sortByDesc('total_order')
            ->values();

        return [
            'items'   => $layanan,
            'summary' => [
                'total_layanan'    => $layanan->count(),
                'terlaris'         => $layanan->first()?->nama ?? '-',
                'total_order'      => $layanan->sum('total_order'),
                'total_pendapatan' => $layanan->sum('total_pendapatan'),
            ],
        ];
    }

    private function getChartData(string $dari, string $sampai): array
    {
        $dates = collect();
        $start = \Carbon\Carbon::parse($dari);
        $end   = \Carbon\Carbon::parse($sampai);
        $diff  = $start->diffInDays($end);
        $days  = min($diff + 1, 30);

        for ($i = 0; $i < $days; $i++) {
            $date    = $start->copy()->addDays($i)->toDateString();
            $dariDt  = $date . ' 00:00:00';
            $sampDt  = $date . ' 23:59:59';

            $jumlah = Transaksi::whereBetween('tanggal_masuk', [$dariDt, $sampDt])->count();
            $total  = Transaksi::whereBetween('tanggal_masuk', [$dariDt, $sampDt])
                                ->where('status_pembayaran', 'Lunas')
                                ->sum('total');

            $dates->push([
                'date'   => \Carbon\Carbon::parse($date)->translatedFormat('d M'),
                'jumlah' => $jumlah,
                'total'  => $total,
            ]);
        }
        return $dates->toArray();
    }

    public function exportPdf(Request $request)
    {
        $dari   = $request->dari   ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? now()->toDateString();
        $jenis  = $request->jenis  ?? 'transaksi';

        $dariDt   = $dari . ' 00:00:00';
        $sampaiDt = $sampai . ' 23:59:59';

        $data     = $this->laporanTransaksi($dariDt, $sampaiDt);
        $settings = Pengaturan::getAll();

        $pdf = Pdf::loadView('laporan.pdf', array_merge(
            compact('dari', 'sampai', 'jenis', 'settings'),
            $data
        ))->setPaper('a4', 'landscape');

        return $pdf->download("laporan-laundry-{$dari}-{$sampai}.pdf");
    }

    public function exportCsv(Request $request)
    {
        $dari   = $request->dari   ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? now()->toDateString();

        $dariDt   = $dari . ' 00:00:00';
        $sampaiDt = $sampai . ' 23:59:59';

        $transaksi = Transaksi::with(['pelanggan', 'layanan'])
            ->whereBetween('tanggal_masuk', [$dariDt, $sampaiDt])
            ->orderBy('tanggal_masuk')
            ->get();

        $filename = "laporan-laundry-{$dari}-{$sampai}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($transaksi) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8
            fputcsv($file, ['No. Transaksi','Pelanggan','Layanan','Berat (kg)','Harga/kg','Total (Rp)','Metode','Status Bayar','Status Cucian','Tgl Masuk','Est. Selesai']);
            foreach ($transaksi as $t) {
                $tglMasuk = $t->tanggal_masuk
                    ? (is_string($t->tanggal_masuk) ? date('d/m/Y', strtotime($t->tanggal_masuk)) : $t->tanggal_masuk->format('d/m/Y'))
                    : '-';
                $tglEst = $t->estimasi_selesai
                    ? (is_string($t->estimasi_selesai) ? date('d/m/Y', strtotime($t->estimasi_selesai)) : $t->estimasi_selesai->format('d/m/Y'))
                    : '-';

                fputcsv($file, [
                    $t->no_transaksi,
                    $t->pelanggan?->nama ?? 'Umum',
                    $t->layanan?->nama   ?? '-',
                    $t->berat,
                    $t->harga_per_kg,
                    $t->total,
                    $t->metode_pembayaran,
                    $t->status_pembayaran,
                    $t->status,
                    $tglMasuk,
                    $tglEst,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
