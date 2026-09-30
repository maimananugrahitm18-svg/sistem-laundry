<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::withCount('transaksi');
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('nama', 'like', "%$q%")
                   ->orWhere('no_hp', 'like', "%$q%")
                   ->orWhere('kode_pelanggan', 'like', "%$q%");
            });
        }
        $pelanggan = $query->orderBy('nama')->paginate(15)->withQueryString();
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        $pelanggan = Pelanggan::create([
            'kode_pelanggan' => Pelanggan::generateKode(),
            'nama'           => $request->nama,
            'no_hp'          => $request->no_hp,
            'alamat'         => $request->alamat,
            'email'          => $request->email,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'catatan'        => $request->catatan,
        ]);

        return redirect()->route('pelanggan.index')
                         ->with('success', "Pelanggan {$pelanggan->nama} berhasil ditambahkan!");
    }

    public function show(Pelanggan $pelanggan)
    {
        $riwayat = $pelanggan->transaksi()
                             ->with('layanan')
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

        $stats = [
            'total_transaksi' => $pelanggan->transaksi()->count(),
            'total_belanja'   => $pelanggan->transaksi()->where('status_pembayaran', 'Lunas')->sum('total'),
            'total_berat'     => $pelanggan->transaksi()->sum('berat'),
            'terakhir_masuk'  => $pelanggan->transaksi()->orderBy('tanggal_masuk', 'desc')->value('tanggal_masuk'),
        ];

        return view('pelanggan.show', compact('pelanggan', 'riwayat', 'stats'));
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama'  => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);

        $pelanggan->update($request->only(['nama','no_hp','alamat','email','jenis_kelamin','catatan']));

        return redirect()->route('pelanggan.index')
                         ->with('success', 'Data pelanggan berhasil diperbarui!');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $nama = $pelanggan->nama;
        $pelanggan->transaksi()->delete();
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
                         ->with('success', "Data pelanggan {$nama} berhasil dihapus dari sistem!");
    }

    public function search(Request $request)
    {
        // AJAX: untuk dropdown transaksi
        $pelanggan = Pelanggan::where('aktif', true)
                              ->where('nama', 'like', '%' . $request->q . '%')
                              ->limit(10)->get(['id','nama','no_hp','alamat']);
        return response()->json($pelanggan);
    }
}
