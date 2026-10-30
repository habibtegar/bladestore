@extends('layouts.app')

@section('title', 'Profil Saya - BladeStore')

@push('styles')
<style>
    /* ========================================================
       BLADESTORE CUSTOMER PROFILE DASHBOARD - DESIGN SYSTEM
       ======================================================== */
    :root {
        --profile-sidebar-width: 268px;
        --profile-bg: #F8FAFC;
        --profile-border: #E2E8F0;
        --profile-card-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.02);
        --profile-hover-shadow: 0 10px 25px -4px rgba(8, 102, 255, 0.12), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
    }

    body {
        background-color: var(--profile-bg);
    }

    /* 1. Header Banner */
    .profile-hero-banner {
        background: linear-gradient(135deg, #0F172A 0%, #1E3A8A 50%, #0866FF 100%);
        position: relative;
        overflow: hidden;
        padding: 2.25rem 1rem 3.75rem !important;
        margin-bottom: 0 !important;
    }

    .profile-hero-orb-1 {
        position: absolute;
        top: -60px;
        left: 5%;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, rgba(56, 189, 248, 0) 70%);
        pointer-events: none;
    }

    .profile-hero-orb-2 {
        position: absolute;
        bottom: -70px;
        right: 8%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(8, 102, 255, 0.3) 0%, rgba(8, 102, 255, 0) 70%);
        pointer-events: none;
    }

    /* 2. Main Wrapper Layout */
    .profile-main-container {
        max-width: 1220px;
        margin: -2.5rem auto 3.5rem;
        position: relative;
        z-index: 10;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .profile-dashboard-layout {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }

    /* Desktop Sidebar fixed width */
    @media (min-width: 992px) {
        .profile-sidebar-col {
            flex: 0 0 var(--profile-sidebar-width);
            width: var(--profile-sidebar-width);
            max-width: var(--profile-sidebar-width);
        }

        .profile-content-col {
            flex: 1 1 0%;
            min-width: 0; /* Prevents overflow */
        }
    }

    @media (max-width: 991.98px) {
        .profile-dashboard-layout {
            flex-direction: column;
            gap: 1.25rem;
        }

        .profile-sidebar-col,
        .profile-content-col {
            width: 100%;
        }
    }

    /* 3. Modern White Cards */
    .profile-card {
        background: #FFFFFF;
        border: 1px solid var(--profile-border);
        border-radius: 16px;
        box-shadow: var(--profile-card-shadow);
        padding: 1.5rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-sidebar-card {
        background: #FFFFFF;
        border: 1px solid var(--profile-border);
        border-radius: 16px;
        box-shadow: var(--profile-card-shadow);
        overflow: hidden;
    }

    /* 4. Avatar & Identity Header in Sidebar */
    .profile-avatar-box {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 1.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(8, 102, 255, 0.25);
        border: 3px solid #FFFFFF;
        overflow: hidden;
        flex-shrink: 0;
    }

    .profile-user-name {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.35;
        margin-bottom: 0.2rem;
        word-break: break-word;
    }

    .profile-user-email {
        font-size: 0.8rem;
        color: #64748B;
        line-height: 1.3;
        margin-bottom: 0.65rem;
        word-break: break-all;
    }

    .profile-verified-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.25rem 0.65rem;
        background-color: #EFF6FF;
        color: #0866FF;
        border: 1px solid #BFDBFE;
        border-radius: 9999px;
        font-size: 0.725rem;
        font-weight: 600;
        letter-spacing: 0.01em;
    }

    /* 5. Sidebar Navigation Menu */
    .profile-nav-list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        padding: 1rem;
    }

    .profile-nav-btn {
        width: 100%;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.7rem 0.85rem;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #475569;
        text-decoration: none;
        background: transparent;
        border: 1px solid transparent;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .profile-nav-btn i {
        font-size: 1.15rem;
        color: #64748B;
        width: 22px;
        text-align: center;
        flex-shrink: 0;
        transition: color 0.2s ease;
    }

    .profile-nav-btn:hover {
        background-color: #F1F5F9;
        color: #0866FF;
    }

    .profile-nav-btn:hover i {
        color: #0866FF;
    }

    .profile-nav-btn.active {
        background-color: #EFF6FF !important;
        color: #0866FF !important;
        border-color: #BFDBFE !important;
        box-shadow: 0 1px 3px rgba(8, 102, 255, 0.08);
    }

    .profile-nav-btn.active i {
        color: #0866FF !important;
    }

    /* Mobile Horizontal Tabs Bar */
    @media (max-width: 991.98px) {
        .mobile-profile-tabs-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            overflow-x: auto;
            padding: 0.75rem 1rem;
            background: #FFFFFF;
            border-bottom: 1px solid var(--profile-border);
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .mobile-tab-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.9rem;
            border-radius: 9999px;
            font-size: 0.825rem;
            font-weight: 600;
            color: #475569;
            background: #F1F5F9;
            border: 1px solid #E2E8F0;
            text-decoration: none;
            cursor: pointer;
        }

        .mobile-tab-pill.active {
            background: #0866FF;
            color: #FFFFFF;
            border-color: #0866FF;
        }

        .mobile-tab-pill.active i {
            color: #FFFFFF;
        }
    }

    /* 6. Isolated Logout Button */
    .profile-logout-wrap {
        padding: 0 1rem 1rem;
        border-top: 1px solid var(--profile-border);
        margin-top: 0.5rem;
        padding-top: 0.85rem;
    }

    .profile-logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        padding: 0.65rem 1rem;
        background-color: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FEE2E2;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .profile-logout-btn:hover {
        background-color: #FEE2E2;
        border-color: #FECACA;
        color: #B91C1C;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.12);
    }

    /* 7. Metric Statistic Cards (Equal Dimensions) */
    .stat-metric-card {
        background: #FFFFFF;
        border: 1px solid var(--profile-border);
        border-radius: 14px;
        padding: 1.25rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        height: 100%;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }

    .stat-metric-card:hover {
        border-color: #93C5FD;
        transform: translateY(-2px);
        box-shadow: var(--profile-hover-shadow);
    }

    .stat-metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .stat-metric-val {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0F172A;
        line-height: 1.2;
        font-family: inherit;
    }

    .stat-metric-lbl {
        font-size: 0.8rem;
        color: #64748B;
        margin-bottom: 0.15rem;
        font-weight: 500;
    }

    /* 8. Account Info Tiles */
    .info-tile {
        background-color: #F8FAFC;
        border: 1px solid var(--profile-border);
        border-radius: 12px;
        padding: 0.9rem 1rem;
        height: 100%;
        transition: border-color 0.2s ease;
    }

    .info-tile:hover {
        border-color: #CBD5E1;
    }

    .info-tile-lbl {
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748B;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .info-tile-val {
        font-size: 0.925rem;
        font-weight: 600;
        color: #0F172A;
        line-height: 1.35;
    }

    /* 9. Address Item Cards */
    .address-card-item {
        background: #FFFFFF;
        border: 1.5px solid var(--profile-border);
        border-radius: 14px;
        padding: 1.25rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: all 0.2s ease;
    }

    .address-card-item:hover {
        border-color: #93C5FD;
        box-shadow: 0 6px 18px -4px rgba(8, 102, 255, 0.1);
    }

    .address-card-item.is-primary {
        border-color: #0866FF;
        background: linear-gradient(180deg, #F8FAFF 0%, #FFFFFF 100%);
        box-shadow: 0 4px 14px -2px rgba(8, 102, 255, 0.12);
    }

    /* 10. Form Controls & Inputs */
    .profile-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.4rem;
        display: block;
    }

    .profile-input {
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        padding: 0.65rem 0.95rem;
        font-size: 0.9rem;
        color: #0F172A;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-input:focus {
        border-color: #0866FF;
        box-shadow: 0 0 0 3px rgba(8, 102, 255, 0.15);
    }

    .password-eye-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #94A3B8;
        cursor: pointer;
        padding: 0.25rem;
        font-size: 1.05rem;
        transition: color 0.15s ease;
    }

    .password-eye-btn:hover {
        color: #0866FF;
    }

    /* Hide scrollbar helper */
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
@endpush

