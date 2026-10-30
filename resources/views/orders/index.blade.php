@extends('layouts.app')

@section('title', 'Pesanan Saya')

@push('styles')
<style>
    /* 1. Header Banner Compact with Primary Blue Gradient */
    .orders-header-banner {
        background: linear-gradient(135deg, #1E40AF 0%, #2563EB 50%, #3B82F6 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 100%;
        margin: 0 !important;
        padding: 2.25rem 1rem 3.25rem !important;
    }

    .orders-banner-orb-1 {
        position: absolute;
        top: -40px;
        left: 8%;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.22) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    .orders-banner-orb-2 {
        position: absolute;
        bottom: -50px;
        right: 8%;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0) 70%);
        pointer-events: none;
    }

    /* 2. Status Navigation Tabs */
    .orders-tabs-wrapper {
        margin-top: -28px;
        position: relative;
        z-index: 10;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
    }

    .order-status-tab {
        flex: 1 1 0;
        min-width: 110px;
        text-align: center;
        padding: 0.9rem 0.5rem 0.95rem;
        color: #64748B;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        position: relative;
        transition: color 0.2s ease, background-color 0.2s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
    }

    /* Animated Underline Indicator */
    .order-status-tab::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3.5px;
        background-color: #0866FF;
        border-radius: 4px 4px 0 0;
        transform: scaleX(0);
        transform-origin: center;
        opacity: 0;
        transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease, background-color 0.2s ease;
    }

    /* Animasi garis biru saat kursor diarahkan ke pilihan lain (Hover) */
    .order-status-tab:hover {
        color: #0866FF;
        background-color: #F8FAFC;
    }

    .order-status-tab:hover::after {
        transform: scaleX(1);
        opacity: 1;
        background-color: #0866FF;
    }

    /* Tab Aktif: Garis biru penuh & permanen */
    .order-status-tab.active {
        color: #0866FF;
        font-weight: 700;
        background-color: #FFFFFF;
    }

    .order-status-tab.active::after {
        transform: scaleX(1);
        opacity: 1;
        background-color: #0866FF;
    }

    .order-status-tab .badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.45rem;
        transition: transform 0.2s ease, background-color 0.2s ease, color 0.2s ease;
    }

    .order-status-tab:hover .badge {
        transform: scale(1.08);
    }

    .order-status-tab.active .badge {
        background-color: #0866FF !important;
        color: #FFFFFF !important;
    }

    /* 3. Order Card Styling */
    .order-card-box {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #E2E8F0 !important;
    }

    .order-card-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -4px rgba(15, 23, 42, 0.08) !important;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Responsivitas Action Bar pada HP */
    @media (max-width: 575.98px) {
        .order-card-actions {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 0.5rem !important;
        }

        .order-card-actions .btn {
            width: 100% !important;
            justify-content: center !important;
        }

        .order-summary-row {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.5rem !important;
        }

        .order-summary-row .order-total-amount {
            margin-left: 0 !important;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.25rem;
            border-top: 1px dashed #E2E8F0;
        }
    }
</style>
@endpush

@section('hero')
<!-- ==========================================
     1. PRIMARY BLUE HEADER BANNER
     ========================================== -->
