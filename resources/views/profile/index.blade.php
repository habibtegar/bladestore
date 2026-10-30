@extends('layouts.app')

@section('title', 'Profil Saya - BladeStore')

@push('styles')
<style>
    /* ==========================================
       1. HERO HEADER BANNER (BLADESTORE NAVY/BLUE)
       ========================================== */
    .profile-header-banner {
        background: linear-gradient(135deg, #0F172A 0%, #1E40AF 55%, #0866FF 100%);
        position: relative;
        overflow: hidden;
        width: 100%;
        margin: 0 !important;
        padding: 2.25rem 1rem 3.5rem !important;
    }

    .profile-banner-orb-1 {
        position: absolute;
        top: -40px;
        left: 5%;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.22) 0%, rgba(56, 189, 248, 0) 70%);
        pointer-events: none;
    }

    .profile-banner-orb-2 {
        position: absolute;
        bottom: -50px;
        right: 8%;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(8, 102, 255, 0.28) 0%, rgba(8, 102, 255, 0) 70%);
        pointer-events: none;
    }

    /* ==========================================
       2. DASHBOARD CARDS & SIDEBAR STYLING
       ========================================== */
    .profile-dashboard-wrapper {
        margin-top: -2.5rem;
        position: relative;
        z-index: 10;
    }

    .profile-sidebar-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.07), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
        overflow: hidden;
    }

    .profile-avatar-wrapper {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(8, 102, 255, 0.28);
        border: 3.5px solid #FFFFFF;
    }

    .profile-nav-group {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .profile-nav-item {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        color: var(--slate-700, #334155);
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
    }

    .profile-nav-item:hover {
        background-color: #F8FAFC;
        color: var(--primary, #0866FF);
        transform: translateX(3px);
    }

    .profile-nav-item.active {
        background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%);
        color: #0866FF !important;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(8, 102, 255, 0.1);
    }

    .profile-nav-item i {
        font-size: 1.15rem;
        width: 22px;
        text-align: center;
        flex-shrink: 0;
    }

    /* Content Area Cards */
    .profile-content-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 18px;
        box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.07), 0 4px 12px -2px rgba(15, 23, 42, 0.03);
        padding: 2rem;
    }

    @media (max-width: 767.98px) {
        .profile-content-card {
            padding: 1.5rem 1.25rem;
        }
    }

    /* Metric Summary Stat Card */
    .stat-metric-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.25rem 1.15rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        text-decoration: none;
    }

    .stat-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.08);
        border-color: #BFDBFE;
    }

    .stat-metric-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    /* Address Cards */
    .address-box-card {
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.25rem;
        position: relative;
        transition: all 0.2s ease;
    }

    .address-box-card:hover {
        border-color: #93C5FD;
        box-shadow: 0 8px 22px -6px rgba(8, 102, 255, 0.12);
    }

    .address-box-card.primary-address {
        border-color: #0866FF;
        background: linear-gradient(180deg, #FBFDFF 0%, #FFFFFF 100%);
        box-shadow: 0 6px 18px -4px rgba(8, 102, 255, 0.1);
    }

    /* Form Styling */
    .profile-form-label {
        font-size: 0.825rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.4rem;
    }

    .profile-form-control {
        border: 1.5px solid #CBD5E1;
        border-radius: 10px;
        padding: 0.65rem 0.95rem;
        font-size: 0.925rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .profile-form-control:focus {
        border-color: #0866FF;
        box-shadow: 0 0 0 3px rgba(8, 102, 255, 0.18);
    }

    .password-toggle-btn {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        background: transparent;
        color: #64748B;
        cursor: pointer;
        padding: 0.25rem;
    }

    .password-toggle-btn:hover {
        color: #0866FF;
    }
</style>
@endpush

@section('hero')
<!-- Header Banner Compact with Dark Navy & Blue Gradient -->
<div class="profile-header-banner text-white">
    <div class="profile-banner-orb-1" aria-hidden="true"></div>
    <div class="profile-banner-orb-2" aria-hidden="true"></div>

    <div class="container position-relative z-1" style="max-width: 1240px;">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small text-white-50 mb-0">
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-white text-decoration-none opacity-75">Beranda</a></li>
                <li class="breadcrumb-item active text-white fw-semibold" aria-current="page">Akun Saya</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1 text-white">Profil Pelanggan</h1>
                <p class="text-white-50 small mb-0">Kelola informasi pribadi, buku alamat pengiriman, dan keamanan akun Anda.</p>
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
<div class="profile-dashboard-wrapper mb-5" style="max-width: 1240px; margin-left: auto; margin-right: auto;">
    
    <div class="row g-4">
        
        <!-- ==========================================
             LEFT SIDEBAR: USER INFO & NAVIGATION TABS
             ========================================== -->
        <div class="col-12 col-lg-4 col-xl-3">
            <div class="profile-sidebar-card p-4">
                
                <!-- Avatar & Identity Summary -->
                <div class="text-center pb-4 border-bottom">
                    <div class="profile-avatar-wrapper mb-3">
                        @if($user->avatar ?? false)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-100 h-100 rounded-circle object-fit-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <h5 class="fw-bold text-dark mb-1 text-truncate" title="{{ $user->name }}">{{ $user->name }}</h5>
                    <p class="text-muted small mb-2 text-truncate" title="{{ $user->email }}">{{ $user->email }}</p>
                    
                    <div class="d-inline-flex align-items-center gap-1.5 px-2.5 py-1 rounded-pill bg-primary-subtle text-primary border border-primary-subtle small fw-semibold">
                        <i class="bi bi-patch-check-fill"></i>
                        <span>Pelanggan Terverifikasi</span>
                    </div>

                    @if($user->isAdmin())
                        <div class="mt-2">
                            <a href="{{ route('admin.dashboard') }}" class="badge bg-danger text-white text-decoration-none px-2 py-1">
                                <i class="bi bi-shield-lock-fill me-1"></i> Dashboard Admin
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Navigation Tabs List -->
                <div class="profile-nav-group mt-3" role="tablist">
                    <!-- Tab 1: Ringkasan Profil -->
                    <button type="button" 
                            class="profile-nav-item {{ $tab === 'overview' ? 'active' : '' }}" 
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
                            class="profile-nav-item {{ $tab === 'personal' ? 'active' : '' }}" 
                            id="btn-tab-personal" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-personal" 
                            role="tab" 
                            aria-controls="tab-personal" 
                            aria-selected="{{ $tab === 'personal' ? 'true' : 'false' }}">
                        <i class="bi bi-person-gear"></i>
                        <span class="flex-grow-1">Informasi Pribadi</span>
                    </button>

                    <!-- Tab 3: Manajemen Alamat -->
                    <button type="button" 
                            class="profile-nav-item {{ $tab === 'addresses' ? 'active' : '' }}" 
                            id="btn-tab-addresses" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-addresses" 
                            role="tab" 
                            aria-controls="tab-addresses" 
                            aria-selected="{{ $tab === 'addresses' ? 'true' : 'false' }}">
                        <i class="bi bi-geo-alt"></i>
                        <span class="flex-grow-1">Buku Alamat</span>
                        <span class="badge bg-primary rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">{{ $addresses->count() }}</span>
                    </button>

                    <!-- Tab 4: Keamanan Password -->
                    <button type="button" 
                            class="profile-nav-item {{ $tab === 'security' ? 'active' : '' }}" 
                            id="btn-tab-security" 
                            data-bs-toggle="tab" 
                            data-bs-target="#tab-security" 
                            role="tab" 
                            aria-controls="tab-security" 
                            aria-selected="{{ $tab === 'security' ? 'true' : 'false' }}">
                        <i class="bi bi-shield-lock"></i>
                        <span class="flex-grow-1">Kata Sandi & Keamanan</span>
                    </button>

                    <!-- Navigasi Pesanan Saya (Direct Link) -->
                    <a href="{{ route('orders.index') }}" class="profile-nav-item" title="Lihat Riwayat Pesanan">
                        <i class="bi bi-bag-check"></i>
                        <span class="flex-grow-1">Pesanan Saya</span>
                        @if($activeOrdersCount > 0)
                            <span class="badge bg-warning text-dark rounded-pill px-2 py-0.5" style="font-size: 0.7rem;" title="{{ $activeOrdersCount }} pesanan sedang berjalan">{{ $activeOrdersCount }}</span>
                        @else
                            <i class="bi bi-arrow-right-short text-muted fs-5"></i>
                        @endif
                    </a>

                    <!-- Divider -->
                    <hr class="my-2 text-secondary opacity-25">

                    <!-- Logout Button (Laravel Auth POST) -->
                    <form action="{{ route('logout') }}" method="POST" id="sidebarLogoutForm">
                        @csrf
                        <button type="submit" class="profile-nav-item text-danger w-100" title="Keluar dari Akun">
                            <i class="bi bi-box-arrow-right text-danger"></i>
                            <span class="flex-grow-1">Keluar / Logout</span>
                        </button>
                    </form>
                </div>

                <!-- Footer Support Note -->
                <div class="mt-4 pt-3 border-top text-center text-muted small">
                    <div class="d-flex align-items-center justify-content-center gap-1.5 mb-1 text-secondary">
                        <i class="bi bi-whatsapp text-success"></i>
                        <span class="fw-semibold">Butuh Bantuan?</span>
                    </div>
                    <span>Hubungi CS WhatsApp BladeStore siap melayani Anda 24/7.</span>
                </div>

            </div>
        </div>

        <!-- ==========================================
             RIGHT CONTENT AREA: TAB PANELS
             ========================================== -->
        <div class="col-12 col-lg-8 col-xl-9">
            <div class="tab-content" id="profileTabContent">

                <!-- ------------------------------------------
                     TAB 1: RINGKASAN AKUN (OVERVIEW)
                     ------------------------------------------ -->
                <div class="tab-pane fade {{ $tab === 'overview' ? 'show active' : '' }}" id="tab-overview" role="tabpanel" aria-labelledby="btn-tab-overview">
                    <div class="profile-content-card">
                        
                        <!-- Welcome Header -->
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                            <div>
                                <h4 class="fw-bold text-dark mb-1">Halo, {{ $user->name }}! 👋</h4>
                                <p class="text-muted small mb-0">Selamat datang di dashboard akun pribadi BladeStore Anda.</p>
                            </div>
                            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">
                                <i class="bi bi-calendar3 me-1 text-primary"></i> Bergabung: {{ $user->created_at?->format('d M Y') ?? 'Pengguna Setia' }}
                            </span>
                        </div>

                        <!-- 3 Quick Metric Cards -->
                        <div class="row g-3 mb-4">
                            <!-- Metric 1: Total Pesanan -->
                            <div class="col-sm-6 col-xl-4">
                                <a href="{{ route('orders.index') }}" class="stat-metric-card">
                                    <div class="stat-metric-icon bg-primary-subtle text-primary">
                                        <i class="bi bi-bag-check-fill"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block small mb-0.5">Total Pesanan</span>
                                        <h4 class="fw-bold text-dark mb-0">{{ $ordersCount }}</h4>
                                    </div>
                                </a>
                            </div>

                            <!-- Metric 2: Pesanan Aktif -->
                            <div class="col-sm-6 col-xl-4">
                                <a href="{{ route('orders.index') }}" class="stat-metric-card">
                                    <div class="stat-metric-icon bg-warning-subtle text-warning">
                                        <i class="bi bi-clock-history"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block small mb-0.5">Pesanan Berjalan</span>
                                        <h4 class="fw-bold text-dark mb-0">{{ $activeOrdersCount }}</h4>
                                    </div>
                                </a>
                            </div>

                            <!-- Metric 3: Buku Alamat Tersimpan -->
                            <div class="col-sm-12 col-xl-4">
                                <a href="javascript:void(0);" onclick="switchProfileTab('addresses');" class="stat-metric-card">
                                    <div class="stat-metric-icon bg-info-subtle text-info">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div>
                                        <span class="text-muted d-block small mb-0.5">Alamat Tersimpan</span>
                                        <h4 class="fw-bold text-dark mb-0">{{ $addresses->count() }}</h4>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Primary Information Snapshot Card -->
                        <div class="p-3.5 rounded-3 border bg-light mb-4" style="background-color: #F8FAFC !important;">
                            <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-lines-fill text-primary fs-5"></i>
                                    <h6 class="fw-bold text-dark mb-0">Informasi Akun Utama</h6>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-primary text-decoration-none fw-semibold p-0" onclick="switchProfileTab('personal');">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Data
                                </button>
                            </div>

                            <div class="row g-3 small">
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block mb-1">Nama Lengkap</span>
                                    <span class="fw-bold text-dark d-block text-truncate">{{ $user->name }}</span>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block mb-1">Alamat Email</span>
                                    <span class="fw-semibold text-dark d-block text-truncate">{{ $user->email }}</span>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <span class="text-muted d-block mb-1">Nomor Telepon / WA</span>
                                    <span class="fw-semibold {{ $user->phone ? 'text-dark' : 'text-muted fst-italic' }} d-block">
                                        {{ $user->phone ?: 'Belum ditambahkan' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat Utama Snapshot Card -->
                        <div class="p-3.5 rounded-3 border mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-pin-map-fill text-danger fs-5"></i>
                                    <h6 class="fw-bold text-dark mb-0">Alamat Pengiriman Utama</h6>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" onclick="switchProfileTab('addresses');">
                                    Kelola Alamat
                                </button>
                            </div>

                            @php
                                $primaryAddress = $addresses->firstWhere('is_primary', true) ?? $addresses->first();
                            @endphp

                            @if($primaryAddress)
                                <div class="pt-2">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-primary text-white rounded-pill px-2.5 py-0.5 small">{{ $primaryAddress->label }}</span>
                                        <span class="fw-bold text-dark">{{ $primaryAddress->recipient_name }}</span>
                                        <span class="text-muted small">&bull; {{ $primaryAddress->phone }}</span>
                                    </div>
                                    <p class="text-secondary small mb-0">{{ $primaryAddress->full_address }}</p>
                                    @if($primaryAddress->city || $primaryAddress->postal_code)
                                        <span class="text-muted small">{{ $primaryAddress->city }}{{ $primaryAddress->postal_code ? ', '.$primaryAddress->postal_code : '' }}</span>
                                    @endif
                                </div>
                            @else
                                <div class="text-muted small py-2 d-flex align-items-center justify-content-between">
                                    <span>Anda belum memiliki alamat pengiriman tersimpan.</span>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                                        <i class="bi bi-plus-lg me-1"></i> Tambah Alamat
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- Recent Orders Snapshot -->
                        <div class="pt-2">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-clock-history text-primary fs-5"></i>
                                    <h6 class="fw-bold text-dark mb-0">Pesanan Terbaru Anda</h6>
                                </div>
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-link text-primary text-decoration-none fw-semibold p-0">
                                    Lihat Semua Pesanan <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            @if($recentOrders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle small mb-0 border">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="py-2.5">Kode Pesanan</th>
                                                <th class="py-2.5">Tanggal</th>
                                                <th class="py-2.5">Total Belanja</th>
                                                <th class="py-2.5">Status</th>
                                                <th class="py-2.5 text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentOrders as $order)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('orders.show', $order->order_code) }}" class="fw-bold text-primary text-decoration-none">
                                                            {{ $order->order_code }}
                                                        </a>
                                                    </td>
                                                    <td class="text-muted">{{ $order->created_at->format('d M Y, H:i') }}</td>
                                                    <td class="fw-semibold text-dark">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                                    <td>
                                                        @php
                                                            $statusClasses = [
                                                                'menunggu_pembayaran' => 'bg-warning-subtle text-warning border-warning-subtle',
                                                                'diproses' => 'bg-info-subtle text-info border-info-subtle',
                                                                'dikirim' => 'bg-primary-subtle text-primary border-primary-subtle',
                                                                'selesai' => 'bg-success-subtle text-success border-success-subtle',
                                                                'dibatalkan' => 'bg-danger-subtle text-danger border-danger-subtle',
                                                            ];
                                                            $statusLabels = [
                                                                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                                                                'diproses' => 'Diproses',
                                                                'dikirim' => 'Sedang Dikirim',
                                                                'selesai' => 'Selesai',
                                                                'dibatalkan' => 'Dibatalkan',
                                                            ];
                                                        @endphp
                                                        <span class="badge border rounded-pill px-2.5 py-1 {{ $statusClasses[$order->status] ?? 'bg-secondary text-white' }}">
                                                            {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('orders.show', $order->order_code) }}" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5">
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
                                    <i class="bi bi-bag-x fs-2 text-muted mb-2 d-block"></i>
                                    <p class="text-muted small mb-2">Anda belum melakukan transaksi belanja di BladeStore.</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary rounded-pill px-3">
                                        Mulai Belanja Sekarang
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- ------------------------------------------
                     TAB 2: INFORMASI PRIBADI (EDIT PROFIL)
                     ------------------------------------------ -->
                <div class="tab-pane fade {{ $tab === 'personal' ? 'show active' : '' }}" id="tab-personal" role="tabpanel" aria-labelledby="btn-tab-personal">
                    <div class="profile-content-card">
                        
                        <div class="mb-4 pb-3 border-bottom">
                            <h4 class="fw-bold text-dark mb-1">Informasi Pribadi</h4>
                            <p class="text-muted small mb-0">Perbarui nama, kontak email, dan nomor telepon aktif Anda.</p>
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3 mb-4">
                                <!-- Nama Lengkap -->
                                <div class="col-12 col-md-6">
                                    <label for="name" class="profile-form-label">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-person"></i>
                                        </span>
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="form-control profile-form-control @error('name') is-invalid @enderror" 
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
                                    <label for="email" class="profile-form-label">
                                        Alamat Email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" 
                                               name="email" 
                                               id="email" 
                                               class="form-control profile-form-control @error('email') is-invalid @enderror" 
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
                                    <label for="phone" class="profile-form-label">
                                        Nomor Telepon / WhatsApp
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-telephone"></i>
                                        </span>
                                        <input type="text" 
                                               name="phone" 
                                               id="phone" 
                                               class="form-control profile-form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $user->phone) }}" 
                                               placeholder="Contoh: 081234567890">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text small text-muted">Nomor ini akan digunakan sebagai kontak penerima utama saat checkout.</div>
                                </div>

                                <!-- Tanggal Terdaftar (Read-only) -->
                                <div class="col-12 col-md-6">
                                    <label class="profile-form-label">Tanggal Bergabung</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0">
                                            <i class="bi bi-calendar-check"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control profile-form-control bg-light" 
                                               value="{{ $user->created_at?->format('d F Y, H:i') ?? '-' }}" 
                                               readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 pt-2 border-top">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                                    <i class="bi bi-check2-circle"></i>
                                    <span>Simpan Perubahan</span>
                                </button>
                                <button type="reset" class="btn btn-outline-secondary px-3 py-2 rounded-pill fw-semibold">
                                    Batal
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- ------------------------------------------
                     TAB 3: MANAJEMEN ALAMAT PENGIRIMAN
                     ------------------------------------------ -->
                <div class="tab-pane fade {{ $tab === 'addresses' ? 'show active' : '' }}" id="tab-addresses" role="tabpanel" aria-labelledby="btn-tab-addresses">
                    <div class="profile-content-card">
                        
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-3 border-bottom">
                            <div>
                                <h4 class="fw-bold text-dark mb-1">Buku Alamat Pengiriman</h4>
                                <p class="text-muted small mb-0">Kelola daftar alamat tujuan pengiriman pesanan belanja Anda.</p>
                            </div>
                            <button type="button" class="btn btn-primary rounded-pill px-3.5 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                                <i class="bi bi-plus-lg"></i>
                                <span>Tambah Alamat Baru</span>
                            </button>
                        </div>

                        @if($addresses->count() > 0)
                            <div class="row g-3">
                                @foreach($addresses as $address)
                                    <div class="col-12 col-md-6">
                                        <div class="address-box-card h-100 d-flex flex-column justify-content-between {{ $address->is_primary ? 'primary-address' : '' }}">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge {{ $address->is_primary ? 'bg-primary' : 'bg-secondary' }} text-white rounded-pill px-2.5 py-1 small">
                                                            {{ $address->label }}
                                                        </span>
                                                        @if($address->is_primary)
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-0.5 small fw-semibold">
                                                                <i class="bi bi-check-circle-fill me-1"></i> Utama
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <h6 class="fw-bold text-dark mb-1">{{ $address->recipient_name }}</h6>
                                                <p class="text-muted small mb-2 d-flex align-items-center gap-1.5">
                                                    <i class="bi bi-telephone text-primary"></i>
                                                    <span>{{ $address->phone }}</span>
                                                </p>
                                                
                                                <p class="text-secondary small mb-1" style="line-height: 1.45;">
                                                    {{ $address->full_address }}
                                                </p>

                                                @if($address->city || $address->postal_code)
                                                    <p class="text-muted small mb-3">
                                                        {{ $address->city }}{{ $address->postal_code ? ' - '.$address->postal_code : '' }}
                                                    </p>
                                                @endif
                                            </div>

                                            <div class="pt-3 border-top mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                <div>
                                                    @if(! $address->is_primary)
                                                        <form action="{{ route('addresses.set-primary', $address) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-1 small fw-semibold" title="Jadikan Alamat Utama">
                                                                Jadikan Utama
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>

                                                <div class="d-flex align-items-center gap-1.5">
                                                    <!-- Edit Button -->
                                                    <button type="button" 
                                                            class="btn btn-sm btn-light text-primary border rounded-pill px-2.5 py-1 small fw-semibold" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalEditAddress-{{ $address->id }}">
                                                        <i class="bi bi-pencil me-1"></i> Edit
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat {{ $address->label }} ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light text-danger border rounded-pill px-2.5 py-1 small fw-semibold" title="Hapus Alamat">
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
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body p-4">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <label class="profile-form-label">Label Alamat <span class="text-danger">*</span></label>
                                                                <input type="text" name="label" class="form-control profile-form-control" value="{{ old('label', $address->label) }}" required placeholder="Contoh: Rumah, Kantor, Kos">
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-form-label">Nama Penerima <span class="text-danger">*</span></label>
                                                                <input type="text" name="recipient_name" class="form-control profile-form-control" value="{{ old('recipient_name', $address->recipient_name) }}" required>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                                                <input type="text" name="phone" class="form-control profile-form-control" value="{{ old('phone', $address->phone) }}" required>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="profile-form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                                                                <textarea name="full_address" rows="3" class="form-control profile-form-control" required placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan...">{{ old('full_address', $address->full_address) }}</textarea>
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-form-label">Kota / Kabupaten</label>
                                                                <input type="text" name="city" class="form-control profile-form-control" value="{{ old('city', $address->city) }}" placeholder="Contoh: Jakarta Selatan">
                                                            </div>
                                                            <div class="col-12 col-sm-6">
                                                                <label class="profile-form-label">Kode Pos</label>
                                                                <input type="text" name="postal_code" class="form-control profile-form-control" value="{{ old('postal_code', $address->postal_code) }}" placeholder="12345">
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
                                                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
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
                                <div class="stat-metric-icon bg-primary-subtle text-primary mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.75rem;">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Alamat Tersimpan</h6>
                                <p class="text-muted small mb-3">Tambahkan alamat tempat tinggal Anda agar proses checkout dan pengiriman barang lebih cepat.</p>
                                <button type="button" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddAddress">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah Alamat Sekarang
                                </button>
                            </div>
                        @endif

                    </div>
                </div>

                <!-- ------------------------------------------
                     TAB 4: KATA SANDI & KEAMANAN
                     ------------------------------------------ -->
                <div class="tab-pane fade {{ $tab === 'security' ? 'show active' : '' }}" id="tab-security" role="tabpanel" aria-labelledby="btn-tab-security">
                    <div class="profile-content-card">
                        
                        <div class="mb-4 pb-3 border-bottom">
                            <h4 class="fw-bold text-dark mb-1">Kata Sandi & Keamanan</h4>
                            <p class="text-muted small mb-0">Ubah kata sandi akun Anda secara berkala demi menjaga keamanan data pribadi dan transaksi.</p>
                        </div>

                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="row g-3 mb-4" style="max-width: 650px;">
                                <!-- Current Password -->
                                <div class="col-12">
                                    <label for="current_password" class="profile-form-label">
                                        Kata Sandi Saat Ini <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="current_password" 
                                               id="current_password" 
                                               class="form-control profile-form-control @error('current_password') is-invalid @enderror" 
                                               required 
                                               placeholder="Masukkan kata sandi lama Anda">
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('current_password', this);" aria-label="Lihat kata sandi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div class="col-12">
                                    <label for="password" class="profile-form-label">
                                        Kata Sandi Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               class="form-control profile-form-control @error('password') is-invalid @enderror" 
                                               required 
                                               placeholder="Minimal 6 karakter">
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password', this);" aria-label="Lihat kata sandi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-text small text-muted">Gunakan kombinasi huruf, angka, dan simbol untuk keamanan maksimal.</div>
                                </div>

                                <!-- Password Confirmation -->
                                <div class="col-12">
                                    <label for="password_confirmation" class="profile-form-label">
                                        Konfirmasi Kata Sandi Baru <span class="text-danger">*</span>
                                    </label>
                                    <div class="position-relative">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               class="form-control profile-form-control" 
                                               required 
                                               placeholder="Ulangi kata sandi baru Anda">
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password_confirmation', this);" aria-label="Lihat kata sandi">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 pt-2 border-top">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2 shadow-sm">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Perbarui Kata Sandi</span>
                                </button>
                                <button type="reset" class="btn btn-outline-secondary px-3 py-2 rounded-pill fw-semibold">
                                    Batal
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>

</div>

<!-- ==========================================
     MODAL: TAMBAH ALAMAT PENGIRIMAN BARU
     ========================================== -->
<div class="modal fade" id="modalAddAddress" tabindex="-1" aria-labelledby="modalAddAddressLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf
                
                <div class="modal-header border-bottom px-4 pt-4 pb-3">
                    <h5 class="modal-title fw-bold text-dark" id="modalAddAddressLabel">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>Tambah Alamat Pengiriman
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="profile-form-label">Label Alamat <span class="text-danger">*</span></label>
                            <input type="text" name="label" class="form-control profile-form-control" value="{{ old('label', 'Rumah') }}" required placeholder="Contoh: Rumah, Kantor, Kos, Toko">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-form-label">Nama Penerima <span class="text-danger">*</span></label>
                            <input type="text" name="recipient_name" class="form-control profile-form-control" value="{{ old('recipient_name', $user->name) }}" required placeholder="Nama lengkap penerima">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-form-label">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control profile-form-control" value="{{ old('phone', $user->phone) }}" required placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="col-12">
                            <label class="profile-form-label">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea name="full_address" rows="3" class="form-control profile-form-control" required placeholder="Nama jalan, nomor rumah, RT/RW, patokan, kelurahan, kecamatan...">{{ old('full_address') }}</textarea>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-form-label">Kota / Kabupaten</label>
                            <input type="text" name="city" class="form-control profile-form-control" value="{{ old('city') }}" placeholder="Contoh: Jakarta Barat">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="profile-form-label">Kode Pos</label>
                            <input type="text" name="postal_code" class="form-control profile-form-control" value="{{ old('postal_code') }}" placeholder="12345">
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
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
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
        const triggerEl = document.getElementById('btn-tab-' + tabName);
        if (triggerEl) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(triggerEl);
            tabInstance.show();
            // Update URL query string without reloading
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url);
        }
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
        // Handle tab change events to keep browser URL in sync
        const tabButtons = document.querySelectorAll('.profile-nav-item[data-bs-toggle="tab"]');
        tabButtons.forEach(btn => {
            btn.addEventListener('shown.bs.tab', function (e) {
                const targetId = e.target.getAttribute('data-bs-target');
                if (targetId) {
                    const tabKey = targetId.replace('#tab-', '');
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabKey);
                    window.history.replaceState({}, '', url);
                }
            });
        });
    });
</script>
@endpush

