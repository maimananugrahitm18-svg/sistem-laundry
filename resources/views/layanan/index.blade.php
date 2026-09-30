@extends('layouts.app')

@section('title', 'Paket Layanan Laundry')
@section('page-title', 'Daftar Paket & Tarif Layanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h5 class="fw-bold mb-0">Paket & Tarif Layanan Laundry</h5>
    <p class="text-muted small mb-0">Atur harga per kg, estimasi waktu pengerjaan, dan status layanan.</p>
  </div>
  <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTambahLayanan">
    <i class="bi bi-plus-lg me-1"></i> Tambah Layanan
  </button>
</div>

<div class="row g-4 mb-4">
  @foreach($layanan as $l)
    <div class="col-lg-4 col-md-6">
      <div class="card card-custom p-4 h-100 position-relative">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div class="d-flex align-items-center gap-3">
            <span class="fs-1">{{ $l->ikon }}</span>
            <div>
              <h5 class="fw-bold mb-0">{{ $l->nama }}</h5>
              <small class="text-muted"><i class="bi bi-clock me-1"></i>Estimasi: {{ $l->estimasi_hari }} Hari</small>
            </div>
          </div>
          <div>
            @if($l->status === 'aktif')
              <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">Aktif</span>
            @else
              <span class="badge bg-secondary rounded-pill px-3 py-1">Nonaktif</span>
            @endif
          </div>
        </div>

        <p class="text-muted small mb-3" style="min-height: 40px;">
          {{ $l->deskripsi ?: 'Tidak ada deskripsi layanan.' }}
        </p>

        <div class="p-3 bg-light rounded-3 mb-3 border">
          <div class="small text-muted">Tarif Cuci:</div>
          <div class="h4 mb-0 fw-bold text-primary">Rp {{ number_format($l->harga_per_kg, 0, ',', '.') }} <span class="fs-6 fw-normal text-muted">/ kg</span></div>
        </div>

        <div class="mt-auto d-flex justify-content-between align-items-center pt-3 border-top">
          <small class="text-muted">
            <i class="bi bi-receipt me-1"></i> {{ $l->transaksi_count }} transaksi
          </small>

          <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary" 
                    data-bs-toggle="modal" data-bs-target="#modalEditLayanan{{ $l->id }}">
              <i class="bi bi-pencil"></i> Ubah Tarif
            </button>

            <form action="{{ route('layanan.toggle', $l) }}" method="POST">
              @csrf
              <button type="submit" class="btn btn-sm {{ $l->status === 'aktif' ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                      title="{{ $l->status === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' }}">
                <i class="bi {{ $l->status === 'aktif' ? 'bi-pause-fill' : 'bi-play-fill' }}"></i>
              </button>
            </form>

            @if($l->transaksi_count === 0)
              <form action="{{ route('layanan.destroy', $l) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Edit Layanan -->
    <div class="modal fade" id="modalEditLayanan{{ $l->id }}" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('layanan.update', $l) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h5 class="modal-title fw-bold">Ubah Layanan: {{ $l->nama }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label fw-semibold">Nama Layanan</label>
                <input type="text" name="nama" class="form-control" value="{{ $l->nama }}" required>
              </div>
              <div class="row g-2 mb-3">
                <div class="col-8">
                  <label class="form-label fw-semibold">Harga per Kg (Rp)</label>
                  <input type="number" name="harga_per_kg" class="form-control fw-bold text-primary" value="{{ (int)$l->harga_per_kg }}" required>
                </div>
                <div class="col-4">
                  <label class="form-label fw-semibold">Ikon Emoji</label>
                  <input type="text" name="ikon" class="form-control text-center fs-5" value="{{ $l->ikon }}">
                </div>
              </div>
              <div class="row g-2 mb-3">
                <div class="col-6">
                  <label class="form-label fw-semibold">Estimasi (Hari)</label>
                  <input type="number" name="estimasi_hari" class="form-control" value="{{ $l->estimasi_hari }}" min="1" required>
                </div>
                <div class="col-6">
                  <label class="form-label fw-semibold">Status</label>
                  <select name="status" class="form-select">
                    <option value="aktif" {{ $l->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ $l->status === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2">{{ $l->deskripsi }}</textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endforeach
</div>

<!-- Modal Tambah Layanan -->
<div class="modal fade" id="modalTambahLayanan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('layanan.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Layanan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Layanan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" required placeholder="Contoh: Cuci Boneka & Helm">
          </div>
          <div class="row g-2 mb-3">
            <div class="col-8">
              <label class="form-label fw-semibold">Harga per Kg / Satuan (Rp) <span class="text-danger">*</span></label>
              <input type="number" name="harga_per_kg" class="form-control fw-bold text-primary" required placeholder="10000">
            </div>
            <div class="col-4">
              <label class="form-label fw-semibold">Ikon Emoji</label>
              <input type="text" name="ikon" class="form-control text-center fs-5" value="🧺">
            </div>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold">Estimasi Pengerjaan (Hari) <span class="text-danger">*</span></label>
              <input type="number" name="estimasi_hari" class="form-control" value="2" min="1" required>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Status Awal</label>
              <select name="status" class="form-select">
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi Layanan</label>
            <textarea name="deskripsi" class="form-control" rows="2" placeholder="Jelaskan proses atau keunggulan layanan ini..."></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary-custom">Simpan Layanan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
