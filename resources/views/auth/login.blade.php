<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Live Demo - LaundryPro Sistem Manajemen Laundry</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 1.5rem 1rem;
      position: relative;
      overflow-x: hidden;
    }

    /* Ambient Glow Blobs */
    body::before, body::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      filter: blur(90px);
      opacity: 0.25;
      z-index: 1;
      pointer-events: none;
    }
    body::before {
      width: 420px;
      height: 420px;
      background: radial-gradient(circle, #6366f1, transparent);
      top: -100px;
      left: -100px;
    }
    body::after {
      width: 380px;
      height: 380px;
      background: radial-gradient(circle, #06b6d4, transparent);
      bottom: -100px;
      right: -100px;
    }

    /* Top Floating Demo Bar */
    .demo-top-bar {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 520px;
      background: rgba(255, 255, 255, 0.08);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 16px;
      padding: 10px 16px;
      margin-bottom: 1.25rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      color: #fff;
    }

    .live-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(16, 185, 129, 0.2);
      border: 1px solid #10b981;
      color: #34d399;
      font-size: 0.76rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
      letter-spacing: 0.5px;
    }
    .pulse-dot {
      width: 8px;
      height: 8px;
      background: #10b981;
      border-radius: 50%;
      box-shadow: 0 0 8px #10b981;
      animation: pulse 1.8s infinite;
    }
    @keyframes pulse {
      0% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.4; transform: scale(1.3); }
      100% { opacity: 1; transform: scale(1); }
    }

    .btn-buy-modal {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #fff;
      font-weight: 700;
      font-size: 0.82rem;
      padding: 6px 14px;
      border-radius: 10px;
      border: none;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
      box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
      text-decoration: none;
    }
    .btn-buy-modal:hover {
      background: linear-gradient(135deg, #d97706, #b45309);
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(245, 158, 11, 0.45);
    }

    .login-box {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 520px;
      background: #ffffff;
      border-radius: 24px;
      padding: 2.25rem 2rem;
      box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6);
    }

    .brand-icon {
      width: 58px;
      height: 58px;
      background: linear-gradient(135deg, #4f46e5, #06b6d4);
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      margin: 0 auto 0.75rem;
      box-shadow: 0 8px 20px rgba(79, 70, 229, 0.35);
    }

    .btn-login {
      background: linear-gradient(135deg, #4f46e5, #3b82f6);
      border: none;
      color: #fff;
      font-weight: 700;
      padding: 0.75rem 1.5rem;
      border-radius: 12px;
      width: 100%;
      font-size: 0.95rem;
      transition: all 0.2s ease;
      box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
    }

    .btn-login:hover {
      background: linear-gradient(135deg, #4338ca, #2563eb);
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(79, 70, 229, 0.4);
    }

    /* 1-Click Demo Buttons */
    .demo-card {
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      padding: 10px 12px;
      background: #f8fafc;
      transition: all 0.2s;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      text-decoration: none;
      color: #1e293b;
    }
    .demo-card:hover {
      background: #eff6ff;
      border-color: #6366f1;
      transform: translateY(-2px);
      box-shadow: 0 4px 14px rgba(99, 102, 241, 0.15);
      color: #1e40af;
    }

    .form-control {
      padding: 0.75rem 1rem;
      border-radius: 12px;
      border: 1.5px solid #e2e8f0;
    }
    .portfolio-badge {
      background: rgba(99, 102, 241, 0.18);
      border: 1px solid rgba(99, 102, 241, 0.4);
      color: #c7d2fe;
      border-radius: 50px;
      padding: 7px 18px;
      font-size: 13px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 14px;
      transition: all 0.2s ease;
      position: relative;
      z-index: 10;
      text-decoration: none;
    }
    .portfolio-badge:hover {
      background: rgba(99, 102, 241, 0.35);
      color: #ffffff;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
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

  <!-- Badge Portfolio MaimanDev (Balik ke Portofolio) -->
  <a href="https://maimandev.my.id" class="portfolio-badge">
    <span style="color:#10b981;">●</span> Live Showcase Proyek &bull; <strong>maimandev.my.id</strong>
    <i class="fas fa-arrow-up-right-from-square ms-1" style="font-size:11px;"></i>
  </a>

  <div class="login-box">
    <div class="text-center mb-3">
      @if($isImg)
        <div class="mb-2">
          <img src="{{ $logo }}" alt="Logo {{ $namaLaundry }}" class="rounded-3 shadow-sm" style="max-height: 60px; max-width: 140px; object-fit: contain;">
        </div>
      @else
        <div class="brand-icon">{{ $logo }}</div>
      @endif
      <h3 class="fw-bold mb-1 fs-4">{{ $namaLaundry }}</h3>
      <p class="text-muted small mb-0">{{ $slogan }}</p>
    </div>

    <!-- 1-Click Quick Demo Login Section -->
    <div class="mb-3 p-3 rounded-3" style="background: #f1f5f9; border: 1px dashed #cbd5e1;">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="fw-bold text-dark small"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> Demo Cepat 1-Klik Masuk:</span>
        <span class="badge bg-primary text-white" style="font-size: 0.65rem;">Auto-Login</span>
      </div>
      <div class="row g-2">
        <div class="col-6">
          <div class="demo-card" onclick="quickLogin('owner@laundrypro.com', 'owner123')">
            <div>
              <div class="fw-bold small text-dark"><i class="bi bi-shield-check text-warning me-1"></i> Owner</div>
              <div class="text-muted" style="font-size: 0.7rem;">Akses Penuh & Laporan</div>
            </div>
            <i class="bi bi-arrow-right-short fs-5 text-primary"></i>
          </div>
        </div>
        <div class="col-6">
          <div class="demo-card" onclick="quickLogin('kasir@laundrypro.com', 'kasir123')">
            <div>
              <div class="fw-bold small text-dark"><i class="bi bi-person-badge text-primary me-1"></i> Kasir</div>
              <div class="text-muted" style="font-size: 0.7rem;">Input Order & Struk</div>
            </div>
            <i class="bi bi-arrow-right-short fs-5 text-primary"></i>
          </div>
        </div>
      </div>
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

    <form method="POST" action="{{ route('login.post') }}" id="loginForm" class="mb-3">
      @csrf

      <div class="mb-2">
        <label class="form-label fw-semibold small text-secondary mb-1">Email</label>
        <div class="input-group">
          <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 12px 0 0 12px;">
            <i class="bi bi-envelope"></i>
          </span>
          <input type="email" name="email" id="emailInput" class="form-control border-start-0 ps-0" placeholder="nama@laundrypro.com" value="{{ old('email') }}" required autofocus style="border-radius: 0 12px 12px 0;">
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
          <input type="password" name="password" id="passwordInput" class="form-control border-start-0 ps-0" placeholder="Masukkan password" required style="border-radius: 0 12px 12px 0;">
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
          <label class="form-check-label small text-secondary" for="remember">
            Ingat saya
          </label>
        </div>
      </div>

      <button type="submit" id="btnSubmit" class="btn btn-login py-2 mb-2">
        <span>Masuk ke Sistem</span> <i class="bi bi-box-arrow-in-right ms-1"></i>
      </button>
    </form>

    <div class="text-center pt-1 border-top">
      <button type="button" class="btn btn-link text-decoration-none text-muted small p-0" data-bs-toggle="modal" data-bs-target="#modalBeliSourceCode">
        Tertarik memiliki website ini? <strong class="text-success"><i class="fas fa-shopping-cart me-1"></i>Beli Lisensi</strong>
      </button>
    </div>
  </div>

  <!-- MODAL BELI SOURCE CODE / PEMBAYARAN -->
  <div class="modal fade" id="modalBeliSourceCode" tabindex="-1" aria-labelledby="modalBeliSourceCodeLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        <div class="modal-header border-0 p-4 pb-3" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); color: #fff;">
          <div class="d-flex align-items-center gap-3">
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,.18); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px;">
              💎
            </div>
            <div>
              <h5 class="modal-title fw-bold mb-1" id="modalBeliSourceCodeLabel">Beli / Lisensi Website Laundry Pro Ini</h5>
              <p class="mb-0 small text-light opacity-75">Full source code asli, database MySQL lengkap, dan siap online di hosting</p>
            </div>
          </div>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-4 pt-3">
          <!-- Highlight Value -->
          <div class="alert alert-success border-0 d-flex align-items-center gap-2 mb-4" style="background: #ecfdf5; color: #065f46;">
            <i class="fas fa-shield-alt fa-lg"></i>
            <div class="small">
              <strong>Source Code 100% Bersih & Siap Pakai:</strong> Tanpa enkripsi, bebas dikembangkan kembali (white-label) untuk portofolio, tugas akhir, maupun operasional bisnis laundry Anda.
            </div>
          </div>

          <!-- Pricing Cards -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100 position-relative" style="background: #fafafa; border-color: #e2e8f0;">
                <span class="badge bg-secondary position-absolute top-0 end-0 m-3">Paket Standar</span>
                <h6 class="fw-bold text-dark mb-1">Paket Source Code Only</h6>
                <div class="fs-4 fw-bold text-primary mb-2">Rp 199.000</div>
                <ul class="list-unstyled small text-muted mb-3" style="line-height: 1.8;">
                  <li><i class="fas fa-check text-success me-2"></i>Full Source Code (Laravel + Bootstrap 5)</li>
                  <li><i class="fas fa-check text-success me-2"></i>Database MySQL (File `.sql` + Seeder)</li>
                  <li><i class="fas fa-check text-success me-2"></i>Cetak Struk Thermal 58mm/80mm & PDF</li>
                  <li><i class="fas fa-check text-success me-2"></i>Laporan Keuangan, Omset & Export CSV</li>
                  <li><i class="fas fa-check text-success me-2"></i>Panduan Instalasi Lokal & Hosting</li>
                </ul>
                <a href="https://wa.me/6282289152653?text=Halo%20Maiman%20Dev%2C%20saya%20tertarik%20membeli%20*Paket%20Source%20Code%20Sistem%20Laundry%20Pro%20(Rp%20199.000)*%20dari%20demo%20maimandev.my.id.%20Bagaimana%20prosedur%20pembayarannya%3F" target="_blank" class="btn btn-outline-primary btn-sm w-100 fw-bold">
                  <i class="fab fa-whatsapp me-1"></i> Beli Paket Ini
                </a>
              </div>
            </div>

            <div class="col-md-6">
              <div class="border rounded-3 p-3 h-100 position-relative shadow-sm" style="background: #f8fafc; border: 2px solid #6366f1;">
                <span class="badge bg-primary position-absolute top-0 end-0 m-3">Paling Diminati ⭐</span>
                <h6 class="fw-bold text-dark mb-1">Paket Siap Pakai (Online)</h6>
                <div class="fs-4 fw-bold text-success mb-2">Rp 399.000</div>
                <ul class="list-unstyled small text-muted mb-3" style="line-height: 1.8;">
                  <li><i class="fas fa-check text-success me-2"></i>Semua isi Paket Source Code</li>
                  <li><i class="fas fa-check text-success me-2"></i><strong>Dibantu Pasang di Hosting Anda (cPanel)</strong></li>
                  <li><i class="fas fa-check text-success me-2"></i>Setup Database & Konfigurasi Online</li>
                  <li><i class="fas fa-check text-success me-2"></i>Garansi Website Berjalan 100%</li>
                  <li><i class="fas fa-check text-success me-2"></i>Gratis Konsultasi & Setup Custom Logo</li>
                </ul>
                <a href="https://wa.me/6282289152653?text=Halo%20Maiman%20Dev%2C%20saya%20tertarik%20membeli%20*Paket%20Siap%20Pakai%20Sistem%20Laundry%20Pro%20(Rp%20399.000)*%20dari%20demo%20maimandev.my.id.%20Mohon%20info%20pembayaran%20dan%20setup-nya." target="_blank" class="btn btn-success btn-sm w-100 fw-bold">
                  <i class="fab fa-whatsapp me-1"></i> Beli & Dibantu Pasang
                </a>
              </div>
            </div>
          </div>

          <!-- Metode Pembayaran -->
          <div class="border rounded-3 p-3 bg-white">
            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-credit-card text-success me-2"></i>Pilihan Metode Pembayaran (Payment):</h6>
            <div class="row g-3 small">
              <div class="col-md-6">
                <div class="p-2 border rounded-2 bg-light">
                  <div class="text-muted fw-semibold">Transfer Bank / Virtual Account:</div>
                  <div class="fw-bold text-dark fs-6 mt-1">BCA / Mandiri / BRI / SeaBank</div>
                  <div class="text-muted small mt-1">Nomor Rekening dikirimkan otomatis saat konfirmasi via WhatsApp.</div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="p-2 border rounded-2 bg-light">
                  <div class="text-muted fw-semibold">QRIS & E-Wallet Instan:</div>
                  <div class="fw-bold text-dark fs-6 mt-1">QRIS / DANA / GoPay / OVO / ShopeePay</div>
                  <div class="text-muted small mt-1">Bisa scan QRIS seluruh perbankan & dompet digital tanpa biaya admin.</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="modal-footer border-0 p-4 pt-0 d-flex justify-content-between align-items-center">
          <a href="https://maimandev.my.id" target="_blank" class="text-decoration-none small text-muted">
            <i class="fas fa-external-link-alt me-1"></i> Portofolio Developer (maimandev.my.id)
          </a>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
            <a href="https://wa.me/6282289152653?text=Halo%20Maiman%20Dev%2C%20saya%20ingin%20tanya-tanya%20tentang%20website%20Sistem%20Laundry%20Pro%20dari%20demo%20maimandev.my.id." target="_blank" class="btn btn-success btn-sm px-4 fw-bold">
              <i class="fab fa-whatsapp me-1"></i> Hubungi WhatsApp
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function quickLogin(email, pass) {
      document.getElementById('emailInput').value = email;
      document.getElementById('passwordInput').value = pass;
      const btn = document.getElementById('btnSubmit');
      if (btn) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Masuk Otomatis...';
      }
      document.getElementById('loginForm').submit();
    }
  </script>
</body>
</html>
