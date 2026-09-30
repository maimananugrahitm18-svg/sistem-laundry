<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Struk {{ $transaksi->no_transaksi }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #000;
      margin: 0;
      padding: 10px;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .fw-bold { font-weight: bold; }
    .dashed { border-bottom: 1px dashed #444; margin: 8px 0; }
    .solid { border-bottom: 1px solid #000; margin: 8px 0; }
    table { width: 100%; border-collapse: collapse; }
    td { padding: 2px 0; }
  </style>
</head>
<body>
  <div class="text-center">
    <h3 style="margin: 0 0 4px 0;">{{ $settings['nama_laundry'] ?? 'LAUNDRYPRO' }}</h3>
    <div style="font-size: 9px; color: #555;">{{ $settings['alamat'] ?? 'Jl. Merdeka No. 45' }}</div>
    <div style="font-size: 9px; color: #555;">Telp: {{ $settings['telepon'] ?? '0812-3456-7890' }}</div>
  </div>

  <div class="dashed"></div>

  <table>
    <tr><td>No Nota</td><td>: <strong>{{ $transaksi->no_transaksi }}</strong></td></tr>
    <tr><td>Tanggal</td><td>: {{ $transaksi->tanggal_masuk->format('d/m/Y') }}</td></tr>
    <tr><td>Pelanggan</td><td>: <strong>{{ $transaksi->pelanggan->nama ?? '-' }}</strong></td></tr>
    <tr><td>No HP</td><td>: {{ $transaksi->pelanggan->no_hp ?? '-' }}</td></tr>
    <tr><td>Est. Selesai</td><td>: {{ $transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('d/m/Y') : '-' }}</td></tr>
  </table>

  <div class="solid"></div>

  <table>
    <tr>
      <td colspan="2"><strong>{{ $transaksi->layanan->nama ?? 'Layanan' }}</strong></td>
    </tr>
    <tr>
      <td>{{ $transaksi->berat }} kg x Rp {{ number_format($transaksi->harga_per_kg, 0, ',', '.') }}</td>
      <td class="text-right">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</td>
    </tr>
    @if($transaksi->diskon > 0)
    <tr>
      <td>Potongan Diskon</td>
      <td class="text-right">- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</td>
    </tr>
    @endif
  </table>

  <div class="dashed"></div>

  <table>
    <tr>
      <td><strong>TOTAL TAGIHAN</strong></td>
      <td class="text-right"><strong>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong></td>
    </tr>
    <tr>
      <td>Metode Pembayaran</td>
      <td class="text-right">{{ $transaksi->metode_pembayaran }}</td>
    </tr>
    <tr>
      <td>Status Pembayaran</td>
      <td class="text-right"><strong>{{ strtoupper($transaksi->status_pembayaran) }}</strong></td>
    </tr>
    @if($transaksi->status_pembayaran === 'DP')
    <tr>
      <td>Uang Muka (DP)</td>
      <td class="text-right">Rp {{ number_format($transaksi->jumlah_dp, 0, ',', '.') }}</td>
    </tr>
    <tr>
      <td><strong>Sisa Pembayaran</strong></td>
      <td class="text-right"><strong>Rp {{ number_format($transaksi->sisa_bayar, 0, ',', '.') }}</strong></td>
    </tr>
    @endif
  </table>

  <div class="solid"></div>

  <div class="text-center" style="font-size: 9px; margin-top: 10px;">
    <div><strong>{{ $settings['footer_struk'] ?? 'Terima kasih atas kepercayaan Anda!' }}</strong></div>
    <div style="margin-top: 4px;">* Harap simpan struk untuk pengambilan *</div>
  </div>
</body>
</html>
