<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - LaundryPro Sistem Manajemen</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    * {
      box-sizing: border-box;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(30, 41, 59) 90.2%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
    }

    .login-box {
      width: 100%;
      max-width: 480px;
      background: #ffffff;
      border-radius: 24px;
      padding: 2.5rem 2.25rem;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
    }

    .brand-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #4f46e5, #06b6d4);
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      margin: 0 auto 1rem;
      box-shadow: 0 10px 25px rgba(79, 70, 229, 0.35);
    }

    .btn-login {
      background: linear-gradient(135deg, #4f46e5, #3b82f6);
      border: none;
      color: #fff;
      font-weight: 700;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      width: 100%;
      font-size: 1rem;
      transition: all 0.2s ease;
      box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }

    .btn-login:hover {
      background: linear-gradient(135deg, #4338ca, #2563eb);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
    }

    .demo-pill {
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      border-radius: 14px;
      padding: 0.85rem 1rem;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.2s;
      text-align: left;
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
      color: #334155;
    }

    .demo-pill:hover {
      background: #eff6ff;
      border-color: #6366f1;
      color: #1e40af;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.15);
    }

    .form-control {
      padding: 0.75rem 1rem;
      border-radius: 12px;
      border: 1.5px solid #e2e8f0;
    }
    .form-control:focus {
      border-color: #6366f1;
      box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }
  </style>
</head>
<body>
@php
  $logo = \App\Models\Pengaturan::get('logo', '🧺');
  $namaLaundry = \App\Models\Pengaturan::get('nama_laundry', 'LaundryPro');
  $slogan = \App\Models\Pengaturan::get('slogan', 'Sistem Manajemen Laundry Profesional & Modern');
  $isImg = str_starts_with($logo, '/') || str_starts_with($logo, 'http') || str_contains($logo, '.');
@endphp

  <div class="login-box">
    <div class="text-center mb-4">
      @if($isImg)
        <div class="mb-3">
          <img src="{{ $logo }}" alt="Logo {{ $namaLaundry }}" class="rounded-3 shadow-sm" style="max-height: 70px; max-width: 140px; object-fit: contain;">
        </div>
      @else
        <div class="brand-icon">{{ $logo }}</div>
      @endif
      <h3 class="fw-bold mb-1">{{ $namaLaundry }}</h3>
      <p class="text-muted small">{{ $slogan }}</p>
    </div>

    @if($errors->any())
      <div class="alert alert-danger border-0 py-2 px-3 small rounded-3 mb-3">
        <i class="bi bi-exclamation-circle me-1"></i> {{ $errors->first() }}
      </div>
    @endif

    @if(session('success'))
      <div class="alert alert-success border-0 py-2 px-3 small rounded-3 mb-3">
        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="mb-4">
      @csrf

      <div class="mb-3">
        <label class="form-label fw-semibold small text-secondary">Email Address</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
            <i class="bi bi-envelope"></i>
          </span>
          <input type="email" name="email" class="form-control border-start-0 ps-0" placeholder="nama@laundrypro.com" value="{{ old('email') }}" required autofocus style="border-radius: 0 12px 12px 0;">
        </div>
      </div>

      <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <label class="form-label fw-semibold small text-secondary mb-0">Password</label>
        </div>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
            <i class="bi bi-lock"></i>
          </span>
          <input type="password" name="password" id="passwordInput" class="form-control border-start-0 ps-0" placeholder="Masukkan password Anda" required style="border-radius: 0 12px 12px 0;">
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
          <label class="form-check-label small text-secondary" for="remember">
            Ingat saya di perangkat ini
          </label>
        </div>
      </div>

      <button type="submit" class="btn btn-login py-3">
        Masuk ke Sistem <i class="bi bi-box-arrow-in-right ms-2"></i>
      </button>
    </form>

    <div class="text-center pt-2 text-muted" style="font-size: 0.8rem;">
      Silakan hubungi Owner jika lupa akun atau password.
    </div>
  </div>

</body>
</html>
