@extends('layouts.app')

@section('title', 'Transaksi Baru')
@section('page-title', 'Buat Transaksi Laundry Baru')

@section('content')
<form action="{{ route('transaksi.store') }}" method="POST" id="formTransaksi">
  @csrf

  <div class="row g-4">
    <!-- Kolom Kiri: Form Input -->
    <div class="col-lg-8">
      <!-- 1. Pelanggan -->
      <div class="card card-custom p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="fw-bold mb-0">1. Data Pelanggan</h5>
          <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalTambahPelanggan">
            <i class="bi bi-person-plus-fill me-1"></i> Pelanggan Baru
          </button>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Pilih Pelanggan <span class="text-danger">*</span></label>
          <select name="pelanggan_id" id="pelangganSelect" class="form-select @error('pelanggan_id') is-invalid @enderror" required>
            <option value="">-- Cari / Pilih Pelanggan --</option>
            @foreach($pelanggan as $p)
              <option value="{{ $p->id }}" {{ old('pelanggan_id') == $p->id ? 'selected' : '' }}>
                {{ $p->nama }} ({{ $p->kode_pelanggan }}) - {{ $p->no_hp }}
              </option>
            @endforeach
          </select>
          @error('pelanggan_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <!-- 2. Paket Layanan -->
      <div class="card card-custom p-4 mb-4">
        <h5 class="fw-bold mb-3">2. Pilih Layanan Laundry</h5>

        <div class="row g-3 mb-3">
          @foreach($layanan as $l)
            <div class="col-md-6">
              <label class="card p-3 border rounded-3 h-100 cursor-pointer service-option position-relative" style="cursor: pointer;">
                <div class="d-flex align-items-center gap-3">
                  <input type="radio" name="layanan_id" value="{{ $l->id }}" 
                         data-harga="{{ $l->harga_per_kg }}" 
                         data-hari="{{ $l->estimasi_hari }}"
                         class="form-check-input service-radio" 
                         {{ $loop->first ? 'checked' : '' }} required>
                  <span class="fs-2">{{ $l->ikon }}</span>
                  <div class="flex-grow-1">
                    <div class="fw-bold text-dark">{{ $l->nama }}</div>
                    <div class="text-primary fw-bold">Rp {{ number_format($l->harga_per_kg, 0, ',', '.') }} /kg</div>
                    <small class="text-muted"><i class="bi bi-clock me-1"></i>Est. {{ $l->estimasi_hari }} Hari</small>
                  </div>
                </div>
              </label>
            </div>
          @endforeach
        </div>
      </div>

      <!-- 3. Berat & Waktu -->
      <div class="card card-custom p-4 mb-4">
        <h5 class="fw-bold mb-3">3. Timbang & Jadwal</h5>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Berat Cucian (Kg) <span class="text-danger">*</span></label>
            <div class="input-group">
              <input type="number" step="0.1" min="0.1" name="berat" id="inputBerat" 
                     class="form-control form-control-lg fw-bold text-primary @error('berat') is-invalid @enderror" 
                     value="{{ old('berat', 1.0) }}" required>
              <span class="input-group-text fw-bold">KG</span>
            </div>
            <!-- Quick Chips -->
            <div class="d-flex gap-2 mt-2">
              <button type="button" class="btn btn-sm btn-light border chip-weight" data-weight="1">1 kg</button>
              <button type="button" class="btn btn-sm btn-light border chip-weight" data-weight="2">2 kg</button>
              <button type="button" class="btn btn-sm btn-light border chip-weight" data-weight="3">3 kg</button>
              <button type="button" class="btn btn-sm btn-light border chip-weight" data-weight="5">5 kg</button>
              <button type="button" class="btn btn-sm btn-light border chip-weight" data-weight="7">7 kg</button>
              <button type="button" class="btn btn-sm btn-light border chip-weight" data-weight="10">10 kg</button>
            </div>
            @error('berat')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Estimasi Selesai</label>
            <input type="date" name="estimasi_selesai" id="inputEstimasi" class="form-control form-control-lg" 
                   value="{{ old('estimasi_selesai', now()->addDays(2)->format('Y-m-d')) }}">
            <small class="text-muted">Dihitung otomatis dari estimasi paket</small>
          </div>
        </div>

        <div class="mt-3">
          <label class="form-label fw-semibold">Catatan Khusus (Opsional)</label>
          <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: Baju putih dipisah, kemeja jangan disikat keras...">{{ old('catatan') }}</textarea>
        </div>
      </div>
    </div>

    <!-- Kolom Kanan: Rincian Pembayaran & Tombol Simpan -->
    <div class="col-lg-4">
      <div class="card card-custom p-4 sticky-top" style="top: 90px;">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Ringkasan Pembayaran</h5>

        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Harga / Kg</span>
          <span class="fw-semibold" id="labelHargaKg">Rp 0</span>
        </div>

        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Berat</span>
          <span class="fw-semibold" id="labelBerat">0 kg</span>
        </div>

        <div class="d-flex justify-content-between mb-2">
          <span class="text-muted">Subtotal</span>
          <span class="fw-semibold" id="labelSubtotal">Rp 0</span>
        </div>

        <div class="mb-3">
          <label class="form-label small text-muted">Potongan Diskon (Rp)</label>
          <input type="number" name="diskon" id="inputDiskon" class="form-control form-control-sm" value="0" min="0">
        </div>

        <div class="p-3 bg-light rounded-3 mb-3 border">
          <div class="d-flex justify-content-between align-items-center">
            <span class="fw-bold fs-6">Total Tagihan</span>
            <span class="fw-bold fs-4 text-success" id="labelTotal">Rp 0</span>
          </div>
        </div>

        <!-- Metode & Status Pembayaran -->
        <div class="mb-3">
          <label class="form-label fw-semibold small">Metode Pembayaran</label>
          <select name="metode_pembayaran" class="form-select">
            <option value="Tunai">💵 Tunai (Cash)</option>
            <option value="QRIS">📱 QRIS / E-Wallet</option>
            <option value="Transfer">🏦 Transfer Bank</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold small">Status Pembayaran</label>
          <select name="status_pembayaran" id="statusBayarSelect" class="form-select">
            <option value="Lunas">✅ Lunas</option>
            <option value="DP">⏳ Bayar DP (Uang Muka)</option>
            <option value="Belum Bayar">❌ Belum Bayar (Nanti saat ambil)</option>
          </select>
        </div>

        <div class="mb-3 d-none" id="groupDP">
          <label class="form-label fw-semibold small">Jumlah DP (Rp)</label>
          <input type="number" name="jumlah_dp" id="inputDP" class="form-control" value="0" min="0">
          <div class="small text-danger mt-1">Sisa Bayar: <span class="fw-bold" id="labelSisa">Rp 0</span></div>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 py-3 fs-5 mt-2">
          <i class="bi bi-check-lg me-1"></i> Simpan Transaksi
        </button>
      </div>
    </div>
  </div>
</form>

<!-- Modal Tambah Pelanggan Cepat -->
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
            <label class="form-label fw-semibold">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat rumah / apartemen..."></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
              <option value="">-- Pilih --</option>
              <option value="L">Laki-laki</option>
              <option value="P">Perempuan</option>
            </select>
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

@push('scripts')
<script>
  function formatRupiah(val) {
    return 'Rp ' + Math.max(0, Math.round(val)).toLocaleString('id-ID');
  }

  function hitungTotal() {
    const radioSelected = document.querySelector('input[name="layanan_id"]:checked');
    const harga = radioSelected ? parseFloat(radioSelected.dataset.harga) : 0;
    const hari = radioSelected ? parseInt(radioSelected.dataset.hari) : 2;
    const berat = parseFloat(document.getElementById('inputBerat').value) || 0;
    const diskon = parseFloat(document.getElementById('inputDiskon').value) || 0;

    const subtotal = harga * berat;
    const total = Math.max(0, subtotal - diskon);

    document.getElementById('labelHargaKg').textContent = formatRupiah(harga);
    document.getElementById('labelBerat').textContent = berat + ' kg';
    document.getElementById('labelSubtotal').textContent = formatRupiah(subtotal);
    document.getElementById('labelTotal').textContent = formatRupiah(total);

    // Hitung Sisa DP
    const statusBayar = document.getElementById('statusBayarSelect').value;
    const groupDP = document.getElementById('groupDP');
    if (statusBayar === 'DP') {
      groupDP.classList.remove('d-none');
      const dp = parseFloat(document.getElementById('inputDP').value) || 0;
      const sisa = Math.max(0, total - dp);
      document.getElementById('labelSisa').textContent = formatRupiah(sisa);
    } else {
      groupDP.classList.add('d-none');
    }

    // Update target date automatically
    const tglEst = new Date();
    tglEst.setDate(tglEst.getDate() + hari);
    const yyyy = tglEst.getFullYear();
    const mm = String(tglEst.getMonth() + 1).padStart(2, '0');
    const dd = String(tglEst.getDate()).padStart(2, '0');
    document.getElementById('inputEstimasi').value = `${yyyy}-${mm}-${dd}`;
  }

  // Event Listeners
  document.querySelectorAll('input[name="layanan_id"]').forEach(r => {
    r.addEventListener('change', hitungTotal);
  });
  document.getElementById('inputBerat').addEventListener('input', hitungTotal);
  document.getElementById('inputDiskon').addEventListener('input', hitungTotal);
  document.getElementById('inputDP').addEventListener('input', hitungTotal);
  document.getElementById('statusBayarSelect').addEventListener('change', hitungTotal);

  // Quick weight chips
  document.querySelectorAll('.chip-weight').forEach(b => {
    b.addEventListener('click', function() {
      document.getElementById('inputBerat').value = this.dataset.weight;
      hitungTotal();
    });
  });

  // Highlight selected service card
  document.querySelectorAll('.service-option').forEach(card => {
    card.addEventListener('click', function() {
      const radio = this.querySelector('input[type="radio"]');
      if (radio) {
        radio.checked = true;
        hitungTotal();
      }
    });
  });

  // Run on start
  hitungTotal();
</script>
@endpush
