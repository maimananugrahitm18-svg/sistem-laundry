<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') - {{ config('app.name', 'LaundryPro') }}</title>

  <!-- Google Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

  <style>
    :root {
      --primary: #4f46e5;
      --primary-dark: #3730a3;
      --primary-light: #e0e7ff;
      --secondary: #06b6d4;
      --dark-sidebar: #0f172a;
      --dark-sidebar-hover: #1e293b;
      --sidebar-width: 260px;
      --bg-body: #f8fafc;
      --card-bg: #ffffff;
      --text-main: #1e293b;
      --text-muted: #64748b;
      --border-color: #e2e8f0;
      --radius-lg: 16px;
      --radius-md: 10px;
    }

    * {
      box-sizing: border-box;
      font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
      background-color: var(--bg-body);
      color: var(--text-main);
      overflow-x: hidden;
      min-height: 100vh;
    }

    /* Sidebar Styling */
    .sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      width: var(--sidebar-width);
      background-color: var(--dark-sidebar);
      color: #fff;
      z-index: 1040;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      box-shadow: 4px 0 20px rgba(0,0,0,0.06);
    }

    .sidebar-brand {
      padding: 1.5rem 1.25rem;
      display: flex;
      align-items: center;
      gap: 12px;
      border-bottom: 1px solid rgba(255,255,255,0.08);
      text-decoration: none;
      color: #fff;
    }

    .brand-icon {
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, #6366f1, #3b82f6);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    }

    .brand-title {
      font-weight: 800;
      font-size: 1.15rem;
      letter-spacing: -0.5px;
      margin: 0;
      line-height: 1.2;
    }

    .brand-sub {
      font-size: 0.72rem;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .sidebar-menu {
      padding: 1rem 0.75rem;
      flex-grow: 1;
      overflow-y: auto;
    }

    .menu-header {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 1.2px;
      color: #64748b;
      padding: 0.75rem 0.75rem 0.35rem;
      font-weight: 700;
    }

    .nav-item {
      margin-bottom: 4px;
    }

    .nav-link-custom {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 0.7rem 0.85rem;
      color: #cbd5e1;
      text-decoration: none;
      border-radius: var(--radius-md);
      font-size: 0.92rem;
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .nav-link-custom i {
      font-size: 1.15rem;
      width: 22px;
      text-align: center;
      color: #94a3b8;
      transition: color 0.2s ease;
    }

    .nav-link-custom:hover {
      background-color: var(--dark-sidebar-hover);
      color: #fff;
    }

    .nav-link-custom:hover i {
      color: #60a5fa;
    }

    .nav-link-custom.active {
      background: linear-gradient(135deg, #4f46e5, #3b82f6);
      color: #ffffff;
      font-weight: 600;
      box-shadow: 0 4px 15px rgba(79, 70, 229, 0.35);
    }

    .nav-link-custom.active i {
      color: #fff;
    }

    .sidebar-user {
      padding: 1rem;
      border-top: 1px solid rgba(255,255,255,0.08);
      background-color: rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .user-avatar {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      background: linear-gradient(135deg, #3b82f6, #06b6d4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #fff;
      font-size: 0.95rem;
    }

    /* Main Content Wrapper */
    .main-wrapper {
      margin-left: var(--sidebar-width);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      transition: margin-left 0.3s ease;
    }

    /* Top Navbar */
    .top-header {
      height: 70px;
      background: #ffffff;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 1.75rem;
      position: sticky;
      top: 0;
      z-index: 1020;
    }

    .role-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .role-owner { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
    .role-kasir { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }

    /* Page Content */
    .page-content {
      padding: 1.75rem;
      flex-grow: 1;
    }

    /* Cards */
    .card-custom {
      background: var(--card-bg);
      border-radius: var(--radius-lg);
      border: 1px solid var(--border-color);
      box-shadow: 0 2px 12px rgba(0,0,0,0.03);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card {
      border: 1px solid var(--border-color);
      border-radius: var(--radius-lg);
      padding: 1.35rem;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: all 0.25s ease;
    }
    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    }
    .stat-icon {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
    }

    /* Badges */
    .badge-status {
      padding: 6px 12px;
      font-weight: 600;
      font-size: 0.8rem;
      border-radius: 30px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    .badge-baru { background-color: #e0e7ff; color: #4338ca; }
    .badge-diproses { background-color: #fef3c7; color: #b45309; }
    .badge-dicuci { background-color: #e0f2fe; color: #0369a1; }
    .badge-disetrika { background-color: #f3e8ff; color: #6b21a8; }
    .badge-siap-diambil { background-color: #dcfce7; color: #15803d; }
    .badge-selesai { background-color: #f1f5f9; color: #334155; }
    .badge-dibatalkan { background-color: #fee2e2; color: #b91c1c; }

    .badge-lunas { background-color: #dcfce7; color: #166534; font-weight: 700; }
    .badge-dp { background-color: #fef9c3; color: #854d0e; font-weight: 700; }
    .badge-belum-bayar { background-color: #fee2e2; color: #991b1b; font-weight: 700; }

    /* Button styles */
    .btn-primary-custom {
      background: linear-gradient(135deg, #4f46e5, #3b82f6);
      color: #fff;
      border: none;
      font-weight: 600;
      border-radius: var(--radius-md);
      padding: 0.55rem 1.25rem;
      transition: all 0.2s ease;
      box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
    }
    .btn-primary-custom:hover {
      background: linear-gradient(135deg, #4338ca, #2563eb);
      color: #fff;
      transform: translateY(-1px);
      box-shadow: 0 6px 16px rgba(79, 70, 229, 0.35);
    }

    /* Responsive */
    @media (max-width: 991.98px) {
      .sidebar {
        transform: translateX(-100%);
      }
      .sidebar.show {
        transform: translateX(0);
      }
      .main-wrapper {
        margin-left: 0;
      }
    }
  </style>
  @stack('styles')
</head>
<body>

  <!-- Sidebar -->
@php
  $appLogo = \App\Models\Pengaturan::get('logo', '🧺');
  $appNama = \App\Models\Pengaturan::get('nama_laundry', 'LaundryPro');
  $appIsImg = str_starts_with($appLogo, '/') || str_starts_with($appLogo, 'http') || str_contains($appLogo, '.');
@endphp
  <aside class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
      @if($appIsImg)
        <img src="{{ $appLogo }}" alt="Logo" class="rounded-2 shadow-sm" style="width: 40px; height: 40px; object-fit: contain; background: #fff; padding: 2px;">
      @else
        <div class="brand-icon">{{ $appLogo }}</div>
      @endif
      <div class="overflow-hidden">
        <div class="brand-title text-truncate" style="max-width: 170px;">{{ $appNama }}</div>
        <div class="brand-sub">{{ auth()->user()->isOwner() ? 'Mode Owner (Admin)' : 'Loket Kasir Petugas' }}</div>
      </div>
    </a>

    <div class="sidebar-menu">
      @if(auth()->user()->isOwner())
        <!-- ================= OWNER / ADMIN MENU ================= -->
        <div class="menu-header">Menu Utama (Owner)</div>

        <div class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-pie-chart-fill"></i>
            <span>Dashboard & Omset</span>
          </a>
        </div>

        <div class="nav-item">
          <a href="{{ route('transaksi.index') }}" class="nav-link-custom {{ request()->routeIs('transaksi.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff"></i>
            <span>Kelola Transaksi</span>
          </a>
        </div>

        <div class="nav-item">
          <a href="{{ route('pelanggan.index') }}" class="nav-link-custom {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Data Pelanggan</span>
          </a>
        </div>

        <div class="nav-item">
          <a href="{{ route('layanan.index') }}" class="nav-link-custom {{ request()->routeIs('layanan.*') ? 'active' : '' }}">
            <i class="bi bi-tags-fill"></i>
            <span>Tarif & Layanan</span>
          </a>
        </div>

        <div class="menu-header mt-3">Laporan & Pengaturan</div>

        <div class="nav-item">
          <a href="{{ route('laporan.index') }}" class="nav-link-custom {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line-fill text-warning"></i>
            <span>Laporan Keuangan</span>
          </a>
        </div>

        <div class="nav-item">
          <a href="{{ route('pengaturan.index') }}" class="nav-link-custom {{ request()->routeIs('pengaturan.*') ? 'active' : '' }}">
            <i class="bi bi-gear-fill"></i>
            <span>Pengaturan & Kasir</span>
          </a>
        </div>
      @else
        <!-- ================= KASIR MENU (SIMPEL & PRAKTIS) ================= -->
        <div class="menu-header">Loket Kasir Laundry</div>

        <div class="nav-item">
          <a href="{{ route('transaksi.create') }}" class="nav-link-custom {{ request()->routeIs('transaksi.create') ? 'active' : '' }}" style="background: rgba(99, 102, 241, 0.2); border: 1px solid rgba(99,102,241,0.4);">
            <i class="bi bi-plus-circle-fill text-primary"></i>
            <span class="fw-bold text-white">➕ Buat Pesanan Baru</span>
          </a>
        </div>

        <div class="nav-item mt-2">
          <a href="{{ route('transaksi.index') }}" class="nav-link-custom {{ request()->routeIs('transaksi.index') || request()->routeIs('transaksi.show') ? 'active' : '' }}">
            <i class="bi bi-card-checklist"></i>
            <span>Daftar Cucian & Status</span>
          </a>
        </div>

        <div class="nav-item">
          <a href="{{ route('pelanggan.index') }}" class="nav-link-custom {{ request()->routeIs('pelanggan.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i>
            <span>Data Pelanggan</span>
          </a>
        </div>

        <div class="p-3 mt-4 rounded-3 text-white-50 small" style="background: rgba(255,255,255,0.05); border: 1px dashed rgba(255,255,255,0.15);">
          <div class="fw-bold text-white mb-1"><i class="bi bi-info-circle me-1"></i> Mode Kasir:</div>
          <div>Anda berwenang menginput cucian baru, mendata pelanggan, cetak struk, dan update proses cuci.</div>
        </div>
      @endif

      <!-- Fast Action Shortcut Button -->
      <div class="px-2 mt-4">
        <a href="{{ route('transaksi.create') }}" class="btn btn-primary-custom w-100 py-2 d-flex align-items-center justify-content-center gap-2">
          <i class="bi bi-plus-circle-fill"></i>
          <span>Order Cucian Baru</span>
        </a>
      </div>
    </div>

    <!-- User Profile Footer -->
    <div class="sidebar-user">
      <div class="user-avatar" style="{{ auth()->user()->isOwner() ? 'background: linear-gradient(135deg, #d97706, #f59e0b);' : '' }}">
        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
      </div>
      <div class="flex-grow-1 overflow-hidden">
        <div class="text-white text-truncate fw-semibold" style="font-size: 0.88rem;">{{ auth()->user()->name ?? 'User' }}</div>
        <span class="role-badge role-{{ auth()->user()->role ?? 'kasir' }}" style="font-size: 0.68rem; padding: 2px 7px;">
          {{ auth()->user()->isOwner() ? 'OWNER / ADMIN' : 'KASIR' }}
        </span>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Keluar">
          <i class="bi bi-box-arrow-right fs-5"></i>
        </button>
      </form>
    </div>
  </aside>

  <!-- Main Wrapper -->
  <div class="main-wrapper">
    <!-- Top Header -->
    <header class="top-header">
      <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light d-lg-none" id="btnToggleSidebar">
          <i class="bi bi-list fs-4"></i>
        </button>
        <div>
          <h5 class="mb-0 fw-bold">@yield('page-title', 'LaundryPro')</h5>
          <small class="text-muted d-none d-sm-inline">
            <i class="bi bi-calendar3 me-1"></i> {{ now()->translatedFormat('l, d F Y') }}
          </small>
        </div>
      </div>

      <!-- User Logged-in Info & Action -->
      <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center gap-2 px-3 py-1 bg-light rounded-pill border">
          <i class="bi bi-person-circle fs-5 text-secondary"></i>
          <div class="d-none d-sm-block text-start">
            <span class="fw-bold small d-block">{{ auth()->user()->name }}</span>
            <span class="role-badge role-{{ auth()->user()->role ?? 'kasir' }}" style="font-size: 0.65rem; padding: 1px 6px;">
              {{ auth()->user()->isOwner() ? 'OWNER / ADMIN' : 'KASIR PETUGAS' }}
            </span>
          </div>
        </div>

        <a href="{{ route('transaksi.create') }}" class="btn btn-sm btn-primary-custom d-none d-sm-inline-flex align-items-center gap-2">
          <i class="bi bi-plus-lg"></i>
          <span>Transaksi Baru</span>
        </a>

        <form action="{{ route('logout') }}" method="POST" class="d-inline m-0">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" title="Keluar / Ganti Akun" onclick="return confirm('Apakah Anda yakin ingin keluar / logout?')">
            <i class="bi bi-box-arrow-right"></i>
            <span class="d-none d-md-inline">Keluar</span>
          </button>
        </form>
      </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
      <!-- Flash Alert -->
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
          <i class="bi bi-check-circle-fill fs-5"></i>
          <div>{{ session('success') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
          <i class="bi bi-exclamation-triangle-fill fs-5"></i>
          <div>{{ session('error') }}</div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-3 px-4 bg-white border-top text-center text-muted" style="font-size: 0.85rem;">
      &copy; {{ date('Y') }} <strong>LaundryPro</strong> &bull; Sistem Manajemen Laundry Terpadu Profesional
    </footer>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const btnToggle = document.getElementById('btnToggleSidebar');
    const sidebar = document.getElementById('sidebar');
    if (btnToggle && sidebar) {
      btnToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
      });
    }
  </script>
  @stack('scripts')
</body>
</html>
