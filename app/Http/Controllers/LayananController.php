<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::withCount('transaksi')->orderBy('urutan')->get();
        return view('layanan.index', compact('layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'         => 'required|string|max:100',
            'harga_per_kg' => 'required|numeric|min:0',
            'estimasi_hari'=> 'required|integer|min:1|max:30',
        ]);

        Layanan::create([
            'nama'          => $request->nama,
            'ikon'          => $request->ikon ?? '👕',
            'harga_per_kg'  => $request->harga_per_kg,
            'estimasi_hari' => $request->estimasi_hari,
            'deskripsi'     => $request->deskripsi,
            'status'        => $request->status ?? 'aktif',
            'urutan'        => Layanan::max('urutan') + 1,
        ]);

        return redirect()->route('layanan.index')
                         ->with('success', 'Layanan baru berhasil ditambahkan!');
    }

    public function update(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama'         => 'required|string|max:100',
            'harga_per_kg' => 'required|numeric|min:0',
            'estimasi_hari'=> 'required|integer|min:1|max:30',
        ]);

        $layanan->update([
            'nama'          => $request->nama,
            'ikon'          => $request->ikon ?? $layanan->ikon,
            'harga_per_kg'  => $request->harga_per_kg,
            'estimasi_hari' => $request->estimasi_hari,
            'deskripsi'     => $request->deskripsi,
            'status'        => $request->status,
        ]);

        return redirect()->route('layanan.index')
                         ->with('success', 'Layanan berhasil diperbarui!');
    }

    public function destroy(Layanan $layanan)
    {
        if ($layanan->transaksi()->exists()) {
            return back()->with('error', 'Layanan tidak dapat dihapus karena sudah digunakan dalam transaksi!');
        }
        $layanan->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    public function toggleStatus(Layanan $layanan)
    {
        $layanan->update([
            'status' => $layanan->status === 'aktif' ? 'nonaktif' : 'aktif'
        ]);
        return back()->with('success', 'Status layanan diperbarui!');
    }
}
