@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $transaksi->no_transaksi)
@section('page-title', 'Detail Pesanan #' . $transaksi->no_transaksi)

@section('content')
<div class="row g-4">
  <!-- Status Stepper Bar -->
  <div class="col-12">
    <div class="card card-custom p-4">
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
          <span class="text-muted small">Status Transaksi Saat Ini:</span>
          <h4 class="fw-bold mb-0">
            <span class="badge badge-status badge-{{ Str::slug($transaksi->status) }} fs-6">
              {{ $transaksi->status_icon }} {{ $transaksi->status }}
            </span>
          </h4>
        </div>

        <div class="d-flex gap-2">
          <!-- WhatsApp Notification Button -->
          @if($transaksi->pelanggan && $transaksi->pelanggan->no_hp)
            @php
              $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $transaksi->pelanggan->no_hp));
              $pesanWa = urlencode("Halo Kak {$transaksi->pelanggan->nama}! Cucian Anda di LaundryPro (No: {$transaksi->no_transaksi}) berstatus: *{$transaksi->status}*. Total Tagihan: Rp " . number_format($transaksi->total, 0, ',', '.') . " ({$transaksi->status_pembayaran}). Terima kasih!");
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $pesanWa }}" target="_blank" class="btn btn-success d-flex align-items-center gap-2">
              <i class="bi bi-whatsapp fs-5"></i> Kirim WhatsApp
            </a>
          @endif

          <a href="{{ route('transaksi.struk', $transaksi) }}" target="_blank" class="btn btn-outline-dark d-flex align-items-center gap-2">
            <i class="bi bi-printer fs-5"></i> Cetak Struk
          </a>

          <a href="{{ route('transaksi.edit', $transaksi) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-pencil"></i> Edit Pembayaran / Status
          </a>
        </div>
      </div>

      <!-- Stepper Indicator -->
      <div class="p-3 bg-light rounded-3">
        <div class="d-flex justify-content-between position-relative flex-wrap gap-2 text-center">
          @php
            $flow = ['Baru', 'Diproses', 'Dicuci', 'Disetrika', 'Siap Diambil', 'Selesai'];
            $currentIndex = array_search($transaksi->status, $flow);
            if ($currentIndex === false && $transaksi->status === 'Dibatalkan') {
              $currentIndex = -1;
            }
          @endphp

          @foreach($flow as $idx => $step)
            @php
              $isPassed = $currentIndex !== -1 && $idx <= $currentIndex;
              $isCurrent = $currentIndex !== -1 && $idx === $currentIndex;
            @endphp
            <div class="flex-grow-1" style="min-width: 90px;">
              <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-1 fw-bold
                          {{ $isCurrent ? 'bg-primary text-white shadow' : ($isPassed ? 'bg-success text-white' : 'bg-white border text-muted') }}"
                   style="width: 36px; height: 36px; font-size: 0.9rem;">
                @if($isPassed && !$isCurrent)
                  <i class="bi bi-check-lg"></i>
                @else
                  {{ $idx + 1 }}
                @endif
              </div>
              <div class="small fw-semibold {{ $isCurrent ? 'text-primary' : ($isPassed ? 'text-dark' : 'text-muted') }}">
                {{ $step }}
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Quick Step-Up Button -->
      @if($currentIndex !== false && $currentIndex < count($flow) - 1 && $transaksi->status !== 'Dibatalkan')
        @php $nextStep = $flow[$currentIndex + 1]; @endphp
        <div class="mt-3 text-end">
          <form action="{{ route('transaksi.update-status', $transaksi) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="status" value="{{ $nextStep }}">
            <button type="submit" class="btn btn-primary-custom">
              Lanjut ke Status: <strong>{{ $nextStep }}</strong> <i class="bi bi-arrow-right ms-1"></i>
            </button>
          </form>
        </div>
      @endif
    </div>
  </div>

  <!-- Detail Information -->
  <div class="col-lg-7">
    <div class="card card-custom p-4 mb-4">
      <h5 class="fw-bold mb-3 border-bottom pb-2">Rincian Cucian</h5>

      <div class="row g-3">
        <div class="col-sm-6">
          <div class="text-muted small">Nomor Transaksi</div>
          <div class="fw-bold fs-5 text-primary">{{ $transaksi->no_transaksi }}</div>
        </div>
        <div class="col-sm-6">
          <div class="text-muted small">Tanggal Masuk</div>
          <div class="fw-semibold">{{ $transaksi->tanggal_masuk->format('d F Y') }}</div>
        </div>
        <div class="col-sm-6">
          <div class="text-muted small">Paket Layanan</div>
          <div class="fw-bold fs-6">
            {{ $transaksi->layanan->ikon ?? '👕' }} {{ $transaksi->layanan->nama ?? '-' }}
          </div>
          <small class="text-muted">Rp {{ number_format($transaksi->harga_per_kg, 0, ',', '.') }} /kg</small>
        </div>
        <div class="col-sm-6">
          <div class="text-muted small">Berat Cucian</div>
          <div class="fw-bold fs-5">{{ $transaksi->berat }} kg</div>
        </div>
        <div class="col-sm-6">
          <div class="text-muted small">Estimasi Selesai</div>
          <div class="fw-semibold">{{ $transaksi->estimasi_selesai ? $transaksi->estimasi_selesai->format('d F Y') : '-' }}</div>
        </div>
        <div class="col-sm-6">
          <div class="text-muted small">Petugas Kasir</div>
          <div class="fw-semibold">{{ $transaksi->kasir->name ?? 'Kasir' }}</div>
        </div>
      </div>

      @if($transaksi->catatan)
        <div class="mt-4 p-3 bg-light rounded-3 border">
          <div class="small fw-bold text-muted mb-1"><i class="bi bi-sticky me-1"></i> Catatan Khusus:</div>
          <div>{{ $transaksi->catatan }}</div>
        </div>
      @endif

      @if($transaksi->status === 'Dibatalkan' && $transaksi->catatan_batal)
        <div class="mt-3 p-3 bg-danger-subtle rounded-3 border border-danger text-danger">
          <div class="small fw-bold mb-1"><i class="bi bi-x-circle me-1"></i> Alasan Dibatalkan:</div>
          <div>{{ $transaksi->catatan_batal }}</div>
        </div>
      @endif
    </div>

    <!-- Pelanggan Info -->
    <div class="card card-custom p-4">
      <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Pelanggan</h5>
      <div class="d-flex align-items-center gap-3">
        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 54px; height: 54px;">
          {{ strtoupper(substr($transaksi->pelanggan->nama ?? 'P', 0, 1)) }}
        </div>
        <div>
          <h5 class="fw-bold mb-1">{{ $transaksi->pelanggan->nama ?? 'Pelanggan Umum' }}</h5>
          <div class="text-muted small">
            <i class="bi bi-telephone me-1"></i> {{ $transaksi->pelanggan->no_hp ?? '-' }} &bull; 
            <i class="bi bi-geo-alt me-1"></i> {{ $transaksi->pelanggan->alamat ?? '-' }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tagihan & Pembayaran -->
  <div class="col-lg-5">
    <div class="card card-custom p-4">
      <h5 class="fw-bold mb-3 border-bottom pb-2">Rincian Pembayaran</h5>

      <div class="d-flex justify-content-between mb-2">
        <span class="text-muted">Subtotal ({{ $transaksi->berat }} kg x Rp {{ number_format($transaksi->harga_per_kg, 0, ',', '.') }})</span>
        <span class="fw-semibold">Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
      </div>

      @if($transaksi->diskon > 0)
        <div class="d-flex justify-content-between mb-2 text-danger">
          <span>Potongan Diskon</span>
          <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
        </div>
      @endif

      <hr>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <span class="fw-bold fs-5">Total Tagihan</span>
        <span class="fw-bold fs-4 text-success">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
      </div>

      <div class="p-3 bg-light rounded-3 mb-3 border">
        <div class="d-flex justify-content-between mb-1">
          <span class="text-muted small">Status Pembayaran:</span>
          @if($transaksi->status_pembayaran === 'Lunas')
            <span class="badge badge-status badge-lunas">Lunas</span>
          @elseif($transaksi->status_pembayaran === 'DP')
            <span class="badge badge-status badge-dp">DP (Uang Muka)</span>
          @else
            <span class="badge badge-status badge-belum-bayar">Belum Bayar</span>
          @endif
        </div>
        <div class="d-flex justify-content-between mb-1">
          <span class="text-muted small">Metode:</span>
          <span class="fw-semibold small">{{ $transaksi->metode_pembayaran }}</span>
        </div>

        @if($transaksi->status_pembayaran === 'DP')
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted small">Sudah Dibayar (DP):</span>
            <span class="fw-semibold small text-success">Rp {{ number_format($transaksi->jumlah_dp, 0, ',', '.') }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-danger fw-bold small">Sisa Pelunasan:</span>
            <span class="fw-bold small text-danger">Rp {{ number_format($transaksi->sisa_bayar, 0, ',', '.') }}</span>
          </div>
        @endif
      </div>

      <!-- Form Ubah Status Pembayaran / Pelunasan (Bisa dilakukan oleh Kasir & Owner saat barang diambil) -->
      @if($transaksi->status_pembayaran !== 'Lunas')
        <form action="{{ route('transaksi.lunasi', $transaksi) }}" method="POST" class="mb-3">
          @csrf
          <input type="hidden" name="metode_pembayaran" value="{{ $transaksi->metode_pembayaran }}">
          <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
            <i class="bi bi-cash-coin me-1"></i> Lunasi Tagihan Sekarang
          </button>
        </form>
      @endif

      @if(auth()->user()->isOwner())
        <form action="{{ route('transaksi.destroy', $transaksi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data transaksi ini? Data yang terhapus tidak dapat dikembalikan.')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger btn-sm w-100">
            <i class="bi bi-trash me-1"></i> Hapus Transaksi (Khusus Owner)
          </button>
        </form>
      @endif
    </div>
  </div>
</div>
@endsection
