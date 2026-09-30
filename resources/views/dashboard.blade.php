@extends('layouts.app')

@section('title', auth()->user()->isOwner() ? 'Dashboard Omset & Operasional' : 'Loket Kasir Laundry')
@section('page-title', auth()->user()->isOwner() ? 'Ringkasan Omset & Operasional Bisnis' : 'Loket Pelayanan Kasir & Pesanan')

@section('content')
<!-- Kasir Quick Action Banner -->
@if(!auth()->user()->isOwner())
  <div class="card card-custom p-4 mb-4" style="background: linear-gradient(135deg, #4f46e5, #3b82f6); color: white;">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div>
        <h4 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}! 👋</h4>
        <p class="mb-0 opacity-90">Siap melayani pelanggan cuci hari ini? Catat pelanggan baru atau timbang cucian dengan cepat.</p>
      </div>
      <a href="{{ route('transaksi.create') }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
        <i class="bi bi-plus-circle-fill me-1"></i> Input Cucian Baru Sekarang
      </a>
    </div>
  </div>
@endif

<div class="row g-3 mb-4">
  @if(auth()->user()->isOwner())
    <!-- KHUSUS OWNER: Omset Keuangan -->
    <div class="col-xl-3 col-md-6">
      <div class="stat-card">
        <div>
          <span class="text-muted fw-semibold small">Pendapatan Hari Ini</span>
          <h3 class="fw-bold mt-1 mb-0 text-success">Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}</h3>
          <small class="text-muted"><i class="bi bi-calendar-check text-success me-1"></i> Bulan ini: Rp {{ number_format($stats['pendapatan_bulan'], 0, ',', '.') }}</small>
        </div>
        <div class="stat-icon bg-success-subtle text-success">
          <i class="bi bi-cash-stack"></i>
        </div>
      </div>
    </div>
  @endif

  <!-- Pesanan Masuk (Baru) -->
  <div class="{{ auth()->user()->isOwner() ? 'col-xl-3 col-md-6' : 'col-md-4' }}">
    <div class="stat-card">
      <div>
        <span class="text-muted fw-semibold small">Pesanan Masuk</span>
        <h3 class="fw-bold mt-1 mb-0 text-primary">{{ $stats['pesanan_masuk'] }} <span class="fs-6 fw-normal text-muted">antrean</span></h3>
        <small class="text-muted"><i class="bi bi-inbox text-primary me-1"></i> Menunggu diproses</small>
      </div>
      <div class="stat-icon bg-primary-subtle text-primary">
        <i class="bi bi-inbox-fill"></i>
      </div>
    </div>
  </div>

  <!-- Sedang Dicuci / Diproses -->
  <div class="{{ auth()->user()->isOwner() ? 'col-xl-3 col-md-6' : 'col-md-4' }}">
    <div class="stat-card">
      <div>
        <span class="text-muted fw-semibold small">Sedang Dicuci / Proses</span>
        <h3 class="fw-bold mt-1 mb-0 text-warning">{{ $stats['sedang_proses'] }} <span class="fs-6 fw-normal text-muted">cucian</span></h3>
        <small class="text-muted"><i class="bi bi-arrow-repeat text-warning me-1"></i> Dalam mesin & setrika</small>
      </div>
      <div class="stat-icon bg-warning-subtle text-warning">
        <i class="bi bi-water"></i>
      </div>
    </div>
  </div>

  <!-- Siap Diambil -->
  <div class="{{ auth()->user()->isOwner() ? 'col-xl-3 col-md-6' : 'col-md-4' }}">
    <div class="stat-card">
      <div>
        <span class="text-muted fw-semibold small">Siap Diambil</span>
        <h3 class="fw-bold mt-1 mb-0 text-info">{{ $stats['siap_diambil'] }} <span class="fs-6 fw-normal text-muted">paket</span></h3>
        <small class="text-muted"><i class="bi bi-bell-fill text-info me-1"></i> Hubungi pelanggan via WA</small>
      </div>
      <div class="stat-icon bg-info-subtle text-info">
        <i class="bi bi-bag-check-fill"></i>
      </div>
    </div>
  </div>
