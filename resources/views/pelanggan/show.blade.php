@extends('layouts.app')

@section('title', 'Riwayat Pelanggan ' . $pelanggan->nama)
@section('page-title', 'Detail & Riwayat: ' . $pelanggan->nama)

@section('content')
<div class="row g-4 mb-4">
  <!-- Pelanggan Profile Card -->
  <div class="col-lg-4">
    <div class="card card-custom p-4 text-center">
      <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold fs-2 mx-auto mb-3" style="width: 80px; height: 80px;">
        {{ strtoupper(substr($pelanggan->nama, 0, 1)) }}
      </div>
      <h4 class="fw-bold mb-1">{{ $pelanggan->nama }}</h4>
      <span class="badge bg-light text-primary border font-monospace mb-3">{{ $pelanggan->kode_pelanggan }}</span>

      <div class="text-start border-top pt-3">
        <div class="mb-2">
          <small class="text-muted d-block">Nomor Telepon / WhatsApp:</small>
          <div class="fw-semibold d-flex align-items-center justify-content-between">
            <span>{{ $pelanggan->no_hp }}</span>
            @if($pelanggan->no_hp)
              @php $clean = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $pelanggan->no_hp)); @endphp
              <a href="https://wa.me/{{ $clean }}" target="_blank" class="btn btn-sm btn-success py-0 px-2">
                <i class="bi bi-whatsapp"></i> Chat
              </a>
            @endif
          </div>
        </div>

        @if($pelanggan->email)
          <div class="mb-2">
            <small class="text-muted d-block">Email:</small>
            <div class="fw-semibold">{{ $pelanggan->email }}</div>
          </div>
        @endif

        <div class="mb-2">
          <small class="text-muted d-block">Alamat:</small>
          <div>{{ $pelanggan->alamat ?? '-' }}</div>
        </div>

        @if($pelanggan->catatan)
          <div class="mb-2 p-2 bg-light rounded border">
            <small class="text-muted d-block fw-bold">Catatan Khusus:</small>
            <small>{{ $pelanggan->catatan }}</small>
          </div>
        @endif
      </div>

      <div class="d-flex gap-2 mt-3 pt-3 border-top">
        <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="btn btn-outline-secondary w-50">
          <i class="bi bi-pencil me-1"></i> Edit No. HP / Nama
        </a>
        <a href="{{ route('transaksi.create', ['pelanggan_id' => $pelanggan->id]) }}" class="btn btn-primary-custom w-50">
          <i class="bi bi-plus-lg me-1"></i> Cuci Baru
        </a>
      </div>

      <div class="mt-2">
        <form action="{{ route('pelanggan.destroy', $pelanggan) }}" method="POST" onsubmit="return confirm('Hapus pelanggan {{ $pelanggan->nama }}? Tindakan ini akan menghapus data pelanggan dari database agar tidak memenuhi penyimpanan.')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger btn-sm w-100">
            <i class="bi bi-trash me-1"></i> Hapus Pelanggan Ini
          </button>
        </form>
      </div>
    </div>
  </div>

  <!-- Lifetime Stats & History Table -->
  <div class="col-lg-8">
    <div class="row g-3 mb-4">
      <div class="col-sm-4">
        <div class="p-3 bg-white border rounded-3 text-center">
          <small class="text-muted">Total Transaksi</small>
          <h3 class="fw-bold mb-0 text-primary">{{ $stats['total_transaksi'] }}x</h3>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="p-3 bg-white border rounded-3 text-center">
          <small class="text-muted">Total Berat Cucian</small>
          <h3 class="fw-bold mb-0 text-info">{{ $stats['total_berat'] }} kg</h3>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="p-3 bg-white border rounded-3 text-center">
          <small class="text-muted">Total Belanja (Lunas)</small>
          <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($stats['total_belanja'], 0, ',', '.') }}</h3>
        </div>
      </div>
    </div>

    <!-- History Table -->
    <div class="card card-custom p-4">
      <h5 class="fw-bold mb-3">Riwayat Transaksi Cucian</h5>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Nota</th>
              <th>Layanan</th>
              <th>Berat</th>
              <th>Total</th>
              <th>Bayar</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($riwayat as $t)
              <tr>
                <td>
                  <a href="{{ route('transaksi.show', $t) }}" class="fw-bold text-decoration-none">
                    {{ $t->no_transaksi }}
                  </a>
                  <div class="small text-muted">{{ $t->tanggal_masuk->format('d/m/Y') }}</div>
                </td>
                <td>{{ $t->layanan->nama ?? '-' }}</td>
                <td>{{ $t->berat }} kg</td>
                <td class="fw-bold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td>
                  @if($t->status_pembayaran === 'Lunas')
                    <span class="badge bg-success">Lunas</span>
                  @elseif($t->status_pembayaran === 'DP')
                    <span class="badge bg-warning text-dark">DP</span>
                  @else
                    <span class="badge bg-danger">Belum</span>
                  @endif
                </td>
                <td>
                  <span class="badge badge-status badge-{{ Str::slug($t->status) }}">
                    {{ $t->status }}
                  </span>
                </td>
                <td class="text-end">
                  <a href="{{ route('transaksi.show', $t) }}" class="btn btn-sm btn-light border">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $riwayat->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</div>
@endsection
