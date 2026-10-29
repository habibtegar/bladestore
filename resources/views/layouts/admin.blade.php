<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BladeStore - {{ trim(preg_replace('/^(BladeStore\s*[-—]\s*|\s*[-—]\s*BladeStore.*$)/i', '', View::yieldContent('title') ?: 'Admin Dashboard')) }}</title>

    <!-- Favicon BladeStore Logo -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #0866FF;
            --primary-hover: #0052CC;
            --primary-subtle: #EEF4FF;
            --sidebar-bg: #0F172A;
            --sidebar-hover: #1E293B;
            --sidebar-active: #1E293B;
            --sidebar-border: #1E293B;
            --charcoal: #111827;
            --off-white: #F8FAFC;
            --border-color: #E2E8F0;
            --border-strong: #CBD5E1;
            --text-heading: #0F172A;
            --text-body: #334155;
            --text-muted: #64748B;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--off-white);
            color: var(--text-body);
            min-height: 100vh;
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 270px;
            background-color: var(--sidebar-bg);
            color: #94A3B8;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.25s ease;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .admin-brand {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            text-decoration: none;
        }

        .admin-brand .brand-badge {
            background: linear-gradient(135deg, var(--primary) 0%, #2563EB 100%);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(8, 102, 245, 0.35);
        }

        .admin-brand-title {
            color: #ffffff;
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.03em;
            line-height: 1.2;
        }

        .admin-brand-subtitle {
            color: #64748B;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }

        .sidebar-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748B;
            padding: 1.25rem 1.5rem 0.5rem;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0 0.75rem;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 0.9rem;
            border-radius: 8px;
            color: #94A3B8;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.15s ease;
        }

        .sidebar-nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
        }

        .sidebar-nav-link.active {
            color: #ffffff;
            background-color: var(--primary);
            box-shadow: 0 4px 12px rgba(8, 102, 245, 0.3);
        }

        .sidebar-nav-link.active .badge-nav {
            background-color: #ffffff !important;
            color: var(--primary) !important;
        }

        .sidebar-nav-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-nav-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Sidebar Footer / User section */
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            background-color: rgba(15, 23, 42, 0.6);
        }

        /* Main Content Layout */
        .admin-wrapper {
            margin-left: 270px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 270px);
            min-width: 0;
            overflow-x: hidden;
            transition: all 0.25s ease;
        }

        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
            }
            .admin-wrapper {
                margin-left: 0;
                width: 100%;
                min-width: 0;
            }
        }

        /* Top Bar */
        .admin-topbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        /* Metric Cards */
        .stat-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            background-color: #ffffff;
            padding: 1.25rem 1.5rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        }

        .stat-card:hover {
            transform: translateY(-2px);
            border-color: var(--border-strong);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.04);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        /* Tables */
        .table-admin {
            margin-bottom: 0;
        }

        .table-admin thead th {
            background-color: #F8FAFC;
            color: #64748B;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .table-admin tbody td {
            padding: 0.95rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #F1F5F9;
        }

        .table-admin tbody tr:hover {
            background-color: #F8FAFC;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(8, 102, 245, 0.2);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #ffffff;
            transform: translateY(-1px);
        }

        .x-small { font-size: 0.75rem; }

        /* Admin Pagination Styling */
        .pagination .page-link {
            color: #475569;
            background-color: #FFFFFF;
            border: 1px solid #E2E8F0;
            font-weight: 600;
            font-size: 0.8125rem;
            transition: all 0.15s ease;
            min-width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px !important;
        }

        .pagination .page-link:hover {
            color: var(--primary);
            background-color: #F8FAFC;
            border-color: #CBD5E1;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #FFFFFF;
            box-shadow: 0 2px 6px rgba(8, 102, 245, 0.25);
        }

        .pagination .page-item.disabled .page-link {
            color: #94A3B8;
            background-color: #F8FAFC;
            border-color: #E2E8F0;
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebarBackdrop" class="d-lg-none position-fixed inset-0 bg-dark opacity-50 z-3" style="display: none; inset: 0;" onclick="toggleAdminSidebar()"></div>

    <!-- Admin Sidebar -->
    <aside class="admin-sidebar" id="adminSidebar">
        <!-- Brand Header -->
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <span class="brand-badge">
                <i class="bi bi-shield-lock-fill"></i>
            </span>
            <div>
                <div class="admin-brand-title">BLADE<span style="color: #60A5FA;">STORE</span></div>
                <div class="admin-brand-subtitle">Management Suite</div>
            </div>
        </a>

        <!-- Navigation Section -->
        <div class="sidebar-section-title">Menu Utama</div>
        <ul class="sidebar-nav">
            <!-- Dashboard Admin -->
            <li>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="sidebar-nav-content">
                        <i class="bi bi-grid-1x2-fill sidebar-nav-icon"></i>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>

            <!-- Kelola Pesanan -->
            @php
                $pendingOrdersCount = \App\Models\Order::where('status', 'pending')->count();
            @endphp
            <li>
                <a href="{{ route('admin.orders.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <div class="sidebar-nav-content">
                        <i class="bi bi-receipt-cutoff sidebar-nav-icon"></i>
                        <span>Kelola Pesanan</span>
                    </div>
                    @if($pendingOrdersCount > 0)
                        <span class="badge bg-warning text-dark rounded-pill x-small px-2 badge-nav">{{ $pendingOrdersCount }} pending</span>
                    @endif
                </a>
            </li>

            <!-- Omset & Keuangan -->
            <li>
                <a href="{{ route('admin.revenue.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}">
                    <div class="sidebar-nav-content">
                        <i class="bi bi-wallet2 sidebar-nav-icon"></i>
                        <span>Omset & Keuangan</span>
                    </div>
                </a>
            </li>

            <!-- Kelola Produk -->
            @php
                $totalProductsCount = \App\Models\Product::count();
            @endphp
            <li>
                <a href="{{ route('admin.products.index') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products.index') || request()->routeIs('admin.products.edit') ? 'active' : '' }}">
                    <div class="sidebar-nav-content">
                        <i class="bi bi-tags sidebar-nav-icon"></i>
                        <span>Katalog Produk</span>
                    </div>
                    <span class="badge bg-secondary rounded-pill x-small px-2 badge-nav">{{ $totalProductsCount }}</span>
                </a>
            </li>

            <!-- Tambah Produk Baru -->
            <li>
                <a href="{{ route('admin.products.create') }}" class="sidebar-nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                    <div class="sidebar-nav-content">
                        <i class="bi bi-plus-circle sidebar-nav-icon"></i>
                        <span>Tambah Produk</span>
                    </div>
                </a>
            </li>
        </ul>

        <div class="sidebar-section-title">Navigasi Luar</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('products.index') }}" target="_blank" class="sidebar-nav-link">
                    <div class="sidebar-nav-content">
                        <i class="bi bi-shop-window sidebar-nav-icon"></i>
                        <span>Lihat Toko Publik</span>
                    </div>
                    <i class="bi bi-box-arrow-up-right x-small"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('orders.index') }}" class="sidebar-nav-link">
                    <div class="sidebar-nav-content">
                        <i class="bi bi-person-check sidebar-nav-icon"></i>
                        <span>Pesanan Akun Saya</span>
                    </div>
                </a>
            </li>
        </ul>

        <!-- Sidebar Footer: Admin User Profile -->
        <div class="sidebar-footer">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white fw-bold" style="width: 34px; height: 34px; font-size: 0.85rem;">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden" style="max-width: 130px;">
                        <div class="small fw-bold text-white text-truncate">{{ auth()->user()?->name ?? 'Admin' }}</div>
                        <div class="x-small text-muted text-truncate">{{ auth()->user()?->email ?? 'admin@bladestore.id' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" title="Keluar">
                        <i class="bi bi-box-arrow-right fs-5"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Admin Main Wrapper -->
    <div class="admin-wrapper">
        <!-- Top Navigation Bar -->
        <header class="admin-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none py-1 px-2" type="button" onclick="toggleAdminSidebar()" aria-label="Toggle Sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div>
                    <h5 class="fw-bold text-dark mb-0">@yield('page-title', 'Dashboard')</h5>
                    <div class="text-muted x-small">BladeStore Store Administration Suite</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-light text-secondary border px-3 py-2 small d-none d-sm-inline-flex align-items-center gap-2">
                    <i class="bi bi-clock-history"></i> {{ date('d M Y') }}
                </span>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                    <i class="bi bi-bag-check"></i>
                    <span class="d-none d-sm-inline">Toko Pelanggan</span>
                </a>
            </div>
        </header>

        <!-- Main Body Area -->
        <main class="p-4 p-lg-5 flex-grow-1" style="min-width: 0; max-width: 100%;">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div class="small fw-semibold">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 rounded-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div class="small fw-semibold">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1 fw-bold small">
                        <i class="bi bi-exclamation-octagon-fill"></i> Periksa kembali data form:
                    </div>
                    <ul class="mb-0 ps-3 x-small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                backdrop.style.display = 'none';
            } else {
                sidebar.classList.add('show');
                backdrop.style.display = 'block';
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