</div>

@if(auth()->user()->isOwner())
  <!-- Secondary Stats Row (Khusus Owner) -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between">
        <div>
          <div class="small text-muted">Total Transaksi Hari Ini</div>
          <div class="h4 mb-0 fw-bold">{{ $stats['total_hari_ini'] }} Transaksi</div>
        </div>
        <span class="badge bg-secondary rounded-pill p-2"><i class="bi bi-receipt fs-5"></i></span>
      </div>
    </div>

    <div class="col-md-4">
      <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between">
        <div>
          <div class="small text-muted">Cucian Selesai Hari Ini</div>
          <div class="h4 mb-0 fw-bold text-success">{{ $stats['selesai_hari_ini'] }} Selesai</div>
        </div>
        <span class="badge bg-success rounded-pill p-2"><i class="bi bi-check2-all fs-5"></i></span>
      </div>
    </div>

    <div class="col-md-4">
      <div class="p-3 bg-white border rounded-3 d-flex align-items-center justify-content-between">
        <div>
          <div class="small text-muted">Pelanggan Terdaftar</div>
          <div class="h4 mb-0 fw-bold text-primary">{{ $stats['total_pelanggan'] }} Orang</div>
        </div>
        <span class="badge bg-primary rounded-pill p-2"><i class="bi bi-people fs-5"></i></span>
      </div>
    </div>
  </div>

  <!-- Grafik Pendapatan 7 Hari & Layanan Terlaris (Khusus Owner) -->
  <div class="row g-4 mb-4">
    <div class="col-lg-8">
      <div class="card card-custom p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h5 class="fw-bold mb-0">Tren Omset Pendapatan 7 Hari Terakhir</h5>
            <small class="text-muted">Grafik omset harian transaksi lunas</small>
          </div>
          <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-primary">
            Lihat Laporan Lengkap <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        <div style="height: 280px; position: relative;">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card card-custom p-4 h-100">
        <h5 class="fw-bold mb-1">Paket Terpopuler</h5>
        <p class="text-muted small mb-3">Layanan paling diminati pelanggan</p>

        <div class="d-flex flex-column gap-3">
          @forelse($layananTerlaris as $item)
            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
              <div class="d-flex align-items-center gap-3">
                <span class="fs-4">{{ $item->ikon }}</span>
                <div>
                  <div class="fw-semibold text-dark">{{ $item->nama }}</div>
                  <div class="text-muted small">Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}/kg</div>
                </div>
              </div>
              <span class="badge bg-primary rounded-pill px-3 py-1">
                {{ $item->transaksi_count }}x dipesan
              </span>
            </div>
          @empty
            <div class="text-center text-muted py-4">Belum ada pesanan</div>
          @endforelse
        </div>

        <div class="mt-auto pt-3 border-top">
          <a href="{{ route('layanan.index') }}" class="btn btn-outline-primary btn-sm w-100">
            Kelola Tarif & Layanan <i class="bi bi-arrow-right ms-1"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
@endif