@section('hero')
<!-- ========================================================
     1. HERO HEADER BANNER (BLADESTORE NAVY & BLUE)
     ======================================================== -->
<div class="profile-hero-banner text-white">
    <div class="profile-hero-orb-1" aria-hidden="true"></div>
    <div class="profile-hero-orb-2" aria-hidden="true"></div>

    <div class="container position-relative z-1" style="max-width: 1220px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-2.5">
            <ol class="breadcrumb small text-white-50 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-white text-decoration-none opacity-75">Beranda</a></li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">Akun Saya</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1 text-white">Profil Pelanggan</h1>
                <p class="text-white text-opacity-75 small mb-0">Kelola informasi pribadi, buku alamat pengiriman, dan keamanan akun Anda.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm">
                    <i class="bi bi-receipt"></i>
                    <span>Pesanan Saya</span>
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-light text-primary rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm">
                    <i class="bi bi-cart3"></i>
                    <span>Mulai Belanja</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- ========================================================
     2. MAIN PROFILE DASHBOARD (SIDEBAR + MAIN CONTENT)
     ======================================================== -->
<div class="profile-main-container">
    
    <div class="profile-dashboard-layout">
        
        <!-- ====================================================
             LEFT SIDEBAR COLUMN (PROPORSIONAL 250 - 270px)
             ==================================================== -->
        <aside class="profile-sidebar-col">
            <div class="profile-sidebar-card">
                
                <!-- Avatar & Identity Summary -->
                <div class="text-center p-4 border-bottom">
                    <div class="profile-avatar-box mb-3 mx-auto">
                        @if($user->avatar ?? false)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-100 h-100 rounded-circle object-fit-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="profile-user-name" title="{{ $user->name }}">{{ $user->name }}</div>
                    <div class="profile-user-email" title="{{ $user->email }}">{{ $user->email }}</div>
                    
                    <div class="profile-verified-badge">
                        <i class="bi bi-patch-check-fill text-primary"></i>
                        <span>Pelanggan Terverifikasi</span>
                    </div>

                    @if($user->isAdmin())
                        <div class="mt-2.5">
                            <a href="{{ route('admin.dashboard') }}" class="badge bg-danger text-white text-decoration-none px-2.5 py-1 rounded-pill small">
                                <i class="bi bi-shield-lock-fill me-1"></i> Dashboard Admin
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Mobile / Tablet Navigation Pills (Visible < 992px) -->
                <div class="mobile-profile-tabs-wrapper d-lg-none hide-scrollbar">
                    <button type="button" class="mobile-tab-pill {{ $tab === 'overview' ? 'active' : '' }}" onclick="switchProfileTab('overview');">
                        <i class="bi bi-grid-1x2"></i> Ringkasan
                    </button>
                    <button type="button" class="mobile-tab-pill {{ $tab === 'personal' ? 'active' : '' }}" onclick="switchProfileTab('personal');">
                        <i class="bi bi-person"></i> Informasi Pribadi
                    </button>
                    <button type="button" class="mobile-tab-pill {{ $tab === 'addresses' ? 'active' : '' }}" onclick="switchProfileTab('addresses');">
                        <i class="bi bi-geo-alt"></i> Alamat ({{ $addresses->count() }})
                    </button>
                    <button type="button" class="mobile-tab-pill {{ $tab === 'security' ? 'active' : '' }}" onclick="switchProfileTab('security');">
                        <i class="bi bi-shield-lock"></i> Kata Sandi
                    </button>
                    <a href="{{ route('orders.index') }}" class="mobile-tab-pill">
                        <i class="bi bi-bag-check"></i> Pesanan ({{ $ordersCount }})
                    </a>
                </div>

                <!-- Desktop Sidebar Navigation List (Visible >= 992px) -->
                <nav class="profile-nav-list d-none d-lg-flex" role="tablist">
                    <!-- Tab 1: Ringkasan Akun -->
                    <button type="button" 
                            class="profile-nav-btn {{ $tab === 'overview' ? 'active' : '' }}" 
                            id="btn-tab-overview" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-overview" 
                            role="tab" 
                            aria-controls="tab-overview" 
                            aria-selected="{{ $tab === 'overview' ? 'true' : 'false' }}">
                        <i class="bi bi-grid-1x2"></i>
                        <span class="flex-grow-1">Ringkasan Akun</span>
                    </button>

                    <!-- Tab 2: Informasi Pribadi -->
                    <button type="button" 
                            class="profile-nav-btn {{ $tab === 'personal' ? 'active' : '' }}" 
                            id="btn-tab-personal" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-personal" 
                            role="tab" 
                            aria-controls="tab-personal" 
                            aria-selected="{{ $tab === 'personal' ? 'true' : 'false' }}">
                        <i class="bi bi-person"></i>
                        <span class="flex-grow-1">Informasi Pribadi</span>
                    </button>

                    <!-- Tab 3: Buku Alamat -->
                    <button type="button" 
                            class="profile-nav-btn {{ $tab === 'addresses' ? 'active' : '' }}" 
                            id="btn-tab-addresses" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-addresses" 
                            role="tab" 
                            aria-controls="tab-addresses" 
                            aria-selected="{{ $tab === 'addresses' ? 'true' : 'false' }}">
                        <i class="bi bi-geo-alt"></i>
                        <span class="flex-grow-1">Buku Alamat</span>
                        <span class="badge {{ $tab === 'addresses' ? 'bg-primary text-white' : 'bg-light text-secondary border' }} rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                            {{ $addresses->count() }}
                        </span>
                    </button>

                    <!-- Tab 4: Kata Sandi & Keamanan -->
                    <button type="button" 
                            class="profile-nav-btn {{ $tab === 'security' ? 'active' : '' }}" 
                            id="btn-tab-security" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-security" 
                            role="tab" 
                            aria-controls="tab-security" 
                            aria-selected="{{ $tab === 'security' ? 'true' : 'false' }}">
                        <i class="bi bi-shield-lock"></i>
                        <span class="flex-grow-1">Kata Sandi &amp; Keamanan</span>
                    </button>

                    <!-- Navigasi Pesanan Saya -->
                    <a href="{{ route('orders.index') }}" class="profile-nav-btn" title="Lihat Riwayat Pesanan Saya">
                        <i class="bi bi-bag-check"></i>
                        <span class="flex-grow-1">Pesanan Saya</span>
                        @if($activeOrdersCount > 0)
                            <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" title="{{ $activeOrdersCount }} pesanan aktif">
                                {{ $activeOrdersCount }}
                            </span>
                        @else
                            <i class="bi bi-chevron-right text-muted x-small"></i>
                        @endif
                    </a>
                </nav>

                <!-- Terpisah: Tombol Logout -->
                <div class="profile-logout-wrap">
                    <form action="{{ route('logout') }}" method="POST" id="sidebarLogoutForm" class="m-0">
                        @csrf
                        <button type="submit" class="profile-logout-btn" title="Keluar dari Akun Anda">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Keluar / Logout</span>
                        </button>
                    </form>
                </div>

                <!-- Footer Support Note -->
                <div class="p-3 bg-light border-top text-center x-small text-muted">
                    <div class="d-flex align-items-center justify-content-center gap-1.5 mb-0.5 text-secondary">
                        <i class="bi bi-whatsapp text-success"></i>
                        <span class="fw-semibold">Bantuan BladeStore</span>
                    </div>
                    <span>Customer service siap membantu belanja Anda.</span>
                </div>

            </div>
        </aside>

        <!-- ====================================================
             RIGHT MAIN CONTENT AREA
             ==================================================== -->
        <main class="profile-content-col">
            <div class="tab-content" id="profileTabContent">

                <!-- ----------------------------------------------------
                     TAB 1: RINGKASAN AKUN (OVERVIEW)
                     ---------------------------------------------------- -->
                <div class="tab-pane fade {{ $tab === 'overview' ? 'show active' : '' }}" id="tab-overview" role="tabpanel" aria-labelledby="btn-tab-overview">
                    
                    <!-- 1. Greeting & Join Date Header Card -->
                    <div class="profile-card mb-4">
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                            <div>
                                <h3 class="fw-bold text-dark mb-1 fs-5">Halo, {{ $user->name }}! 👋</h3>
                                <p class="text-muted small mb-0">Kelola akun, pantau transaksi belanja, dan perbarui alamat pengiriman Anda dengan mudah.</p>
                            </div>
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small flex-shrink-0">
                                <i class="bi bi-calendar3 me-1 text-primary"></i> Member sejak {{ $user->created_at?->format('d M Y') ?? 'Pengguna Setia' }}
                            </span>
                        </div>
                    </div>

                    <!-- 2. Tiga Kartu Statistik (Proporsional & Tinggi Sama) -->
                    <div class="row g-3 mb-4">
                        <!-- Stat 1: Total Pesanan -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('orders.index') }}" class="stat-metric-card">
                                <div class="stat-metric-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-bag-check-fill"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="stat-metric-lbl">Total Pesanan</div>
                                    <div class="stat-metric-val">{{ $ordersCount }}</div>
                                </div>
                            </a>
                        </div>

                        <!-- Stat 2: Pesanan Berjalan -->
                        <div class="col-12 col-sm-6 col-md-4">
                            <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="stat-metric-card">
                                <div class="stat-metric-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="stat-metric-lbl">Pesanan Berjalan</div>
                                    <div class="stat-metric-val text-warning">{{ $activeOrdersCount }}</div>
                                </div>
                            </a>
                        </div>

                        <!-- Stat 3: Alamat Tersimpan -->
                        <div class="col-12 col-sm-12 col-md-4">
                            <a href="javascript:void(0);" onclick="switchProfileTab('addresses');" class="stat-metric-card">
                                <div class="stat-metric-icon bg-success-subtle text-success">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="stat-metric-lbl">Alamat Tersimpan</div>
                                    <div class="stat-metric-val text-success">{{ $addresses->count() }}</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- 3. Informasi Akun Utama (Lega & Tertata) -->
                    <div class="profile-card mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-person-lines-fill fs-6"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0 fs-6">Informasi Akun Utama</h5>
                                    <span class="text-muted x-small">Data pribadi akun pelanggan BladeStore</span>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5" onclick="switchProfileTab('personal');">
                                <i class="bi bi-pencil-square"></i>
                                <span>Edit Data</span>
                            </button>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <div class="info-tile">
                                    <div class="info-tile-lbl">
                                        <i class="bi bi-person"></i>
                                        <span>Nama Lengkap</span>
                                    </div>
                                    <div class="info-tile-val text-truncate" title="{{ $user->name }}">{{ $user->name }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="info-tile">
                                    <div class="info-tile-lbl">
                                        <i class="bi bi-envelope"></i>
                                        <span>Alamat Email</span>
                                    </div>
                                    <div class="info-tile-val text-truncate" title="{{ $user->email }}">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="info-tile">
                                    <div class="info-tile-lbl">
                                        <i class="bi bi-telephone"></i>
                                        <span>Nomor Telepon / WA</span>
                                    </div>
                                    <div class="info-tile-val {{ $user->phone ? '' : 'text-muted fst-italic fw-normal' }}">
                                        {{ $user->phone ?: 'Belum ditambahkan' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Buku Alamat Pengiriman Utama (Terpisah & Rapi) -->
                    <div class="profile-card mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-pin-map-fill fs-6"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0 fs-6">Alamat Pengiriman Utama</h5>
                                    <span class="text-muted x-small">Tujuan default saat checkout pesanan</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 fw-semibold" onclick="switchProfileTab('addresses');">
                                    Kelola Alamat
                                </button>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                                    <i class="bi bi-plus-lg"></i>
                                    <span>Tambah</span>
                                </button>
                            </div>
                        </div>

                        @php
                            $primaryAddress = $addresses->firstWhere('is_primary', true) ?? $addresses->first();
                        @endphp

                        @if($primaryAddress)
                            <div class="p-3 bg-light rounded-3 border">
                                <div class="d-flex align-items-center gap-2 mb-1.5">
                                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-0.5 x-small fw-semibold">
                                        {{ $primaryAddress->label }}
                                    </span>
                                    <span class="fw-bold text-dark small">{{ $primaryAddress->recipient_name }}</span>
                                    <span class="text-muted x-small">&bull; {{ $primaryAddress->phone }}</span>
                                </div>
                                <p class="text-secondary small mb-1">{{ $primaryAddress->full_address }}</p>
                                @if($primaryAddress->city || $primaryAddress->postal_code)
                                    <div class="text-muted x-small">
                                        <i class="bi bi-geo-alt me-1 text-primary"></i>
                                        <span>{{ $primaryAddress->city }}{{ $primaryAddress->postal_code ? ', '.$primaryAddress->postal_code : '' }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Compact Empty State for Address -->
                            <div class="p-3 bg-light rounded-3 border d-flex flex-column flex-sm-row align-items-center justify-content-between gap-3 text-center text-sm-start">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle bg-secondary-subtle text-secondary d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                        <i class="bi bi-geo-alt fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small mb-0.5">Belum ada alamat tersimpan</div>
                                        <div class="text-muted x-small">Tambahkan alamat agar proses checkout pesanan belanja lebih cepat.</div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1.5 fw-bold text-nowrap" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Alamat
                                </button>
                            </div>
                        @endif
                    </div>

                    <!-- 5. Pesanan Terbaru Anda (Tabel Rapi & Responsif) -->
                    <div class="profile-card">
                        <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-receipt fs-6"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold text-dark mb-0 fs-6">Pesanan Terbaru Anda</h5>
                                    <span class="text-muted x-small">Transaksi belanja terkini di BladeStore</span>
                                </div>
                            </div>
                            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-link text-primary text-decoration-none fw-semibold p-0 d-inline-flex align-items-center gap-1">
                                <span>Lihat Semua</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>

                        @if($recentOrders->count() > 0)
                            <div class="table-responsive rounded-3 border">
                                <table class="table table-hover align-middle small mb-0">
                                    <thead class="table-light text-secondary">
                                        <tr>
                                            <th class="py-2.5 px-3">Kode Pesanan</th>
                                            <th class="py-2.5 px-3">Tanggal</th>
                                            <th class="py-2.5 px-3">Total Belanja</th>
                                            <th class="py-2.5 px-3">Status</th>
                                            <th class="py-2.5 px-3 text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td class="px-3">
                                                    <a href="{{ route('orders.show', $order->id) }}" class="fw-bold text-primary font-monospace text-decoration-none">
                                                        {{ $order->order_code }}
                                                    </a>
                                                </td>
                                                <td class="px-3 text-muted" style="white-space: nowrap;">
                                                    {{ $order->created_at->format('d M Y, H:i') }} WIB
                                                </td>
                                                <td class="px-3 fw-bold text-dark font-monospace" style="white-space: nowrap;">
                                                    {{ $order->formatted_total_price }}
                                                </td>
                                                <td class="px-3" style="white-space: nowrap;">
                                                    <span class="badge {{ $order->status_badge_class }} rounded-pill px-2.5 py-1 small">
                                                        {{ $order->status_label }}
                                                    </span>
                                                </td>
                                                <td class="px-3 text-end">
                                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-0.5 fw-semibold">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 bg-light rounded-3 border">
                                <div class="rounded-circle bg-white text-muted d-inline-flex align-items-center justify-content-center shadow-xs mb-2" style="width: 50px; height: 50px;">
                                    <i class="bi bi-bag-x fs-4"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1 small">Belum ada riwayat transaksi belanja</h6>
                                <p class="text-muted x-small mb-3">Mulai belanja sekarang dan temukan berbagai penawaran menarik di BladeStore.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary rounded-pill px-3.5 py-1.5 fw-bold shadow-sm">
                                    <i class="bi bi-shop me-1"></i> Mulai Belanja Sekarang
                                </a>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- ----------------------------------------------------
                     TAB 2: INFORMASI PRIBADI (EDIT PROFIL)
                     ---------------------------------------------------- -->
                <div class="tab-pane fade {{ $tab === 'personal' ? 'show active' : '' }}" id="tab-personal" role="tabpanel" aria-labelledby="btn-tab-personal">
                    <div class="profile-card">
                        
                        <div class="d-flex align-items-center gap-2.5 mb-3 pb-3 border-bottom">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-person-gear fs-6"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-0 fs-5">Informasi Pribadi</h4>
                                <p class="text-muted small mb-0">Perbarui nama, kontak email, dan nomor telepon aktif akun Anda.</p>
                            </div>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3 mb-4">
                                <!-- Nama Lengkap -->
                                <div class="col-12 col-md-6">
                                    <label for="name" class="profile-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="form-control profile-input @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $user->name) }}" 
                                               required 
                                               placeholder="Masukkan nama lengkap Anda">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Alamat Email -->
                                <div class="col-12 col-md-6">
                                    <label for="email" class="profile-label">
                                        Alamat Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" 
                                               name="email" 
                                               id="email" 
                                               class="form-control profile-input @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $user->email) }}" 
                                               required 
                                               placeholder="nama@email.com">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nomor Telepon / WA -->
                                <div class="col-12 col-md-6">
                                    <label for="phone" class="profile-label">
                                        Nomor Telepon / WhatsApp
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-telephone"></i>
                                        </span>
                                        <input type="text" 
                                               name="phone" 
                                               id="phone" 
                                               class="form-control profile-input @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $user->phone) }}" 
                                               placeholder="Contoh: 081234567890">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text small text-muted">Nomor kontak untuk konfirmasi pesanan dan pengantaran kurir.</div>
                                </div>

                                <!-- Tanggal Bergabung (Read-only) -->
                                <div class="col-12 col-md-6">
                                    <label class="profile-label">Tanggal Bergabung</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-calendar-check"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control profile-input bg-light" 
                                               value="{{ $user->created_at?->format('d F Y, H:i') ?? '-' }} WIB" 
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                                    <i class="bi bi-check2-circle"></i>
                                    <span>Simpan Perubahan</span>
                                </button>
                                <button type="reset" class="btn btn-outline-secondary px-3.5 py-2 rounded-pill fw-semibold">
                                    Batal
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- ----------------------------------------------------
                     TAB 3: MANAJEMEN BUKU ALAMAT
                     ---------------------------------------------------- -->
                <div class="tab-pane fade {{ $tab === 'addresses' ? 'show active' : '' }}" id="tab-addresses" role="tabpanel" aria-labelledby="btn-tab-addresses">
                    <div class="profile-card">
                        
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                    <i class="bi bi-geo-alt fs-6"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold text-dark mb-0 fs-5">Buku Alamat Pengiriman</h4>
                                    <p class="text-muted small mb-0">Daftar lokasi tujuan pengantaran paket belanjaan Anda.</p>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 fw-bold d-inline-flex align-items-center gap-1.5 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                                <i class="bi bi-plus-lg"></i>
                                <span>Tambah Alamat Baru</span>
                            </button>
                        </div>

                        @if($addresses->count() > 0)
                            <div class="row g-3">
                                @foreach($addresses as $address)
                                    <div class="col-12 col-md-6">
                                        <div class="address-card-item {{ $address->is_primary ? 'is-primary' : '' }}">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge {{ $address->is_primary ? 'bg-primary' : 'bg-secondary' }} text-white rounded-pill px-2.5 py-1 x-small fw-semibold">
                                                            {{ $address->label }}
                                                        </span>
                                                        @if($address->is_primary)
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 x-small fw-semibold">
                                                                <i class="bi bi-check-circle-fill me-1"></i> Utama
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <h6 class="fw-bold text-dark mb-1 small">{{ $address->recipient_name }}</h6>
                                                <p class="text-muted small mb-2 d-flex align-items-center gap-1.5">
                                                    <i class="bi bi-telephone text-primary"></i>
                                                    <span>{{ $address->phone }}</span>
                                                </p>
                                                
                                                <p class="text-secondary small mb-1.5" style="line-height: 1.45;">
                                                    {{ $address->full_address }}
                                                </p>

                                                @if($address->city || $address->postal_code)
                                                    <p class="text-muted x-small mb-3">
                                                        <i class="bi bi-geo-alt me-1 text-primary"></i>
                                                        <span>{{ $address->city }}{{ $address->postal_code ? ' - '.$address->postal_code : '' }}</span>
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="pt-3 border-top mt-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                <div>
                                                    @if(! $address->is_primary)
                                                        <form action="{{ route('addresses.set-primary', $address) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 x-small fw-semibold" title="Jadikan Alamat Utama">
                                                                Jadikan Utama
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>

                                                <div class="d-flex align-items-center gap-1.5">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-light text-primary border rounded-pill px-2.5 py-1 x-small fw-semibold" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalEditAddress-{{ $address->id }}">
                                                        <i class="bi bi-pencil me-1"></i> Edit
                                                    </button>

                                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat {{ $address->label }} ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light text-danger border rounded-pill px-2.5 py-1 x-small fw-semibold" title="Hapus Alamat">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal Edit Alamat -->
                                    <div class="modal fade" id="modalEditAddress-{{ $address->id }}" tabindex="-1" aria-labelledby="modalEditLabel-{{ $address->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="{{ route('addresses.update', $address) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    
                                                    <div class="modal-header border-bottom px-4 pt-4 pb-3">
                                                        <h5 class="modal-title fw-bold text-dark" id="modalEditLabel-{{ $address->id }}">
                                                            <i class="bi bi-pencil-square text-primary me-2"></i>Edit Alamat Pengiriman
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                    </div>

                                                    <div class="modal-body p-4">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <label class="profile-label">Label Alamat <span class="text-danger">*</span></label>
                                                                <input type="text" name="label" class="form-control profile-input" value="{{ old('label', $address->label) }}" required placeholder="Contoh: Rumah, Kantor, Kos">
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-label">Nama Penerima <span class="text-danger">*</span></label>
                                                                <input type="text" name="recipient_name" class="form-control profile-input" value="{{ old('recipient_name', $address->recipient_name) }}" required>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-label">Nomor Telepon <span class="text-danger">*</span></label>
                                                                <input type="text" name="phone" class="form-control profile-input" value="{{ old('phone', $address->phone) }}" required>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="profile-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                                                <textarea name="full_address" rows="3" class="form-control profile-input" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan...">{{ old('full_address', $address->full_address) }}</textarea>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-label">Kota / Kabupaten</label>
                                                                <input type="text" name="city" class="form-control profile-input" value="{{ old('city', $address->city) }}" placeholder="Contoh: Jakarta Selatan">
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-label">Kode Pos</label>
                                                                <input type="text" name="postal_code" class="form-control profile-input" value="{{ old('postal_code', $address->postal_code) }}" placeholder="12345">
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="edit_is_primary_{{ $address->id }}" {{ $address->is_primary ? 'checked' : '' }}>
                                                                    <label class="form-check-label small fw-semibold text-dark" for="edit_is_primary_{{ $address->id }}">
                                                                        Jadikan alamat utama untuk pengiriman belanja
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3.5" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Alamat</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 bg-light rounded-3 border">
                                <div class="stat-metric-icon bg-primary-subtle text-primary mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.6rem;">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Alamat Tersimpan</h6>
                                <p class="text-muted small mb-3 col-md-7 mx-auto">Tambahkan alamat tempat tinggal Anda agar proses checkout dan pengiriman pesanan lebih cepat dan akurat.</p>
                                <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Alamat Sekarang
                                </button>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- ----------------------------------------------------
                     TAB 4: KATA SANDI & KEAMANAN
                     ---------------------------------------------------- -->
                <div class="tab-pane fade {{ $tab === 'security' ? 'show active' : '' }}" id="tab-security" role="tabpanel" aria-labelledby="btn-tab-security">
                    <div class="profile-card">
                        
                        <div class="d-flex align-items-center gap-2.5 mb-3 pb-3 border-bottom">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="bi bi-shield-lock fs-6"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-0 fs-5">Kata Sandi &amp; Keamanan</h4>
                                <p class="text-muted small mb-0">Ubah kata sandi secara berkala untuk menjaga keamanan akun dan transaksi belanja Anda.</p>
                            </div>
                        </div>

                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3 mb-4" style="max-width: 620px;">
                                <!-- Current Password -->
                                <div class="col-12">
                                    <label for="current_password" class="profile-label">
                                        Kata Sandi Saat Ini <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="current_password" 
                                               id="current_password" 
                                               class="form-control profile-input @error('current_password') is-invalid @enderror" 
                                               required 
                                               placeholder="Masukkan kata sandi lama Anda">
                                        <button type="button" class="password-eye-btn" onclick="togglePasswordVisibility('current_password', this);" aria-label="Lihat kata sandi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="col-12">
                                    <label for="password" class="profile-label">
                                        Kata Sandi Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               class="form-control profile-input @error('password') is-invalid @enderror" 
                                               required 
                                               placeholder="Minimal 6 karakter">
                                        <button type="button" class="password-eye-btn" onclick="togglePasswordVisibility('password', this);" aria-label="Lihat kata sandi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text small text-muted">Gunakan kombinasi huruf besar, kecil, angka, dan simbol untuk proteksi optimal.</div>
                                </div>

                                <!-- Password Confirmation -->
                                <div class="col-12">
                                    <label for="password_confirmation" class="profile-label">
                                        Konfirmasi Kata Sandi Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               class="form-control profile-input" 
                                               required 
                                               placeholder="Ulangi kata sandi baru Anda">
                                        <button type="button" class="password-eye-btn" onclick="togglePasswordVisibility('password_confirmation', this);" aria-label="Lihat kata sandi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Perbarui Kata Sandi</span>
                                </button>
                                <button type="reset" class="btn btn-outline-secondary px-3.5 py-2 rounded-pill fw-semibold">
                                    Batal
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </main>

    </div>

</div>

<!-- ========================================================
     MODAL: TAMBAH ALAMAT PENGIRIMAN BARU
     ======================================================== -->
<div class="modal fade" id="modalAddAddress" tabindex="-1" aria-labelledby="modalAddAddressLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                
                <div class="modal-header border-bottom px-4 pt-4 pb-3">
                    <h5 class="modal-title fw-bold text-dark" id="modalAddAddressLabel">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>Tambah Alamat Pengiriman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="profile-label">Label Alamat <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control profile-input" value="{{ old('label', 'Rumah') }}" required placeholder="Contoh: Rumah, Kantor, Kos, Toko">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="recipient_name" class="form-control profile-input" value="{{ old('recipient_name', $user->name) }}" required placeholder="Nama lengkap penerima">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control profile-input" value="{{ old('phone', $user->phone) }}" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="profile-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="full_address" rows="3" class="form-control profile-input" required placeholder="Nama jalan, nomor rumah, RT/RW, patokan, kelurahan, kecamatan...">{{ old('full_address') }}</textarea>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-label">Kota / Kabupaten</label>
                            <input type="text" name="city" class="form-control profile-input" value="{{ old('city') }}" placeholder="Contoh: Jakarta Barat">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-label">Kode Pos</label>
                            <input type="text" name="postal_code" class="form-control profile-input" value="{{ old('postal_code') }}" placeholder="12345">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="add_is_primary" {{ $addresses->isEmpty() ? 'checked' : '' }}>
                                <label class="form-check-label small fw-semibold text-dark" for="add_is_primary">
                                    Jadikan alamat utama untuk pengiriman pesanan
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top px-4 py-3 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3.5" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchProfileTab(tabName) {
        // Desktop tab trigger
        const triggerEl = document.getElementById('btn-tab-' + tabName);
        if (triggerEl) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(triggerEl);
            tabInstance.show();
        } else {
            // Direct selector fallback
            const targetEl = document.getElementById('tab-' + tabName);
            if (targetEl) {
                document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove('show', 'active'));
                targetEl.classList.add('show', 'active');
            }
        }

        // Update mobile tabs UI
        document.querySelectorAll('.mobile-tab-pill').forEach(pill => {
            if (pill.getAttribute('onclick') && pill.getAttribute('onclick').includes(tabName)) {
                pill.classList.add('active');
            } else {
                pill.classList.remove('active');
            }
        });

        // Update URL query string without reloading
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.replaceState({}, '', url);
    }

    function togglePasswordVisibility(fieldId, btnEl) {
        const input = document.getElementById(fieldId);
        if (!input) return;
        
        const icon = btnEl.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Sync Bootstrap tab events with URL and mobile pill buttons
        const tabButtons = document.querySelectorAll('.profile-nav-btn[data-bs-toggle="tab"]');
        tabButtons.forEach(btn => {
            btn.addEventListener('shown.bs.tab', function (e) {
                const targetId = e.target.getAttribute('data-bs-target');
                if (targetId) {
                    const tabKey = targetId.replace('#tab-', '');
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabKey);
                    window.history.replaceState({}, '', url);

                    // Sync mobile pill active state
                    document.querySelectorAll('.mobile-tab-pill').forEach(pill => {
                        if (pill.getAttribute('onclick') && pill.getAttribute('onclick').includes(tabKey)) {
                            pill.classList.add('active');
                        } else {
                            pill.classList.remove('active');
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
