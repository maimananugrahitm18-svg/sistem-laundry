<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk {{ $transaksi->no_transaksi }} - {{ $settings['nama_laundry'] ?? 'LaundryPro' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: #f1f5f9;
      font-family: 'Courier Prime', monospace;
      color: #000;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px;
      min-height: 100vh;
    }

    .no-print-bar {
      margin-bottom: 20px;
      display: flex;
      gap: 10px;
    }

    .btn {
      font-family: 'Plus Jakarta Sans', sans-serif;
      padding: 8px 18px;
      border-radius: 8px;
      border: none;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-size: 0.9rem;
    }
    .btn-print { background: #4f46e5; color: #fff; }
    .btn-back { background: #e2e8f0; color: #334155; }
    .btn-pdf { background: #dc2626; color: #fff; }

    /* Thermal receipt container: standard 80mm / 58mm POS */
    .receipt {
      width: 320px;
      background: #fff;
      padding: 20px 18px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      font-size: 13px;
      line-height: 1.4;
    }

    .text-center { text-align: center; }
    .text-end { text-align: right; }
    .fw-bold { font-weight: bold; }
    .dashed { border-bottom: 1px dashed #000; margin: 10px 0; }
    .solid { border-bottom: 1px solid #000; margin: 10px 0; }

    .header h2 {
      font-size: 18px;
      font-weight: 700;
      letter-spacing: 0.5px;
      margin-bottom: 3px;
    }
    .header p {
      font-size: 11px;
      color: #333;
    }

    .row-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 4px;
    }

    .total-highlight {
      font-size: 15px;
      font-weight: bold;
    }

    .terms {
      font-size: 10px;
      line-height: 1.3;
      margin-top: 10px;
    }

    @media print {
      body {
        background: transparent;
        padding: 0;
      }
      .no-print-bar {
        display: none !important;
      }
      .receipt {
        width: 100%;
        max-width: 80mm;
        box-shadow: none;
        padding: 10px 5px;
      }
    }
  </style>
</head>
<body>

  <!-- Action Bar -->
  <div class="no-print-bar">
    <button class="btn btn-print" onclick="window.print()">
      <i class="bi bi-printer"></i> Cetak Struk (Thermal)
    </button>
    <a href="{{ route('transaksi.cetak-struk', $transaksi) }}" class="btn btn-pdf">
      <i class="bi bi-file-earmark-pdf"></i> Download PDF
    </a>
    <a href="{{ route('transaksi.show', $transaksi) }}" class="btn btn-back">
      <i class="bi bi-arrow-left"></i> Kembali
    </a>
  </div>

  <!-- Printable Receipt Sheet -->
  <div class="receipt">
    <div class="header text-center">
      <h2>{{ $settings['nama_laundry'] ?? 'LAUNDRYPRO' }}</h2>
      <p>{{ $settings['slogan'] ?? 'Bersih, Wangi, Rapi, & Terpercaya' }}</p>
      <p>{{ $settings['alamat'] ?? 'Jl. Merdeka No. 45' }}</p>
      <p>Telp / WA: {{ $settings['telepon'] ?? '0812-3456-7890' }}</p>
    </div>

    <div class="dashed"></div>

    <div class="row-item">
      <span>No. Nota:</span>
      <span class="fw-bold">{{ $transaksi->no_transaksi }}</span>
    </div>
    <div class="row-item">
      <span>Tgl Masuk:</span>
      <span>{{ $transaksi->tanggal_masuk->format('d/m/Y') }}</span>
    </div>
    <div class="row-item">
      <span>Pelanggan:</span>
      <span class="fw-bold">{{ $transaksi->pelanggan->nama ?? '-' }}</span>
    </div>
    <div class="row-item">
      <span>No. HP:</span>
      <span>{{ $transaksi->pelanggan->no_hp ?? '-' }}</span>
    </div>
    <div class="row-item">
      <span>Est. Selesai:</span>
      <span>{{ $transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('d/m/Y') : '-' }}</span>
    </div>
    <div class="row-item">
      <span>Kasir:</span>
      <span>{{ $transaksi->kasir->name ?? 'Kasir' }}</span>
    </div>

    <div class="solid"></div>

    <!-- Items -->
    <div style="margin-bottom: 6px;">
      <div class="fw-bold">{{ $transaksi->layanan->nama ?? 'Layanan' }}</div>
      <div class="row-item">
        <span>{{ $transaksi->berat }} kg x Rp {{ number_format($transaksi->harga_per_kg, 0, ',', '.') }}</span>
        <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
      </div>
    </div>

    @if($transaksi->diskon > 0)
      <div class="row-item">
        <span>Diskon:</span>
        <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
      </div>
    @endif

    <div class="dashed"></div>

    <div class="row-item total-highlight">
      <span>TOTAL:</span>
      <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
    </div>

    <div class="row-item">
      <span>Metode Bayar:</span>
      <span>{{ $transaksi->metode_pembayaran }}</span>
    </div>

    <div class="row-item">
      <span>Status Bayar:</span>
      <span class="fw-bold">*** {{ strtoupper($transaksi->status_pembayaran) }} ***</span>
    </div>

    @if($transaksi->status_pembayaran === 'DP')
      <div class="row-item">
        <span>Sudah Bayar (DP):</span>
        <span>Rp {{ number_format($transaksi->jumlah_dp, 0, ',', '.') }}</span>
      </div>
      <div class="row-item fw-bold">
        <span>Sisa Tagihan:</span>
        <span>Rp {{ number_format($transaksi->sisa_bayar, 0, ',', '.') }}</span>
      </div>
    @endif

    @if($transaksi->catatan)
      <div class="dashed"></div>
      <div style="font-size: 11px;">
        <strong>Catatan:</strong> {{ $transaksi->catatan }}
      </div>
    @endif

    <div class="solid"></div>

    <div class="text-center terms">
      <p class="fw-bold">{{ $settings['footer_struk'] ?? 'Terima kasih atas kunjungan Anda!' }}</p>
      <p style="margin-top: 6px;">* Pengambilan cucian wajib membawa struk ini *</p>
      <p>* Komplain maksimal 1x24 jam setelah cucian diambil *</p>
    </div>
  </div>

</body>
</html>
