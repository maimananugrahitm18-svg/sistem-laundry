@extends('layouts.app')

@section('title', 'Edit Pelanggan ' . $pelanggan->nama)
@section('page-title', 'Edit Data Pelanggan')

@section('content')
<div class="card card-custom p-4" style="max-width: 700px; margin: 0 auto;">
  <form action="{{ route('pelanggan.update', $pelanggan) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
      <h5 class="fw-bold mb-0">Ubah Data Pelanggan</h5>
      <span class="badge bg-light text-primary border font-monospace">{{ $pelanggan->kode_pelanggan }}</span>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
      <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
             value="{{ old('nama', $pelanggan->nama) }}" required>
      @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <label class="form-label fw-semibold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
        <input type="tel" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" 
               value="{{ old('no_hp', $pelanggan->no_hp) }}" required>
        @error('no_hp') <div class="invalid-feedback">{{ $message }}</div> @enderror
      </div>
      <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $pelanggan->email) }}">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Alamat</label>
      <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $pelanggan->alamat) }}</textarea>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Jenis Kelamin</label>
      <select name="jenis_kelamin" class="form-select">
        <option value="">-- Pilih --</option>
        <option value="L" {{ old('jenis_kelamin', $pelanggan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
        <option value="P" {{ old('jenis_kelamin', $pelanggan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">Catatan Pelanggan</label>
      <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $pelanggan->catatan) }}</textarea>
    </div>

    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
      <a href="{{ route('pelanggan.index') }}" class="btn btn-light">Batal</a>
      <button type="submit" class="btn btn-primary-custom">Simpan Perubahan</button>
    </div>
  </form>
</div>
@endsection
