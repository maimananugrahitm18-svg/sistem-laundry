@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Laundry & Manajemen Hak Akses')

@section('content')
<div class="row g-4">
  <!-- Kolom Kiri: Profil Laundry & Format Struk -->
  <div class="col-lg-6">
    <div class="card card-custom p-4 mb-4">
      <h5 class="fw-bold mb-3 border-bottom pb-2">
        <i class="bi bi-shop me-2 text-primary"></i> Profil Usaha Laundry
      </h5>

      <form action="{{ route('pengaturan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4 p-3 bg-light rounded-3 border">
          <label class="form-label fw-semibold mb-2">
            <i class="bi bi-image me-1 text-primary"></i> Logo Laundry (Tampil di Halaman Login & Navbar)
          </label>
          <div class="d-flex align-items-center gap-3">
            @php
              $currentLogo = $settings['logo'] ?? '🧺';
              $isCurrentImg = str_starts_with($currentLogo, '/') || str_starts_with($currentLogo, 'http') || str_contains($currentLogo, '.');
            @endphp
            <div class="border rounded-3 bg-white d-flex align-items-center justify-content-center shadow-sm" style="width: 72px; height: 72px; flex-shrink: 0;">
              @if($isCurrentImg)
                <img src="{{ $currentLogo }}" alt="Preview Logo" style="max-width: 58px; max-height: 58px; object-fit: contain;">
              @else
                <span style="font-size: 2.2rem;">{{ $currentLogo }}</span>
              @endif
            </div>
            <div class="flex-grow-1">
              <label class="form-label small text-muted mb-1">Unggah Gambar Logo Baru (PNG / JPG / WebP):</label>
              <input type="file" name="logo_image" class="form-control form-control-sm mb-2" accept="image/*">
              <div class="d-flex align-items-center gap-2">
                <span class="small text-muted">Atau Icon Emoji:</span>
                <input type="text" name="logo" class="form-control form-control-sm text-center" style="max-width: 80px;" value="{{ $currentLogo }}">
                <div class="d-flex gap-1">
                  <button type="button" class="btn btn-sm btn-white border py-0 px-2" onclick="document.querySelector('input[name=logo]').value = '🧺'">🧺</button>
                  <button type="button" class="btn btn-sm btn-white border py-0 px-2" onclick="document.querySelector('input[name=logo]').value = '🫧'">🫧</button>
                  <button type="button" class="btn btn-sm btn-white border py-0 px-2" onclick="document.querySelector('input[name=logo]').value = '👕'">👕</button>
                  <button type="button" class="btn btn-sm btn-white border py-0 px-2" onclick="document.querySelector('input[name=logo]').value = '🧼'">🧼</button>
                  <button type="button" class="btn btn-sm btn-white border py-0 px-2" onclick="document.querySelector('input[name=logo]').value = '✨'">✨</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Nama Laundry <span class="text-danger">*</span></label>
          <input type="text" name="nama_laundry" class="form-control" value="{{ $settings['nama_laundry'] ?? '' }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Slogan / Tagline</label>
          <input type="text" name="slogan" class="form-control" value="{{ $settings['slogan'] ?? '' }}">
        </div>

        <div class="row g-3 mb-3">
          <div class="col-sm-6">
            <label class="form-label fw-semibold">No. Telepon / WhatsApp</label>
            <input type="text" name="telepon" class="form-control" value="{{ $settings['telepon'] ?? '' }}">
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-semibold">Email Usaha</label>
            <input type="email" name="email" class="form-control" value="{{ $settings['email'] ?? '' }}">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Alamat Lengkap</label>
          <textarea name="alamat" class="form-control" rows="2">{{ $settings['alamat'] ?? '' }}</textarea>
        </div>

        <h6 class="fw-bold mt-4 mb-3 border-top pt-3">
          <i class="bi bi-receipt-cutoff me-2 text-primary"></i> Format Struk / Nota Cetak
        </h6>

        <div class="mb-3">
          <label class="form-label fw-semibold">Catatan Kaki Struk (Footer)</label>
          <input type="text" name="footer_struk" class="form-control" value="{{ $settings['footer_struk'] ?? '' }}" placeholder="Terima kasih atas kunjungan Anda!">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Syarat & Ketentuan Laundry</label>
          <textarea name="syarat_ketentuan" class="form-control" rows="3">{{ $settings['syarat_ketentuan'] ?? '' }}</textarea>
          <small class="text-muted">Akan ditampilkan pada bagian bawah struk penerimaan barang.</small>
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary-custom px-4">
            <i class="bi bi-save me-1"></i> Simpan Profil Usaha
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Kolom Kanan: Manajemen User & Hak Akses -->
  <div class="col-lg-6">
    <div class="card card-custom p-4">
      <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
        <h5 class="fw-bold mb-0">
          <i class="bi bi-people-fill me-2 text-primary"></i> Pengguna & Hak Akses
        </h5>
        <button type="button" class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
          <i class="bi bi-person-plus-fill me-1"></i> Tambah User
        </button>
      </div>

      <p class="text-muted small mb-3">
        Kelola akun pengguna: <strong>Admin</strong> (kontrol penuh), <strong>Kasir</strong> (order & kasir), atau <strong>Pemilik/Owner</strong> (pantau laporan).
      </p>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Pengguna</th>
              <th>Peran</th>
              <th>No. HP</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $u)
              <tr>
                <td>
                  <div class="fw-bold">{{ $u->name }}</div>
                  <small class="text-muted">{{ $u->email }}</small>
                </td>
                <td>
                  <span class="role-badge role-{{ $u->role }}">
                    {{ ucfirst($u->role) }}
                  </span>
                </td>
                <td><small>{{ $u->no_hp ?? '-' }}</small></td>
                <td class="text-end">
                  <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-light border" 
                            data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $u->id }}" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </button>
                    @if(auth()->id() !== $u->id)
                      <form action="{{ route('pengaturan.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Hapus user {{ $u->name }}?')" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-light border text-danger" title="Hapus">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>

              <!-- Modal Edit User -->
              <div class="modal fade" id="modalEditUser{{ $u->id }}" tabindex="-1">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('pengaturan.users.update', $u) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h5 class="modal-title fw-bold">Edit User: {{ $u->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Nama Lengkap</label>
                          <input type="text" name="name" class="form-control" value="{{ $u->name }}" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Email</label>
                          <input type="email" name="email" class="form-control" value="{{ $u->email }}" required>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Hak Akses / Peran</label>
                          <select name="role" class="form-select" required>
                            <option value="kasir" {{ $u->role == 'kasir' ? 'selected' : '' }}>Kasir / Petugas (Input Order Cucian & Pelanggan)</option>
                            <option value="owner" {{ $u->role == 'owner' || $u->role == 'admin' ? 'selected' : '' }}>Pemilik / Owner (Akses Penuh CRUD, Tarif, & Laporan Keuangan)</option>
                          </select>
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">No. HP</label>
                          <input type="text" name="no_hp" class="form-control" value="{{ $u->no_hp }}">
                        </div>
                        <div class="mb-3">
                          <label class="form-label fw-semibold">Password Baru (Kosongkan jika tidak diubah)</label>
                          <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
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
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('pengaturan.users.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" required placeholder="Contoh: Rendi Kasir">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Email Login <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" required placeholder="rendi@laundrypro.com">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Hak Akses / Peran <span class="text-danger">*</span></label>
            <select name="role" class="form-select" required>
              <option value="kasir">Kasir / Petugas (Input Order Cucian & Pelanggan)</option>
              <option value="owner">Pemilik / Owner (Akses Penuh CRUD, Tarif, & Laporan Keuangan)</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">No. HP</label>
            <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary-custom">Simpan Pengguna</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
