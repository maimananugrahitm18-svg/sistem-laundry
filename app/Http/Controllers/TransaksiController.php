<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Pelanggan;
use App\Models\Layanan;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'layanan', 'kasir'])
                           ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('no_transaksi', 'like', "%$q%")
                   ->orWhereHas('pelanggan', fn($p) => $p->where('nama', 'like', "%$q%"));
            });
        }

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('bayar'))    $query->where('status_pembayaran', $request->bayar);
        if ($request->filled('tanggal'))  $query->whereDate('tanggal_masuk', $request->tanggal);

        $transaksi = $query->paginate(15)->withQueryString();
        $statuses  = Transaksi::STATUSES;

        return view('transaksi.index', compact('transaksi', 'statuses'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::where('aktif', true)->orderBy('nama')->get();
        $layanan   = Layanan::aktif()->get();
        $settings  = Pengaturan::getAll();
        return view('transaksi.create', compact('pelanggan', 'layanan', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id'       => 'required|exists:pelanggan,id',
            'layanan_id'         => 'required|exists:layanan,id',
            'berat'              => 'required|numeric|min:0.1',
            'metode_pembayaran'  => 'required|in:Tunai,Transfer,QRIS',
            'status_pembayaran'  => 'required|in:Lunas,DP,Belum Bayar',
            'estimasi_selesai'   => 'nullable|date',
        ]);

        $layanan   = Layanan::findOrFail($request->layanan_id);
        $subtotal  = $request->berat * $layanan->harga_per_kg;
        $diskon    = $request->diskon ?? 0;
        $total     = $subtotal - $diskon;
        $dp        = $request->status_pembayaran === 'DP' ? ($request->jumlah_dp ?? 0) : 0;
        $sisa      = $request->status_pembayaran === 'DP' ? ($total - $dp) :
                    ($request->status_pembayaran === 'Belum Bayar' ? $total : 0);

        $trx = Transaksi::create([
            'no_transaksi'       => Transaksi::generateNomor(),
            'pelanggan_id'       => $request->pelanggan_id,
            'layanan_id'         => $request->layanan_id,
            'user_id'            => Auth::id(),
            'berat'              => $request->berat,
            'harga_per_kg'       => $layanan->harga_per_kg,
            'subtotal'           => $subtotal,
            'diskon'             => $diskon,
            'total'              => $total,
            'metode_pembayaran'  => $request->metode_pembayaran,
            'status_pembayaran'  => $request->status_pembayaran,
            'jumlah_dp'          => $dp,
            'sisa_bayar'         => $sisa,
            'status'             => 'Baru',
            'tanggal_masuk'      => now()->toDateString(),
            'estimasi_selesai'   => $request->estimasi_selesai,
            'catatan'            => $request->catatan,
        ]);

        return redirect()->route('transaksi.show', $trx)
                         ->with('success', "Transaksi {$trx->no_transaksi} berhasil disimpan!");
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'layanan', 'kasir']);
        $statuses = Transaksi::STATUSES;
        return view('transaksi.show', compact('transaksi', 'statuses'));
    }

    public function edit(Transaksi $transaksi)
    {
        $pelanggan = Pelanggan::where('aktif', true)->orderBy('nama')->get();
        $layanan   = Layanan::aktif()->get();
        $statuses  = Transaksi::STATUSES;
        return view('transaksi.edit', compact('transaksi', 'pelanggan', 'layanan', 'statuses'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'status'             => 'required|in:' . implode(',', Transaksi::STATUSES),
            'status_pembayaran'  => 'required|in:Lunas,DP,Belum Bayar',
            'metode_pembayaran'  => 'required|in:Tunai,Transfer,QRIS',
        ]);

        $data = [
            'status'            => $request->status,
            'status_pembayaran' => $request->status_pembayaran,
            'metode_pembayaran' => $request->metode_pembayaran,
            'catatan'           => $request->catatan,
        ];

        // Hitung total jika berat diubah
        $total = $transaksi->total;
        if ($request->filled('berat') && (float)$request->berat > 0) {
            $berat = (float) $request->berat;
            $subtotal = $berat * $transaksi->harga_per_kg;
            $diskon = $request->filled('diskon') ? (float)$request->diskon : $transaksi->diskon;
            $total = max(0, $subtotal - $diskon);

            $data['berat'] = $berat;
            $data['subtotal'] = $subtotal;
            $data['diskon'] = $diskon;
            $data['total'] = $total;
        }

        // Kalkulasi status pembayaran: Lunas, DP, atau Belum Bayar
        if ($request->status_pembayaran === 'Lunas') {
            $data['jumlah_dp'] = $total;
            $data['sisa_bayar'] = 0;
        } elseif ($request->status_pembayaran === 'DP') {
            $dp = (float) ($request->jumlah_dp ?? 0);
            $data['jumlah_dp'] = $dp;
            $data['sisa_bayar'] = max(0, $total - $dp);
        } else {
            $data['jumlah_dp'] = 0;
            $data['sisa_bayar'] = $total;
        }

        if ($request->status === 'Selesai' && !$transaksi->tanggal_selesai) {
            $data['tanggal_selesai'] = now()->toDateString();
        }

        if ($request->status === 'Dibatalkan') {
            $data['catatan_batal'] = $request->catatan_batal;
        }

        $transaksi->update($data);

        return redirect()->route('transaksi.show', $transaksi)
                         ->with('success', 'Data transaksi berhasil diperbarui!');
    }

    public function destroy(Transaksi $transaksi)
    {
        $no = $transaksi->no_transaksi;
        $transaksi->delete();
        return redirect()->route('transaksi.index')
                         ->with('success', "Transaksi $no berhasil dihapus.");
    }

    public function struk(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'layanan']);
        $settings = Pengaturan::getAll();
        return view('transaksi.struk', compact('transaksi', 'settings'));
    }

    public function cetakStruk(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'layanan']);
        $settings = Pengaturan::getAll();
        $pdf = Pdf::loadView('transaksi.struk-pdf', compact('transaksi', 'settings'))
                  ->setPaper([0, 0, 227, 500], 'portrait'); // 80mm paper
        return $pdf->download("struk-{$transaksi->no_transaksi}.pdf");
    }

    public function updateStatus(Request $request, Transaksi $transaksi)
    {
        $request->validate(['status' => 'required|in:' . implode(',', Transaksi::STATUSES)]);
        $transaksi->update(['status' => $request->status]);
        return back()->with('success', 'Status diperbarui ke: ' . $request->status);
    }

    public function lunasi(Request $request, Transaksi $transaksi)
    {
        $transaksi->update([
            'status_pembayaran' => 'Lunas',
            'sisa_bayar'        => 0,
            'metode_pembayaran' => $request->input('metode_pembayaran', $transaksi->metode_pembayaran),
        ]);

        return back()->with('success', "Pembayaran untuk nota {$transaksi->no_transaksi} berhasil dilunasi!");
    }
}