<!-- Transaksi Terbaru Table -->
<div class="card card-custom p-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
      <h5 class="fw-bold mb-0">Aktivitas Cucian Terkini</h5>
      <small class="text-muted">Daftar pesanan cucian yang sedang aktif di loket</small>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('transaksi.create') }}" class="btn btn-primary-custom btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Pesanan Baru
      </a>
      <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary btn-sm">
        Lihat Semua <i class="bi bi-chevron-right ms-1"></i>
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>No. Nota</th>
          <th>Pelanggan</th>
          <th>Layanan</th>
          <th>Berat</th>
          @if(auth()->user()->isOwner())
            <th>Total Tagihan</th>
            <th>Pembayaran</th>
          @else
            <th>Status Bayar</th>
          @endif
          <th>Status Cucian</th>
          <th class="text-end">Aksi Kasir</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentTrx as $trx)
          <tr>
            <td>
              <a href="{{ route('transaksi.show', $trx) }}" class="fw-bold text-primary text-decoration-none">
                {{ $trx->no_transaksi }}
              </a>
              <div class="text-muted small">{{ $trx->tanggal_masuk->format('d M Y') }}</div>
            </td>
            <td>
              <div class="fw-semibold">{{ $trx->pelanggan->nama ?? 'Umum' }}</div>
              <small class="text-muted">{{ $trx->pelanggan->no_hp ?? '-' }}</small>
            </td>
            <td>
              <span>{{ $trx->layanan->ikon ?? '👕' }}</span>
              <span class="ms-1">{{ $trx->layanan->nama ?? 'Layanan' }}</span>
            </td>
            <td><span class="badge bg-light text-dark border px-2 py-1 fw-bold">{{ $trx->berat }} kg</span></td>
            @if(auth()->user()->isOwner())
              <td class="fw-bold text-dark">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
            @endif
            <td>
              @if($trx->status_pembayaran === 'Lunas')
                <span class="badge badge-status badge-lunas"><i class="bi bi-check-circle"></i> Lunas</span>
              @elseif($trx->status_pembayaran === 'DP')
                <span class="badge badge-status badge-dp"><i class="bi bi-hourglass-split"></i> DP Rp {{ number_format($trx->jumlah_dp, 0, ',', '.') }}</span>
              @else
                <span class="badge badge-status badge-belum-bayar"><i class="bi bi-x-circle"></i> Belum Bayar</span>
              @endif
              <div class="small text-muted mt-1">{{ $trx->metode_pembayaran }}</div>
            </td>
            <td>
              <span class="badge badge-status badge-{{ Str::slug($trx->status) }}">
                {{ $trx->status_icon }} {{ $trx->status }}
              </span>
            </td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('transaksi.struk', $trx) }}" class="btn btn-light border" title="Cetak Struk">
                  <i class="bi bi-printer"></i>
                </a>
                @if($trx->pelanggan && $trx->pelanggan->no_hp)
                  @php
                    $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $trx->pelanggan->no_hp));
                    $pesanWa = urlencode("Halo {$trx->pelanggan->nama}, pesanan laundry Anda No: {$trx->no_transaksi} ({$trx->layanan->nama}) seberat {$trx->berat}kg status saat ini: {$trx->status}. Total: Rp " . number_format($trx->total, 0, ',', '.') . " ({$trx->status_pembayaran}). Terima kasih!");
                  @endphp
                  <a href="https://wa.me/{{ $cleanPhone }}?text={{ $pesanWa }}" target="_blank" class="btn btn-light border text-success" title="WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                  </a>
                @endif
                <a href="{{ route('transaksi.show', $trx) }}" class="btn btn-light border" title="Detail">
                  <i class="bi bi-eye"></i>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="text-center py-4 text-muted">Belum ada transaksi</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

@push('scripts')
@if(auth()->user()->isOwner())
<script>
  const ctx = document.getElementById('revenueChart');
  if (ctx) {
    const chartLabels = {!! json_encode(array_column($chartData, 'label')) !!};
    const chartTotals = {!! json_encode(array_column($chartData, 'total')) !!};

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: chartLabels,
        datasets: [{
          label: 'Pendapatan (Rp)',
          data: chartTotals,
          borderColor: '#4f46e5',
          backgroundColor: 'rgba(79, 70, 229, 0.08)',
          borderWidth: 3,
          fill: true,
          tension: 0.35,
          pointBackgroundColor: '#4f46e5',
          pointRadius: 5,
          pointHoverRadius: 7
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'Rp ' + (value / 1000) + 'k';
              }
            },
            grid: { color: '#f1f5f9' }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    });
  }
</script>
@endif
@endpush
