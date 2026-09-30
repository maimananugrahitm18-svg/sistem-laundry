@extends('layouts.app')

@section('title', 'Transaksi Laundry')
@section('page-title', 'Kelola Transaksi Laundry')

@section('content')
<div class="card card-custom p-4 mb-4">
  <!-- Status Filter Pills -->
  <div class="d-flex flex-wrap gap-2 mb-4 pb-3 border-bottom">
    <a href="{{ route('transaksi.index') }}" 
       class="btn btn-sm {{ !request('status') ? 'btn-primary-custom' : 'btn-outline-secondary' }} rounded-pill px-3">
      Semua Transaksi
    </a>
    @foreach($statuses as $st)
      @php
        $isActive = request('status') === $st;
        $slug = Str::slug($st);
      @endphp
      <a href="{{ route('transaksi.index', array_merge(request()->query(), ['status' => $st])) }}" 
         class="btn btn-sm {{ $isActive ? 'btn-dark' : 'btn-light border' }} rounded-pill px-3">
        {{ App\Models\Transaksi::STATUS_ICONS[$st] ?? '' }} {{ $st }}
      </a>
    @endforeach
  </div>

  <!-- Filter & Search Bar -->
  <form method="GET" action="{{ route('transaksi.index') }}" class="row g-2 mb-4">
    @if(request('status'))
      <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    <div class="col-md-4">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="search" class="form-control border-start-0" 
               placeholder="Cari No. Transaksi atau Nama..." value="{{ request('search') }}">
      </div>
    </div>
    <div class="col-md-3">
      <select name="bayar" class="form-select">
        <option value="">-- Semua Status Bayar --</option>
        <option value="Lunas" {{ request('bayar') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
        <option value="DP" {{ request('bayar') == 'DP' ? 'selected' : '' }}>DP (Uang Muka)</option>
        <option value="Belum Bayar" {{ request('bayar') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
      </select>
    </div>
    <div class="col-md-3">
      <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" title="Filter Tanggal Masuk">
    </div>
    <div class="col-md-2 d-flex gap-2">
      <button type="submit" class="btn btn-primary-custom w-100">
        <i class="bi bi-filter"></i> Filter
      </button>
      @if(request()->hasAny(['search', 'bayar', 'tanggal', 'status']))
        <a href="{{ route('transaksi.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
          <i class="bi bi-arrow-counterclockwise"></i>
        </a>
      @endif
    </div>
  </form>

  <!-- Transactions Table -->
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>No. Transaksi</th>
          <th>Pelanggan</th>
          <th>Layanan</th>
          <th>Berat</th>
          <th>Total Tagihan</th>
          <th>Status Bayar</th>
          <th>Status Cucian</th>
          <th>Est. Selesai</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($transaksi as $t)
          <tr>
            <td>
              <a href="{{ route('transaksi.show', $t) }}" class="fw-bold text-primary text-decoration-none">
                {{ $t->no_transaksi }}
              </a>
              <div class="text-muted small">{{ $t->tanggal_masuk->format('d/m/Y') }}</div>
            </td>
            <td>
              <div class="fw-semibold">{{ $t->pelanggan->nama ?? '-' }}</div>
              <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $t->pelanggan->no_hp ?? '-' }}</small>
            </td>
            <td>
              <span>{{ $t->layanan->ikon ?? '👕' }}</span>
              <span class="fw-semibold ms-1">{{ $t->layanan->nama ?? '-' }}</span>
              <div class="text-muted small">@ Rp {{ number_format($t->harga_per_kg, 0, ',', '.') }}</div>
            </td>
            <td>
              <span class="badge bg-light text-dark border px-2 py-1 fw-bold fs-6">{{ $t->berat }} kg</span>
            </td>
            <td>
              <div class="fw-bold text-dark">Rp {{ number_format($t->total, 0, ',', '.') }}</div>
              @if($t->diskon > 0)
                <small class="text-danger">Diskon Rp {{ number_format($t->diskon, 0, ',', '.') }}</small>
              @endif
            </td>
            <td>
              @if($t->status_pembayaran === 'Lunas')
                <span class="badge badge-status badge-lunas">Lunas</span>
              @elseif($t->status_pembayaran === 'DP')
                <span class="badge badge-status badge-dp">DP: Rp {{ number_format($t->jumlah_dp, 0, ',', '.') }}</span>
                <div class="text-muted small mt-1">Sisa: Rp {{ number_format($t->sisa_bayar, 0, ',', '.') }}</div>
              @else
                <span class="badge badge-status badge-belum-bayar">Belum Bayar</span>
              @endif
              <div class="small text-muted mt-1">{{ $t->metode_pembayaran }}</div>
            </td>
            <td>
              @php $slug = Str::slug($t->status); @endphp
              <div class="dropdown">
                <button class="btn btn-sm badge badge-status badge-{{ $slug }} dropdown-toggle border-0" 
                        type="button" data-bs-toggle="dropdown">
                  {{ $t->status_icon }} {{ $t->status }}
                </button>
                <ul class="dropdown-menu shadow-sm">
                  <li><h6 class="dropdown-header">Ubah Status Cepat:</h6></li>
                  @foreach($statuses as $st)
                    <li>
                      <form action="{{ route('transaksi.update-status', $t) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{ $st }}">
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ $t->status === $st ? 'active fw-bold' : '' }}">
                          <span>{{ App\Models\Transaksi::STATUS_ICONS[$st] ?? '' }}</span>
                          <span>{{ $st }}</span>
                        </button>
                      </form>
                    </li>
                  @endforeach
                </ul>
              </div>
            </td>
            <td>
              @if($t->estimasi_selesai)
                <div class="{{ $t->estimasi_selesai->isPast() && $t->status !== 'Selesai' ? 'text-danger fw-bold' : 'text-muted' }} small">
                  {{ $t->estimasi_selesai->format('d/m/Y') }}
                </div>
              @else
                <span class="text-muted small">-</span>
              @endif
            </td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('transaksi.struk', $t) }}" class="btn btn-light border" title="Cetak Struk">
                  <i class="bi bi-printer"></i>
                </a>
                @if($t->pelanggan && $t->pelanggan->no_hp)
                  @php
                    $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $t->pelanggan->no_hp));
                    $pesanWa = urlencode("Halo {$t->pelanggan->nama}, pesanan laundry Anda No: {$t->no_transaksi} ({$t->layanan->nama}) seberat {$t->berat}kg status saat ini: {$t->status}. Total: Rp " . number_format($t->total, 0, ',', '.') . " ({$t->status_pembayaran}). Terima kasih!");
                  @endphp
                  <a href="https://wa.me/{{ $cleanPhone }}?text={{ $pesanWa }}" target="_blank" class="btn btn-light border text-success" title="Kirim WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                  </a>
                @endif
                <a href="{{ route('transaksi.show', $t) }}" class="btn btn-light border" title="Detail Transaksi">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('transaksi.edit', $t) }}" class="btn btn-light border text-primary" title="Ubah Pembayaran / Status Cucian">
                  <i class="bi bi-pencil"></i>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center py-5 text-muted">
              <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
              Tidak ada data transaksi yang sesuai filter.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $transaksi->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
