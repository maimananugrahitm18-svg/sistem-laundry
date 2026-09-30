<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Laporan Laundry ({{ $dari }} - {{ $sampai }})</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #333;
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #333;
      padding-bottom: 10px;
    }
    .header h2 { margin: 0 0 5px 0; color: #1e1b4b; }
    .header p { margin: 0; font-size: 10px; color: #666; }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 6px 8px;
      text-align: left;
    }
    th {
      background-color: #f1f5f9;
      font-weight: bold;
    }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .summary-box {
      margin-top: 15px;
      width: 300px;
      float: right;
    }
    .summary-box td {
      border: none;
      padding: 3px 0;
    }
  </style>
</head>
<body>
  <div class="header">
    <h2>{{ $settings['nama_laundry'] ?? 'LAUNDRYPRO' }}</h2>
    <p>{{ $settings['alamat'] ?? 'Jl. Merdeka No. 45' }} &bull; Telp: {{ $settings['telepon'] ?? '0812-3456-7890' }}</p>
    <h3 style="margin: 10px 0 0 0;">LAPORAN TRANSAKSI LAUNDRY</h3>
    <p>Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}</p>
  </div>

  <table>
    <thead>
      <tr>
        <th width="5%">No</th>
        <th>No. Transaksi</th>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Layanan</th>
        <th class="text-right">Berat</th>
        <th class="text-right">Total (Rp)</th>
        <th class="text-center">Pembayaran</th>
        <th class="text-center">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $idx => $t)
        <tr>
          <td class="text-center">{{ $idx + 1 }}</td>
          <td><strong>{{ $t->no_transaksi }}</strong></td>
          <td>{{ $t->tanggal_masuk->format('d/m/Y') }}</td>
          <td>{{ $t->pelanggan->nama ?? '-' }}</td>
          <td>{{ $t->layanan->nama ?? '-' }}</td>
          <td class="text-right">{{ $t->berat }} kg</td>
          <td class="text-right">{{ number_format($t->total, 0, ',', '.') }}</td>
          <td class="text-center">{{ $t->status_pembayaran }}</td>
          <td class="text-center">{{ $t->status }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="9" class="text-center">Tidak ada data transaksi.</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if(isset($summary))
  <div class="summary-box">
    <table>
      <tr>
        <td><strong>Total Transaksi</strong></td>
        <td class="text-right">: {{ $summary['total_trx'] }} trx</td>
      </tr>
      <tr>
        <td><strong>Total Berat Cucian</strong></td>
        <td class="text-right">: {{ $summary['total_berat'] }} kg</td>
      </tr>
      <tr>
        <td><strong>Total Pemasukan Lunas</strong></td>
        <td class="text-right">: <strong>Rp {{ number_format($summary['total_lunas'], 0, ',', '.') }}</strong></td>
      </tr>
    </table>
  </div>
  @endif

  <div style="clear: both; margin-top: 40px; font-size: 10px; color: #666;">
    Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} &bull; Sistem Manajemen LaundryPro
  </div>
</body>
</html>
