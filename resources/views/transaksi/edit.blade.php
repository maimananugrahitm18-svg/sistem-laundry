@extends('layouts.app')

@section('title', 'Edit Transaksi ' . $transaksi->no_transaksi)
@section('page-title', 'Ubah Status & Pembayaran #' . $transaksi->no_transaksi)

@section('content')
<div class="card card-custom p-4" style="max-width: 800px; margin: 0 auto;">
  <form action="{{ route('transaksi.update', $transaksi) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
      <div>
        <h5 class="fw-bold mb-0">Ubah Data Transaksi & Pembayaran</h5>
        <small class="text-muted">Pelanggan: <strong>{{ $transaksi->pelanggan->nama ?? '-' }}</strong> &bull; {{ $transaksi->pelanggan->no_hp ?? '-' }}</small>
      </div>
      <span class="badge bg-light text-primary border fs-6 font-monospace">{{ $transaksi->no_transaksi }}</span>
    </div>

    <div class="p-3 bg-light rounded-3 mb-4 border">
      <div class="row g-2">
        <div class="col-sm-4">
          <small class="text-muted d-block">Paket Layanan:</small>
          <strong>{{ $transaksi->layanan->nama ?? '-' }}</strong> (@ Rp {{ number_format($transaksi->harga_per_kg, 0, ',', '.') }}/kg)
        </div>
        <div class="col-sm-4">
          <small class="text-muted d-block">Total Tagihan Saat Ini:</small>
          <strong class="text-success fs-5">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</strong>
        </div>
        <div class="col-sm-4">
          <small class="text-muted d-block">Tgl Masuk:</small>
          <div>{{ $transaksi->tanggal_masuk->format('d F Y') }}</div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <!-- Status Cucian -->
      <div class="col-md-6">
        <label class="form-label fw-semibold">Status Pengerjaan Cucian</label>
        <select name="status" class="form-select form-select-lg">
          @foreach($statuses as $st)
            <option value="{{ $st }}" {{ $transaksi->status === $st ? 'selected' : '' }}>
              {{ App\Models\Transaksi::STATUS_ICONS[$st] ?? '' }} {{ $st }}
            </option>
          @endforeach
        </select>
      </div>

      <!-- Status Bayar -->
      <div class="col-md-6">
        <label class="form-label fw-semibold">Status Pembayaran</label>
        <select name="status_pembayaran" id="editStatusBayar" class="form-select form-select-lg">
          <option value="Lunas" {{ $transaksi->status_pembayaran === 'Lunas' ? 'selected' : '' }}>✅ Lunas (Sudah Dibayar Penuh)</option>
          <option value="DP" {{ $transaksi->status_pembayaran === 'DP' ? 'selected' : '' }}>⏳ Bayar DP (Uang Muka / Sebagian)</option>
          <option value="Belum Bayar" {{ $transaksi->status_pembayaran === 'Belum Bayar' ? 'selected' : '' }}>❌ Belum Bayar (Nanti saat ambil)</option>
        </select>
      </div>

      <!-- Nominal DP (Muncul jika status DP) -->
      <div class="col-md-6 {{ $transaksi->status_pembayaran === 'DP' ? '' : 'd-none' }}" id="editGroupDP">
        <label class="form-label fw-semibold text-primary">Jumlah Uang Muka / DP (Rp)</label>
        <input type="number" name="jumlah_dp" id="editInputDP" class="form-control" value="{{ (int)$transaksi->jumlah_dp }}" min="0">
        <small class="text-muted">Total tagihan: Rp {{ number_format($transaksi->total, 0, ',', '.') }}. Sisa akan dihitung otomatis.</small>
      </div>

      <!-- Metode Pembayaran -->
      <div class="col-md-6">
        <label class="form-label fw-semibold">Metode Pembayaran</label>
        <select name="metode_pembayaran" class="form-select">
          <option value="Tunai" {{ $transaksi->metode_pembayaran === 'Tunai' ? 'selected' : '' }}>💵 Tunai (Cash)</option>
          <option value="QRIS" {{ $transaksi->metode_pembayaran === 'QRIS' ? 'selected' : '' }}>📱 QRIS / E-Wallet</option>
          <option value="Transfer" {{ $transaksi->metode_pembayaran === 'Transfer' ? 'selected' : '' }}>🏦 Transfer Bank</option>
        </select>
      </div>

      <!-- Berat Cucian (Opsional koreksi timbangan) -->
      <div class="col-md-6">
        <label class="form-label fw-semibold">Koreksi Berat Cucian (kg)</label>
        <div class="input-group">
          <input type="number" step="0.1" min="0.1" name="berat" class="form-control" value="{{ $transaksi->berat }}">
          <span class="input-group-text">kg</span>
        </div>
        <small class="text-muted">Ubah hanya jika terjadi kesalahan timbangan awal.</small>
      </div>

      <!-- Catatan Khusus -->
      <div class="col-12">
        <label class="form-label fw-semibold">Catatan Khusus Pelanggan</label>
        <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Baju putih dipisah, parfum lavender...">{{ old('catatan', $transaksi->catatan) }}</textarea>
      </div>

      <!-- Alasan Dibatalkan -->
      <div class="col-12">
        <label class="form-label fw-semibold text-danger">Catatan Pembatalan (Khusus jika status Dibatalkan)</label>
        <textarea name="catatan_batal" class="form-control" rows="2" placeholder="Alasan pesanan dibatalkan...">{{ old('catatan_batal', $transaksi->catatan_batal) }}</textarea>
      </div>
    </div>

    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
      <a href="{{ route('transaksi.show', $transaksi) }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-1"></i> Batal / Kembali
      </a>
      <button type="submit" class="btn btn-primary-custom px-4 py-2">
        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan Transaksi
      </button>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  const statusBayar = document.getElementById('editStatusBayar');
  const groupDP = document.getElementById('editGroupDP');

  if (statusBayar && groupDP) {
    statusBayar.addEventListener('change', function() {
      if (this.value === 'DP') {
        groupDP.classList.remove('d-none');
      } else {
        groupDP.classList.add('d-none');
      }
    });
  }
</script>
@endpush
