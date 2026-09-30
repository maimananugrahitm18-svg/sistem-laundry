@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page-title', 'Data Pelanggan')

@section('content')
<div class="card card-custom p-4 mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <!-- Search Form -->
    <form method="GET" action="{{ route('pelanggan.index') }}" class="d-flex gap-2 flex-grow-1" style="max-width: 400px;">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
        <input type="text" name="search" class="form-control border-start-0" 
               placeholder="Cari nama, no. HP, atau ID..." value="{{ request('search') }}">
      </div>
      <button type="submit" class="btn btn-primary-custom">Cari</button>
      @if(request('search'))
        <a href="{{ route('pelanggan.index') }}" class="btn btn-outline-secondary" title="Reset">
          <i class="bi bi-x-lg"></i>
        </a>
      @endif
    </form>

    <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
      <i class="bi bi-person-plus-fill me-1"></i> Tambah Pelanggan
    </button>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Nama Pelanggan</th>
          <th>No. WhatsApp / HP</th>
          <th>Alamat</th>
          <th>Total Transaksi</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pelanggan as $p)
          <tr>
            <td>
              <span class="badge bg-light text-primary border font-monospace">{{ $p->kode_pelanggan }}</span>
            </td>
            <td>
              <div class="fw-bold text-dark">{{ $p->nama }}</div>
              <small class="text-muted">{{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : ($p->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</small>
            </td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <span>{{ $p->no_hp }}</span>
                @if($p->no_hp)
                  @php
                    $cleanPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $p->no_hp));
                  @endphp
                  <a href="https://wa.me/{{ $cleanPhone }}" target="_blank" class="btn btn-sm btn-light text-success p-1" title="Chat WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                  </a>
                @endif
              </div>
            </td>
            <td>
              <div class="text-truncate" style="max-width: 250px;">{{ $p->alamat ?? '-' }}</div>
            </td>
            <td>
              <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1 rounded-pill">
                {{ $p->transaksi_count }}x Cuci
              </span>
            </td>
            <td class="text-end">
              <div class="btn-group btn-group-sm">
                <a href="{{ route('pelanggan.show', $p) }}" class="btn btn-light border" title="Lihat Riwayat">
                  <i class="bi bi-clock-history"></i> Riwayat
                </a>
                <a href="{{ route('pelanggan.edit', $p) }}" class="btn btn-light border text-primary" title="Ubah Nama / No. HP WhatsApp">
                  <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('pelanggan.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus pelanggan {{ $p->nama }}? Tindakan ini akan menghapus data pelanggan agar tidak memenuhi penyimpanan.')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-light border text-danger" title="Hapus Pelanggan">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center py-5 text-muted">
              Belum ada data pelanggan yang sesuai.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $pelanggan->links('pagination::bootstrap-5') }}
  </div>
</div>

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="modalTambahPelanggan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('pelanggan.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Pelanggan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" required placeholder="Contoh: Anita Wijaya">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
            <input type="tel" name="no_hp" class="form-control" required placeholder="08123456789">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Email (Opsional)</label>
            <input type="email" name="email" class="form-control" placeholder="pelanggan@mail.com">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat rumah atau apartemen..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
              <option value="">-- Pilih --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catatan Pelanggan</label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan preferensi, misal: alergi pewangi tertentu"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary-custom">Simpan Pelanggan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
