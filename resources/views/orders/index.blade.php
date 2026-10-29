@extends('layouts.app')

@section('title', 'Riwayat Pesanan — BladeStore')

@push('styles')
<style>
    /* 1. Header Banner Compact with Primary Blue Gradient */
    .orders-header-banner {
        min-height: auto;
        height: auto;
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
        padding: 2.25rem 1rem 3.5rem !important;
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

    /* 2. Status Navigation Tabs (Overlapping Header) */
    .orders-tabs-wrapper {
        margin-top: -30px;
        position: relative;
        z-index: 10;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
    }

    .order-status-tab {
        flex: 1 1 0;
        min-width: 100px;
        text-align: center;
        padding: 0.95rem 0.5rem;
        color: var(--slate-600);
        font-weight: 500;
        font-size: 0.925rem;
        text-decoration: none;
        position: relative;
        transition: all 0.2s ease;
        border-bottom: 3.5px solid transparent;
        white-space: nowrap;
    }

    .order-status-tab:hover {
        color: #2563EB;
        background-color: #EFF6FF;
    }

    .order-status-tab.active {
        color: #2563EB;
        font-weight: 700;
        border-bottom: 3.5px solid #2563EB !important;
        background-color: #FFFFFF;
    }

    /* 3. Order Card Styling */
    .order-card-box {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #F1F5F9 !important;
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
</style>
@endpush

@section('hero')
<!-- ==========================================
     1. PRIMARY BLUE HEADER BANNER (COMPACT)
     ========================================== -->
<div class="orders-header-banner">
    <!-- Abstract Ambient Glow Orbs -->
    <div class="orders-banner-orb-1"></div>
    <div class="orders-banner-orb-2"></div>

    <div class="position-relative z-1 px-3">
        <span class="badge bg-white text-blue-600 fw-bold px-3 py-1.5 rounded-pill mb-2 shadow-sm text-uppercase tracking-wider x-small" style="color: #2563EB; letter-spacing: 0.05em;">
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
         2. TAB NAVIGASI STATUS (MENUMPUK DENGAN MARGIN-TOP NEGATIF)
         ========================================== -->
    <div class="card border-0 bg-white rounded-3 orders-tabs-wrapper mb-4">
        <div class="card-body p-0">
            <div class="d-flex align-items-center overflow-x-auto text-nowrap hide-scrollbar">
                
                <!-- 1. Semua -->
                <a href="{{ route('orders.index') }}" class="order-status-tab {{ empty($currentStatus) || $currentStatus === 'all' ? 'active' : '' }}">
                    <span>Semua</span>
                </a>

                <!-- 2. Belum Bayar -->
                <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="order-status-tab {{ ($currentStatus ?? '') === 'pending' ? 'active' : '' }}">
                    <span>Belum Bayar</span>
                </a>

                <!-- 3. Dikemas -->
                <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="order-status-tab {{ ($currentStatus ?? '') === 'processing' ? 'active' : '' }}">
                    <span>Dikemas</span>
                </a>

                <!-- 4. Dikirim -->
                <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="order-status-tab {{ ($currentStatus ?? '') === 'shipped' ? 'active' : '' }}">
                    <span>Dikirim</span>
                </a>

                <!-- 5. Selesai -->
                <a href="{{ route('orders.index', ['status' => 'completed']) }}" class="order-status-tab {{ ($currentStatus ?? '') === 'completed' ? 'active' : '' }}">
                    <span>Selesai</span>
                </a>

            </div>
        </div>
    </div>

    <!-- ==========================================
         3. KARTU DAFTAR PESANAN
         ========================================== -->
    @if($orders->isEmpty())
        <!-- Empty State -->
        <div class="card border-0 p-5 text-center bg-white rounded-4 shadow-sm my-4">
            <div class="mb-3">
                <span class="p-3.5 rounded-circle d-inline-block bg-blue-50 text-blue-600" style="background-color: #EFF6FF; color: #2563EB;">
                    <i class="bi bi-receipt fs-1"></i>
                </span>
            </div>
            <h4 class="fw-bold text-dark mb-1">Belum Ada Pesanan</h4>
            <p class="text-muted small mb-4 col-md-6 mx-auto">
                @if(!empty($currentStatus) && $currentStatus !== 'all')
                    Tidak ada transaksi dengan status <strong>"{{ ucfirst($currentStatus) }}"</strong> saat ini.
                @else
                    Anda belum pernah membuat transaksi pembelian di BladeStore. Jelajahi berbagai kategori produk berkualitas kami sekarang.
                @endif
            </p>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-primary bg-blue-600 px-4 py-2.5 text-white fw-bold rounded-3 shadow-sm border-blue-600 hover:bg-blue-700">
                    <i class="bi bi-shop me-1"></i> Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="d-flex flex-column gap-3.5 mb-4">
            @foreach($orders as $order)
                <div class="card border-0 bg-white rounded-3 shadow-sm p-3.5 p-md-4 order-card-box">
                    
                    <!-- Order Card Top Bar: Nama Toko, Nomor Resi/Order, Tanggal, dan Badge Status -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 pb-3 mb-3 border-bottom">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Nama Toko -->
                            <span class="d-inline-flex align-items-center gap-1.5 fw-bold text-dark small">
                                <i class="bi bi-shop text-blue-600" style="color: #2563EB;"></i>
                                <span>BladeStore Official Store</span>
                            </span>
                            <span class="text-muted opacity-50">&bull;</span>
                            <!-- Nomor Pesanan -->
                            <span class="font-monospace text-muted x-small bg-light px-2 py-0.5 rounded border" title="Nomor Pesanan">
                                {{ $order->order_code }}
                            </span>
                            @if($order->tracking_number)
                                <span class="text-muted opacity-50">&bull;</span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 x-small fw-semibold" title="Nomor Resi Pengiriman">
                                    <i class="bi bi-truck me-1"></i> No. Resi: <strong class="font-monospace">{{ $order->tracking_number }}</strong>
                                </span>
                            @endif
                            <span class="text-muted opacity-50">&bull;</span>
                            <!-- Tanggal -->
                            <span class="x-small text-muted">
                                <i class="bi bi-calendar3 me-1"></i>{{ $order->created_at->format('d M Y, H:i') }} WIB
                            </span>
                        </div>

                        <!-- Badge Status Pesanan -->
                        <div>
                            @if(in_array($order->status, ['pending', 'belum_bayar']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #FEF08A; border: 1px solid #FDE047; color: #854D0E;">
                                    <i class="bi bi-clock-history me-1"></i> Belum Bayar
                                </span>
                            @elseif(in_array($order->status, ['processing', 'dikemas']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #BAE6FD; border: 1px solid #7DD3FC; color: #0369A1;">
                                    <i class="bi bi-box-seam me-1"></i> Dikemas
                                </span>
                            @elseif(in_array($order->status, ['shipped', 'dikirim']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #E0F2FE; border: 1px solid #38BDF8; color: #0284C7;">
                                    <i class="bi bi-truck me-1"></i> Dikirim
                                </span>
                            @elseif(in_array($order->status, ['completed', 'selesai']))
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small" style="background-color: #DCFCE7; border: 1px solid #86EFAC; color: #15803D;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                </span>
                            @else
                                <span class="badge px-3 py-1.5 rounded-pill fw-bold x-small bg-danger text-white">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Detail Items List -->
                    <div class="order-items-wrapper mb-3">
                        @foreach($order->items as $item)
                            <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light">
                                <div class="d-flex align-items-center gap-3 overflow-hidden pe-2">
                                    <div style="width: 54px; height: 54px; border-radius: 8px; overflow: hidden; background-color: #F8FAFC; border: 1px solid #E2E8F0; flex-shrink: 0;">
                                        <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                             alt="{{ $item->product->name ?? 'Produk' }}"
                                             class="w-100 h-100 object-fit-cover"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <div class="text-truncate">
                                        <h6 class="fw-semibold text-dark mb-1 small text-truncate">
                                            {{ $item->product->name ?? 'Produk Marketplace' }}
                                        </h6>
                                        <span class="text-muted x-small">
                                            Kategori: {{ $item->product->category ?? 'Umum' }} &bull; Kuantitas: <strong>{{ $item->quantity }}x</strong>
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

                    <!-- Order Card Bottom Bar: Metode Pembayaran (Kiri) & Total Harga di Sudut Kanan Bawah -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 pt-2">
                        <div class="small text-muted d-flex align-items-center gap-2">
                            <span class="badge bg-light text-secondary border">
                                <i class="bi bi-credit-card me-1"></i> {{ $order->payment_method }}
                            </span>
                            @if($order->notes)
                                <span class="x-small text-muted text-truncate" style="max-width: 250px;" title="{{ $order->notes }}">
                                    <i class="bi bi-chat-left-text me-1"></i> "{{ $order->notes }}"
                                </span>
                            @endif
                        </div>

                        <!-- Total Harga di Sudut Kanan Bawah -->
                        <div class="d-flex align-items-center gap-2.5 ms-auto flex-wrap justify-content-end">
                            <div class="text-end">
                                <div class="x-small text-muted">Total Pembayaran:</div>
                                <div class="fw-bold fs-5 text-blue-600" style="color: #2563EB;">
                                    {{ $order->formatted_total_price }}
                                </div>
                            </div>
                            @if($order->canBePaid())
                                <button type="button" 
                                        class="btn btn-sm btn-outline-secondary px-2.5 py-1.5 fw-semibold rounded-2 shadow-sm d-inline-flex align-items-center gap-1 btn-check-status"
                                        data-order-id="{{ $order->id }}"
                                        title="Sinkronkan status jika baru saja melakukan pembayaran di simulator">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>Cek Status</span>
                                </button>
                                <button type="button" 
                                        class="btn btn-sm btn-primary bg-blue-600 border-blue-600 px-3.5 py-1.5 fw-bold rounded-2 shadow-sm d-inline-flex align-items-center gap-1.5 btn-pay-snap"
                                        data-order-id="{{ $order->id }}"
                                        data-snap-token="{{ $order->snap_token ?? '' }}">
                                    <i class="bi bi-credit-card-2-front"></i>
                                    <span>Bayar Sekarang</span>
                                </button>
                            @endif
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm px-3.5 py-1.5 fw-semibold rounded-2 shadow-sm text-blue-600 border-blue-600 hover:bg-blue-50" style="background-color: #EFF6FF; color: #2563EB; border: 1px solid #BFDBFE;">
                                Detail Pesanan <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Pagination -->
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

        function syncAndReload(orderId, btn) {
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengonfirmasi...';
            }

            fetch('/orders/' + orderId + '/sync-status', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(function(res) { return res.json(); })
            .finally(function() {
                window.location.reload();
            });
        }

        // Handler tombol manual 'Cek Status'
        document.querySelectorAll('.btn-check-status').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const orderId = this.getAttribute('data-order-id');
                syncAndReload(orderId, this);
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
                        onSuccess: function() { syncAndReload(orderId, btn); },
                        onPending: function() { syncAndReload(orderId, btn); },
                        onError: function() { alert('Pembayaran gagal atau dibatalkan.'); },
                        onClose: function() {
                            // Cek jika pembayaran sebenarnya sudah diselesaikan di simulator
                            syncAndReload(orderId, btn);
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