<div class="orders-header-banner">
    <!-- Ambient Glow Orbs -->
    <div class="orders-banner-orb-1"></div>
    <div class="orders-banner-orb-2"></div>

    <div class="position-relative z-1 px-3">
        <span class="badge bg-white fw-bold px-3 py-1.5 rounded-pill mb-2 shadow-sm text-uppercase tracking-wider x-small" style="color: #0866FF; letter-spacing: 0.05em;">
            <i class="bi bi-clock-history me-1"></i> Transaksi Pelanggan
        </span>
        <h1 class="fw-bold text-white mb-2" style="font-size: clamp(1.6rem, 2.8vw, 2.15rem); letter-spacing: -0.02em;">
            Riwayat Pesanan
        </h1>
        <p class="text-white text-opacity-90 mb-0 mx-auto" style="max-width: 540px; font-size: 0.9rem; line-height: 1.5;">
            Pantau status transaksi, proses pengemasan, dan lacak pengiriman produk belanjaan Anda secara real-time.
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="container" style="max-width: 1040px;">
    
    <!-- ==========================================
     2. TAB NAVIGASI STATUS PESANAN
     ========================================== -->
    <div class="card border-0 bg-white rounded-4 orders-tabs-wrapper mb-4">
        <div class="card-body p-0">
            <div class="d-flex align-items-center overflow-x-auto text-nowrap hide-scrollbar">
                
                <!-- 1. Semua -->
                <a href="{{ route('orders.index') }}" class="order-status-tab {{ empty($currentStatus) || $currentStatus === 'all' ? 'active' : '' }}">
                    <span>Semua</span>
                    @if(!empty($tabCounts['all']))
                        <span class="badge rounded-pill bg-light text-secondary border">{{ $tabCounts['all'] }}</span>
                    @endif
                </a>

                <!-- 2. Belum Bayar -->
                <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="order-status-tab {{ in_array($currentStatus ?? '', ['pending', 'belum_bayar']) ? 'active' : '' }}">
                    <span>Belum Bayar</span>
                    @if(!empty($tabCounts['pending']))
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle">{{ $tabCounts['pending'] }}</span>
                    @endif
                </a>

                <!-- 3. Dikemas -->
                <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="order-status-tab {{ in_array($currentStatus ?? '', ['processing', 'dikemas']) ? 'active' : '' }}">
                    <span>Dikemas</span>
                    @if(!empty($tabCounts['processing']))
                        <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle">{{ $tabCounts['processing'] }}</span>
                    @endif
                </a>

                <!-- 4. Dikirim -->
                <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="order-status-tab {{ in_array($currentStatus ?? '', ['shipped', 'dikirim']) ? 'active' : '' }}">
                    <span>Dikirim</span>
                    @if(!empty($tabCounts['shipped']))
                        <span class="badge rounded-pill bg-info-subtle text-info-emphasis border border-info-subtle">{{ $tabCounts['shipped'] }}</span>
                    @endif
                </a>

                <!-- 5. Selesai -->
                <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="order-status-tab {{ in_array($currentStatus ?? '', ['completed', 'selesai']) ? 'active' : '' }}">
                    <span>Selesai</span>
                    @if(!empty($tabCounts['completed']))
                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis border border-success-subtle">{{ $tabCounts['completed'] }}</span>
                    @endif
                </a>

                <!-- 6. Dibatalkan -->
                <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="order-status-tab {{ in_array($currentStatus ?? '', ['cancelled', 'dibatalkan']) ? 'active' : '' }}">
                    <span>Dibatalkan</span>
                    @if(!empty($tabCounts['cancelled']))
                        <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis border border-danger-subtle">{{ $tabCounts['cancelled'] }}</span>
                    @endif
                </a>

            </div>
        </div>
    </div>

    <!-- ==========================================
         3. DAFTAR KARTU PESANAN / EMPTY STATE
         ========================================== -->
    @if($orders->isEmpty())
        @php
            $statusLabelMap = [
                'pending' => 'Belum Bayar',
                'belum_bayar' => 'Belum Bayar',
                'processing' => 'Dikemas',
                'dikemas' => 'Dikemas',
                'shipped' => 'Dikirim',
                'dikirim' => 'Dikirim',
                'completed' => 'Selesai',
                'selesai' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                'dibatalkan' => 'Dibatalkan',
            ];
            $friendlyStatusName = $statusLabelMap[$currentStatus] ?? ucfirst($currentStatus);
        @endphp

        <!-- Empty State Rapi & Bersahabat -->
        <div class="card border-0 p-5 text-center bg-white rounded-4 shadow-sm my-4">
            <div class="mb-3">
                <span class="p-3.5 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 76px; height: 76px; background-color: #EFF6FF; color: #0866FF;">
                    <i class="bi bi-receipt-cutoff fs-1"></i>
                </span>
            </div>
            <h4 class="fw-bold text-dark mb-1">
                @if(!empty($currentStatus) && $currentStatus !== 'all')
                    Tidak Ada Pesanan "{{ $friendlyStatusName }}"
                @else
                    Belum Ada Riwayat Pesanan
                @endif
            </h4>
            <p class="text-muted small mb-4 col-md-6 mx-auto">
                @if(!empty($currentStatus) && $currentStatus !== 'all')
                    Saat ini Anda tidak memiliki transaksi belanja dengan status <strong>"{{ $friendlyStatusName }}"</strong>. Silakan periksa status pesanan lainnya atau telusuri produk di katalog.
                @else
                    Anda belum pernah membuat transaksi pembelian di BladeStore. Mulai belanja sekarang untuk menemukan berbagai produk berkualitas dengan penawaran terbaik.
                @endif
            </p>
            <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                @if(!empty($currentStatus) && $currentStatus !== 'all')
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary px-3.5 py-2 fw-semibold rounded-3">
                        <i class="bi bi-arrow-repeat me-1"></i> Lihat Semua Pesanan
                    </a>
                @endif
                <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-primary px-4 py-2 text-white fw-bold rounded-3 shadow-sm" style="background: #0866FF; border-color: #0866FF;">
                    <i class="bi bi-shop me-1"></i> Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-3 mb-4">
            @foreach($orders as $order)
                <div class="card border-0 bg-white rounded-3 shadow-sm p-3.5 p-md-4 order-card-box">
                    
                    <!-- 1. Header Bar Kartu Pesanan -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 pb-3 mb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Nama Toko -->
                            <span class="d-inline-flex align-items-center gap-1.5 fw-bold text-dark small">
                                <i class="bi bi-shop text-primary" style="color: #0866FF !important;"></i>
                                <span>BladeStore Official Store</span>
                            </span>
                            <span class="text-muted opacity-50">&bull;</span>
                            
                            <!-- Nomor Pesanan & Copy Button -->
                            <div class="d-inline-flex align-items-center gap-1 bg-light px-2 py-0.5 rounded border">
                                <span class="font-monospace text-dark x-small fw-semibold" title="Nomor Pesanan">
                                    {{ $order->order_code }}
                                </span>
                                <button type="button" class="btn btn-sm btn-link p-0 text-muted" onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Nomor pesanan {{ $order->order_code }} disalin!');" title="Salin Nomor Pesanan">
                                    <i class="bi bi-clipboard x-small"></i>
                                </button>
                            </div>

                            @if($order->tracking_number)
                                <span class="text-muted opacity-50">&bull;</span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 x-small fw-semibold" title="Nomor Resi Pengiriman">
                                    <i class="bi bi-truck me-1"></i> Resi: <strong class="font-monospace">{{ $order->tracking_number }}</strong>
                                </span>
                            @endif

                            <span class="text-muted opacity-50">&bull;</span>
                            <!-- Tanggal -->
                            <span class="x-small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>

                        <!-- 2. Badge Status Pesanan Konsisten -->
                        <div>
                            @if(in_array($order->status, ['pending', 'belum_bayar']))
                                @if($order->isCod())
                                    <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #E0F2FE; border: 1px solid #BAE6FD; color: #0369A1;">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu Diproses (COD)
                                    </span>
                                @elseif($order->isExpired())
                                    <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B;">
                                        <i class="bi bi-x-circle-fill me-1"></i> Dibatalkan (Kadaluarsa)
                                    </span>
                                @else
                                    <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #FEF3C7; border: 1px solid #FCD34D; color: #92400E;">
                                        <i class="bi bi-clock-history me-1"></i> Belum Bayar
                                    </span>
                                @endif
                            @elseif(in_array($order->status, ['processing', 'dikemas']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8;">
                                    <i class="bi bi-box-seam me-1"></i> Dikemas
                                </span>
                            @elseif(in_array($order->status, ['shipped', 'dikirim']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #E0E7FF; border: 1px solid #C7D2FE; color: #4338CA;">
                                    <i class="bi bi-truck me-1"></i> Dikirim
                                </span>
                            @elseif(in_array($order->status, ['completed', 'selesai']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #DCFCE7; border: 1px solid #86EFAC; color: #15803D;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                </span>
                            @elseif(in_array($order->status, ['cancelled', 'dibatalkan']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B;">
                                    <i class="bi bi-x-circle-fill me-1"></i> Dibatalkan
                                </span>
                            @else
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small bg-secondary text-white">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 3. Daftar Produk yang Dipesan -->
                    <div class="order-items-wrapper mb-3">
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light">
                                <div class="d-flex align-items-center gap-3 overflow-hidden pe-2">
                                    <div style="width: 54px; height: 54px; border-radius: 8px; overflow: hidden; background-color: #F8FAFC; border: 1px solid #E2E8F0; flex-shrink: 0; padding: 2px;" class="d-flex align-items-center justify-content-center">
                                        <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                             alt="{{ $item->product->name ?? 'Produk' }}"
                                             class="w-100 h-100 object-fit-contain"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <div class="text-truncate">
                                        <h6 class="fw-semibold text-dark mb-1 small text-truncate">
                                            {{ $item->product->name ?? 'Produk Marketplace' }}
                                        </h6>
                                        <span class="text-muted x-small">
                                            Kategori: {{ optional($item->product)->category_name ?? 'Umum' }} &bull; Kuantitas: <strong>{{ $item->quantity }}x</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="fw-semibold text-dark small">
                                        Rp {{ number_format((float)$item->price, 0, ',', '.') }}
                                    </span>
                                    <div class="x-small text-muted">
                                        Subtotal: Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- 4. Area Total Pembayaran & Metode (Dipisahkan agar tidak berdesakan dengan Tombol Aksi) -->
                    <div class="order-summary-row d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pt-2 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-light text-secondary border px-2.5 py-1.5 x-small fw-semibold">
                                <i class="bi bi-wallet2 text-primary me-1"></i> {{ $order->payment_method }}
                            </span>
                            @if($order->notes)
                                <span class="x-small text-muted text-truncate d-none d-md-inline" style="max-width: 280px;" title="{{ $order->notes }}">
                                    <i class="bi bi-chat-left-text me-1"></i> "{{ $order->notes }}"
                                </span>
                            @endif
                        </div>

                        <!-- Total Pembayaran dengan Hierarki Visual Jelas -->
                        <div class="order-total-amount d-flex align-items-baseline gap-2 ms-sm-auto">
                            <span class="small text-muted">Total Pembayaran:</span>
                            <span class="fs-5 fw-bold text-primary" style="color: #0866FF !important;">
                                {{ $order->formatted_total_price }}
                            </span>
                        </div>
                    </div>

                    {{-- 5. Bar Tombol Aksi Mandiri (Responsif: Cek Status, Pembayaran Snap, & Detail Pesanan) --}}
                    <div class="order-card-actions d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 pt-3">
                        <!-- Hint Status / Info Tambahan -->
                        <div class="d-flex align-items-center">
                            @if($order->canBePaid())
                                <span class="x-small text-warning fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-clock-history"></i>
                                    <span>Segera bayar sebelum 24 jam</span>
                                </span>
                            @elseif(in_array($order->status, ['processing', 'dikemas']))
                                <span class="x-small text-primary fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-box-seam"></i>
                                    <span>Toko sedang menyiapkan pesanan</span>
                                </span>
                            @elseif(in_array($order->status, ['shipped', 'dikirim']))
                                <span class="x-small text-info-emphasis fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-truck"></i>
                                    <span>Paket dalam perjalanan kurir</span>
                                </span>
                            @elseif(in_array($order->status, ['completed', 'selesai']))
                                <span class="x-small text-success fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-patch-check-fill"></i>
                                    <span>Pesanan telah diterima pembeli</span>
                                </span>
                            @elseif(in_array($order->status, ['cancelled', 'dibatalkan']))
                                <span class="x-small text-danger fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-x-circle-fill"></i>
                                    <span>Pesanan ini dibatalkan</span>
                                </span>
                            @endif
                        </div>

                        <!-- Kelompok Tombol Aksi -->
                        <div class="d-flex align-items-center gap-2 justify-content-sm-end flex-wrap">
                            @if($order->canBePaid())
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary px-3 py-1.5 fw-semibold rounded-2 shadow-sm d-inline-flex align-items-center gap-1.5 btn-check-status"
                                        data-order-id="{{ $order->id }}"
                                        title="Sinkronkan status jika baru saja melakukan pembayaran di simulator atau tab lain">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>Cek Status</span>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-primary px-3.5 py-1.5 fw-bold rounded-2 shadow-sm d-inline-flex align-items-center gap-1.5 btn-pay-snap"
                                        data-order-id="{{ $order->id }}"
                                        data-snap-token="{{ $order->snap_token ?? '' }}"
                                        style="background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%); border: none;">
                                    <i class="bi bi-credit-card-2-front"></i>
                                    <span>Bayar Sekarang</span>
                                </button>
                            @endif
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary px-3.5 py-1.5 fw-semibold rounded-2 shadow-sm d-inline-flex align-items-center gap-1">
                                <span>Detail Pesanan</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- 6. Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="{{ config('midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/snap.js') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        function syncAndReload(orderId, btn, snapResult) {
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengonfirmasi...';
            }

            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = '0';
            overlay.style.left = '0';
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.backgroundColor = 'rgba(15, 23, 42, 0.75)';
            overlay.style.zIndex = '99999';
            overlay.style.display = 'flex';
            overlay.style.flexDirection = 'column';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';
            overlay.style.color = '#fff';
            overlay.innerHTML = '<div class="spinner-border text-light mb-3" style="width: 3rem; height: 3rem;" role="status"></div><h5 class="fw-bold text-white mb-1">Memverifikasi Pembayaran...</h5><p class="small text-light opacity-75 mb-0">Mohon tunggu sebentar, status pesanan sedang diverifikasi dengan Midtrans.</p>';
            document.body.appendChild(overlay);

            fetch('/orders/' + orderId + '/sync-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(snapResult || {})
            })
            .then(function(res) { return res.json(); })
            .then(function() {
                setTimeout(function() {
                    window.location.reload();
                }, 500);
            })
            .catch(function() {
                window.location.reload();
            });
        }

        // Handler tombol manual 'Cek Status'
        document.querySelectorAll('.btn-check-status').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-order-id');
                syncAndReload(orderId, this, {});
            });
        });

        // Handler tombol pembayaran Snap
        document.querySelectorAll('.btn-pay-snap').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const snapToken = this.getAttribute('data-snap-token');
                const orderId = this.getAttribute('data-order-id');

                function launchSnap(token) {
                    window.snap.pay(token, {
                        onSuccess: function(result) { syncAndReload(orderId, btn, result); },
                        onPending: function(result) { syncAndReload(orderId, btn, result); },
                        onError: function() { alert('Pembayaran gagal atau dibatalkan.'); },
                        onClose: function() {
                            // Cek jika pembayaran sebenarnya sudah diselesaikan di simulator
                            syncAndReload(orderId, btn, {});
                        }
                    });
                }

                if (snapToken && snapToken.trim() !== '') {
                    launchSnap(snapToken);
                } else {
                    const originalHtml = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memuat...';

                    fetch('/orders/' + orderId + '/snap-token', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data.success && data.snap_token) {
                            btn.setAttribute('data-snap-token', data.snap_token);
                            launchSnap(data.snap_token);
                        } else {
                            alert(data.message || 'Gagal memuat popup pembayaran Midtrans.');
                        }
                    })
                    .catch(function(err) {
                        console.error('Snap token error:', err);
                        alert('Terjadi kesalahan saat memuat token pembayaran.');
                    })
                    .finally(function() {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    });
                }
            });
        });
    });
</script>
@endpush
