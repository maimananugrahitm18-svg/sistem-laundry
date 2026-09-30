@extends('layouts.app')

@section('title', 'Laporan Keuangan & Operasional')
@section('page-title', 'Laporan Laundry')

@section('content')
<!-- Filter Bar -->
<div class="card card-custom p-4 mb-4">
  <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end">
    <div class="col-md-3">
      <label class="form-label fw-semibold small">Dari Tanggal</label>
      <input type="date" name="dari" class="form-control" value="{{ $dari }}">
    </div>
    <div class="col-md-3">
      <label class="form-label fw-semibold small">Sampai Tanggal</label>
      <input type="date" name="sampai" class="form-control" value="{{ $sampai }}">
    </div>
    <div class="col-md-3">
      <label class="form-label fw-semibold small">Jenis Laporan</label>
      <select name="jenis" class="form-select">
        <option value="transaksi" {{ $jenis == 'transaksi' ? 'selected' : '' }}>📋 Daftar Semua Transaksi</option>
        <option value="pendapatan" {{ $jenis == 'pendapatan' ? 'selected' : '' }}>💰 Ringkasan Pendapatan Harian</option>
        <option value="layanan" {{ $jenis == 'layanan' ? 'selected' : '' }}>👕 Performa Paket Layanan</option>
        <option value="pelanggan" {{ $jenis == 'pelanggan' ? 'selected' : '' }}>👥 Pelanggan Paling Aktif</option>
      </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary-custom w-100">
        <i class="bi bi-filter"></i> Filter Laporan
      </button>
    </div>
  </form>

  <div class="d-flex flex-wrap justify-content-end gap-2 mt-3 pt-3 border-top">
    <a href="{{ route('laporan.csv', ['dari' => $dari, 'sampai' => $sampai]) }}" class="btn btn-success d-inline-flex align-items-center gap-2 fw-semibold px-3 py-2 shadow-sm">
      <i class="bi bi-file-earmark-excel-fill fs-5"></i>
      <span>Download Excel (CSV)</span>
    </a>
    <a href="{{ route('laporan.pdf', ['dari' => $dari, 'sampai' => $sampai, 'jenis' => $jenis]) }}" target="_blank" class="btn btn-danger d-inline-flex align-items-center gap-2 fw-semibold px-3 py-2 shadow-sm">
      <i class="bi bi-printer-fill fs-5"></i>
      <span>Cetak Laporan / PDF</span>
    </a>
  </div>
</div>

<!-- Summary KPI Cards - Dinamis sesuai jenis laporan -->
@if(isset($summary))
<div class="row g-3 mb-4">

  @if($jenis === 'transaksi')
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Total Transaksi</small>
        <h3 class="fw-bold mb-0 text-primary">{{ $summary['total_trx'] ?? 0 }}</h3>
        <small class="text-muted">{{ number_format($summary['total_berat'] ?? 0, 1) }} kg cucian</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Total Pemasukan Lunas</small>
        <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($summary['total_lunas'] ?? 0, 0, ',', '.') }}</h3>
        <small class="text-success"><i class="bi bi-check-circle me-1"></i>Sudah diterima</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Uang Muka (DP)</small>
        <h3 class="fw-bold mb-0 text-warning">Rp {{ number_format($summary['total_dp'] ?? 0, 0, ',', '.') }}</h3>
        <small class="text-muted">Sebagian diterima</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Pesanan Belum Bayar</small>
        <h3 class="fw-bold mb-0 text-danger">{{ $summary['total_belum'] ?? 0 }}</h3>
        <small class="text-danger">Menunggu pelunasan</small>
      </div>
    </div>

  @elseif($jenis === 'pendapatan')
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Total Pendapatan (Lunas)</small>
        <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($summary['total_pendapatan'] ?? 0, 0, ',', '.') }}</h3>
        <small class="text-muted">Periode terpilih</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Total Transaksi Lunas</small>
        <h3 class="fw-bold mb-0 text-primary">{{ $summary['total_trx'] ?? 0 }}</h3>
        <small class="text-muted">Order selesai bayar</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Hari Aktif</small>
        <h3 class="fw-bold mb-0 text-info">{{ $summary['hari_aktif'] ?? 0 }}</h3>
        <small class="text-muted">Hari ada pemasukan</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Rata-rata / Hari</small>
        <h3 class="fw-bold mb-0 text-warning">Rp {{ number_format($summary['rata_harian'] ?? 0, 0, ',', '.') }}</h3>
        <small class="text-muted">Rata pendapatan harian</small>
      </div>
    </div>

  @elseif($jenis === 'layanan')
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Layanan Aktif Dipakai</small>
        <h3 class="fw-bold mb-0 text-primary">{{ $summary['total_layanan'] ?? 0 }}</h3>
        <small class="text-muted">Paket dalam periode ini</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Layanan Terlaris</small>
        <h3 class="fw-bold mb-0 text-success" style="font-size:1rem;">{{ $summary['terlaris'] ?? '-' }}</h3>
        <small class="text-muted">Paling banyak dipesan</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Total Order</small>
        <h3 class="fw-bold mb-0 text-info">{{ $summary['total_order'] ?? 0 }}x</h3>
        <small class="text-muted">Semua layanan</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Total Omset Layanan</small>
        <h3 class="fw-bold mb-0 text-warning">Rp {{ number_format($summary['total_pendapatan'] ?? 0, 0, ',', '.') }}</h3>
        <small class="text-muted">Hanya yang lunas</small>
      </div>
    </div>

  @elseif($jenis === 'pelanggan')
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Pelanggan Aktif</small>
        <h3 class="fw-bold mb-0 text-primary">{{ $summary['total_pelanggan'] ?? 0 }}</h3>
        <small class="text-muted">Ada transaksi periode ini</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Pelanggan Baru</small>
        <h3 class="fw-bold mb-0 text-info">{{ $summary['pelanggan_baru'] ?? 0 }}</h3>
        <small class="text-muted">Daftar di periode ini</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Pelanggan Terbaik</small>
        <h3 class="fw-bold mb-0 text-success" style="font-size:1rem;">{{ $summary['top_pelanggan'] ?? '-' }}</h3>
        <small class="text-muted">Belanja terbanyak</small>
      </div>
    </div>
    <div class="col-md-3">
      <div class="p-3 bg-white border rounded-3 text-center">
        <small class="text-muted">Total Belanja (Lunas)</small>
        <h3 class="fw-bold mb-0 text-warning">Rp {{ number_format($summary['total_belanja'] ?? 0, 0, ',', '.') }}</h3>
        <small class="text-muted">Semua pelanggan</small>
      </div>
    </div>
  @endif

</div>
@endif

<!-- Report Data Table -->
<div class="card card-custom p-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">
      Data Laporan Periode {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
    </h5>
    <span class="badge bg-light text-dark border">
      {{ count($items ?? []) }} Baris Data
    </span>
  </div>

  <div class="table-responsive">
    @if($jenis === 'pendapatan')
      <!-- Table Pendapatan Harian -->
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Tanggal</th>
            <th>Jumlah Transaksi</th>
            <th>Total Pendapatan (Lunas)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $row)
            <tr>
              <td class="fw-semibold">{{ \Carbon\Carbon::parse($row->tanggal_masuk)->format('d F Y') }}</td>
              <td>{{ $row->jumlah_transaksi ?? '-' }} transaksi</td>
              <td class="fw-bold text-success">Rp {{ number_format($row->total_pendapatan ?? 0, 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center py-4 text-muted">Tidak ada data untuk periode ini.</td></tr>
          @endforelse
        </tbody>
      </table>

    @elseif($jenis === 'layanan')
      <!-- Table Performa Layanan -->
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Paket Layanan</th>
            <th>Harga Saat Ini</th>
            <th>Total Dipesan</th>
            <th>Total Berat Cucian</th>
            <th>Total Omset</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $row)
            <tr>
              <td class="fw-semibold">{{ $row->nama }}</td>
              <td>Rp {{ number_format($row->harga_per_kg, 0, ',', '.') }}/kg</td>
              <td><span class="badge bg-primary rounded-pill">{{ $row->total_order ?? 0 }}x</span></td>
              <td>{{ number_format($row->total_berat ?? 0, 1) }} kg</td>
              <td class="fw-bold text-success">Rp {{ number_format($row->total_pendapatan ?? 0, 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data untuk periode ini.</td></tr>
          @endforelse
        </tbody>
      </table>

    @elseif($jenis === 'pelanggan')
      <!-- Table Pelanggan Teraktif -->
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nama Pelanggan</th>
            <th>No. Telepon</th>
            <th>Total Transaksi</th>
            <th>Total Belanja</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $row)
            <tr>
              <td class="font-monospace text-primary">{{ $row->kode_pelanggan }}</td>
              <td class="fw-semibold">{{ $row->nama }}</td>
              <td>{{ $row->no_hp }}</td>
              <td><span class="badge bg-info text-dark rounded-pill">{{ $row->total_trx ?? 0 }}x cuci</span></td>
              <td class="fw-bold text-success">Rp {{ number_format($row->total_belanja ?? 0, 0, ',', '.') }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">Tidak ada data untuk periode ini.</td></tr>
          @endforelse
        </tbody>
      </table>

    @else
      <!-- Table Default: Semua Transaksi -->
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>No. Transaksi</th>
            <th>Tgl Masuk</th>
            <th>Pelanggan</th>
            <th>Layanan</th>
            <th>Berat</th>
            <th>Total Tagihan</th>
            <th>Pembayaran</th>
            <th>Status Cucian</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $t)
            <tr>
              <td>
                <a href="{{ route('transaksi.show', $t) }}" class="fw-bold text-primary text-decoration-none">
                  {{ $t->no_transaksi }}
                </a>
              </td>
              <td>{{ $t->tanggal_masuk->format('d/m/Y') }}</td>
              <td>{{ $t->pelanggan->nama ?? '-' }}</td>
              <td>{{ $t->layanan->nama ?? '-' }}</td>
              <td>{{ $t->berat }} kg</td>
              <td class="fw-bold text-dark">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
              <td>
                @if($t->status_pembayaran === 'Lunas')
                  <span class="badge badge-status badge-lunas">Lunas</span>
                @elseif($t->status_pembayaran === 'DP')
                  <span class="badge badge-status badge-dp">DP: Rp {{ number_format($t->jumlah_dp, 0, ',', '.') }}</span>
                @else
                  <span class="badge badge-status badge-belum-bayar">Belum Bayar</span>
                @endif
              </td>
              <td>
                <span class="badge badge-status badge-{{ Str::slug($t->status) }}">
                  {{ $t->status }}
                </span>
              </td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada transaksi pada rentang tanggal ini.</td></tr>
          @endforelse
        </tbody>
      </table>
    @endif
  </div>
</div>
@endsection
