@extends('layouts.app')

@section('title', 'Pesanan #' . $order->order_code)

@push('styles')
<style>
    /* Dynamic Order Timeline Stepper (BladeStore Primary Blue Design System) */
    .order-stepper {
        display: flex;
        position: relative;
        justify-content: space-between;
        margin: 1.25rem 0;
        padding: 0;
        list-style: none;
    }

    .order-step {
        flex: 1;
        position: relative;
        text-align: center;
        padding: 0 0.5rem;
    }

    .order-step-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: #F8FAFC;
        color: #94A3B8;
        border: 2px solid #E2E8F0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
        transition: all 0.25s ease;
    }

    /* Completed Step */
    .order-step.completed .order-step-icon-wrap {
        background-color: #DCFCE7;
        color: #15803D;
        border-color: #86EFAC;
    }

    /* Active Step (In Progress - Primary Blue) */
    .order-step.active .order-step-icon-wrap {
        background-color: #EFF6FF;
        color: #0866FF;
        border-color: #0866FF;
        box-shadow: 0 0 0 4px rgba(8, 102, 255, 0.15);
    }

    /* Active Step (Waiting Payment - Amber) */
    .order-step.active-warning .order-step-icon-wrap {
        background-color: #FEF3C7;
        color: #D97706;
        border-color: #F59E0B;
        box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15);
    }

    .order-step-title {
        font-size: 0.8125rem;
        font-weight: 700;
        color: #1E293B;
        line-height: 1.25;
        margin-bottom: 0.2rem;
    }

    .order-step-desc {
        font-size: 0.725rem;
        color: #64748B;
        line-height: 1.2;
    }

    /* Connecting Line between Steps */
    .order-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 22px;
        left: 50%;
        width: 100%;
        height: 3px;
        background-color: #E2E8F0;
        z-index: 1;
    }

    .order-step.completed:not(:last-child)::after {
        background-color: #86EFAC;
    }

    @media (max-width: 767.98px) {
        .order-stepper {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.25rem;
            margin: 1rem 0;
        }

        .order-step {
            display: flex;
            align-items: center;
            text-align: left;
            gap: 1rem;
            padding: 0;
            width: 100%;
        }

        .order-step-icon-wrap {
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .order-step:not(:last-child)::after {
            top: 44px;
            left: 21px;
            width: 2px;
            height: calc(100% + 0.25rem);
        }
    }
</style>
@endpush

@section('content')
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small mb-1">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-muted">Pesanan Saya</a></li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $order->order_code }}</li>
    </ol>
</nav>

<div class="row justify-content-center mb-5">
    <div class="col-lg-9">
        
        <!-- ==========================================
             1. SUCCESS / STATUS HERO BANNER
             ========================================== -->
        <div class="card border-0 bg-white p-4 p-md-5 text-center mb-4 rounded-4 shadow-sm">
            @if($order->isCod())
                @if(in_array($order->status, ['completed', 'selesai']))
                    <div class="mb-3">
                        <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #DCFCE7; color: #15803D;">
                            <i class="bi bi-check-circle-fill display-5 text-success"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Pesanan Selesai — Pembayaran COD Diterima</h3>
                    <p class="text-muted small mb-3 mx-auto" style="max-width: 580px;">
                        Terima kasih! Pesanan COD Anda telah diterima dan pembayaran tunai sebesar <strong>{{ $order->formatted_total_price }}</strong> telah diselesaikan kepada kurir.
                    </p>
                @elseif(in_array($order->status, ['shipped', 'dikirim']))
                    <div class="mb-3">
                        <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #E0F2FE; color: #0284C7;">
                            <i class="bi bi-truck display-5 text-primary"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Pesanan Sedang Dikirim ke Alamat Anda</h3>
                    <p class="text-muted small mb-3 mx-auto" style="max-width: 580px;">
                        Paket Anda telah diserahkan ke kurir pengiriman. Mohon siapkan uang tunai pas sebesar <strong>{{ $order->formatted_total_price }}</strong> saat kurir mengantarkan paket.
                    </p>
                @elseif(in_array($order->status, ['processing', 'dikemas']))
                    <div class="mb-3">
                        <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #EFF6FF; color: #0866FF;">
                            <i class="bi bi-box-seam display-5 text-primary"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Pesanan Sedang Dikemas Toko</h3>
                    <p class="text-muted small mb-3 mx-auto" style="max-width: 580px;">
                        Toko sedang menyiapkan barang Anda. Pembayaran tunai sebesar <strong>{{ $order->formatted_total_price }}</strong> dilakukan saat kurir mengantarkan paket ke rumah Anda.
                    </p>
                @elseif(in_array($order->status, ['cancelled', 'dibatalkan']))
                    <div class="mb-3">
                        <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #FEE2E2; color: #DC2626;">
                            <i class="bi bi-x-circle-fill display-5 text-danger"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Pesanan COD Dibatalkan</h3>
                    <p class="text-muted small mb-3">Pesanan COD ini telah dibatalkan.</p>
                @else
                    <div class="mb-3">
                        <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #E0F2FE; color: #0284C7;">
                            <i class="bi bi-hourglass-split display-5 text-primary"></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">Pesanan Dibuat — Menunggu Diproses (COD)</h3>
                    <p class="text-muted small mb-3 mx-auto" style="max-width: 580px;">
                        Pesanan Anda berhasil dibuat dengan metode <strong class="text-primary">Cash on Delivery (Bayar di Tempat)</strong>. Toko kami sedang memeriksa pesanan Anda. Siapkan uang tunai pas sebesar <strong>{{ $order->formatted_total_price }}</strong> saat barang tiba.
                    </p>
                @endif
            @elseif(in_array($order->status, ['processing', 'dikemas', 'shipped', 'dikirim', 'completed', 'selesai']))
                <div class="mb-3">
                    <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #DCFCE7; color: #15803D;">
                        <i class="bi bi-check-circle-fill display-5 text-success"></i>
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-1">Pembayaran Berhasil Diverifikasi</h3>
                <p class="text-muted small mb-3 mx-auto" style="max-width: 580px;">
                    Terima kasih! Pembayaran Anda sebesar <strong>{{ $order->formatted_total_price }}</strong> telah diverifikasi secara resmi. Pesanan Anda saat ini sedang dipersiapkan oleh toko.
                </p>
            @elseif(in_array($order->status, ['pending', 'belum_bayar']))
                <div class="mb-3">
                    <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #FEF3C7; color: #D97706;">
                        <i class="bi bi-hourglass-split display-5"></i>
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-1">Pesanan Dibuat — Menunggu Pembayaran</h3>
                <p class="text-muted small mb-3 mx-auto" style="max-width: 580px;">
                    Pesanan Anda telah dicatat oleh sistem dengan status <strong class="text-warning">Belum Bayar</strong>. Segera selesaikan pembayaran agar pesanan dapat segera diproses dan dikirimkan.
                </p>
            @else
                <div class="mb-3">
                    <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #FEE2E2; color: #DC2626;">
                        <i class="bi bi-x-circle-fill display-5 text-danger"></i>
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-1">Status Pesanan: {{ $order->status_label }}</h3>
                <p class="text-muted small mb-3">Pesanan ini telah dibatalkan atau melewati batas waktu pembayaran.</p>
            @endif

            <!-- Order Code Pill -->
            <div class="d-inline-flex align-items-center justify-content-center gap-2 p-2 px-3 rounded-pill bg-light border mx-auto mb-3">
                <span class="text-muted x-small">Kode Pesanan:</span>
                <span class="fw-bold text-dark font-monospace fs-6">{{ $order->order_code }}</span>
                <button class="btn btn-sm btn-link text-decoration-none p-0 text-primary" onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode pesanan {{ $order->order_code }} berhasil disalin!');" title="Salin Kode Pesanan">
                    <i class="bi bi-clipboard"></i>
                </button>
            </div>

            <!-- Status Badges -->
            <div class="d-flex justify-content-center flex-wrap gap-2">
                <span class="badge {{ $order->status_badge_class }} px-3.5 py-2 fs-6 rounded-pill">
                    Status: {{ $order->status_label }}
                </span>
                <span class="badge {{ $order->payment_status_badge_class }} px-3.5 py-2 fs-6 rounded-pill">
                    Pembayaran: {{ $order->payment_status_label }}
                </span>
            </div>
        </div>

        <!-- ==========================================
             2. TIMELINE STATUS PESANAN AKTUAL SISTEM
             ========================================== -->
        <div class="card border-0 bg-white p-4 mb-4 rounded-4 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <h5 class="fw-bold text-dark mb-0 fs-6 d-flex align-items-center gap-2">
                    <i class="bi bi-diagram-3 text-primary"></i>
                    <span>Pelacakan Alur Pesanan</span>
                </h5>
                <span class="x-small text-muted">Update Real-Time</span>
            </div>

            @php
                $isPaidOrBeyond = in_array($order->status, ['processing', 'dikemas', 'shipped', 'dikirim', 'completed', 'selesai']) && ! $order->isCod();
                $isCodPaid = $order->isCod() && in_array($order->status, ['completed', 'selesai']);
                $isShippedOrBeyond = in_array($order->status, ['shipped', 'dikirim', 'completed', 'selesai']);
                $isCompleted = in_array($order->status, ['completed', 'selesai']);
                $isCancelled = in_array($order->status, ['cancelled', 'dibatalkan']);
                $isUnpaid = in_array($order->status, ['pending', 'belum_bayar']) && ! $order->isCod() && ! $isCancelled;
            @endphp

            @if($isCancelled)
                <div class="alert alert-danger border-0 rounded-3 my-3 d-flex align-items-center gap-2.5">
                    <i class="bi bi-x-octagon-fill fs-5"></i>
                    <div>
                        <strong>Pesanan Dibatalkan</strong>
                        <div class="x-small mt-0.5">Pesanan ini telah dibatalkan karena melewati batas waktu pembayaran 24 jam atau dibatalkan oleh pengguna/admin.</div>
                    </div>
                </div>
            @elseif($order->isCod())
                {{-- Stepper Khusus COD --}}
                <ul class="order-stepper">
                    <!-- Step 1: Pesanan Dibuat -->
                    <li class="order-step completed">
                        <div class="order-step-icon-wrap">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <div class="order-step-title">Pesanan Dibuat</div>
                            <div class="order-step-desc">{{ $order->created_at->format('d M, H:i') }} WIB</div>
                        </div>
                    </li>

                    <!-- Step 2: Menunggu Diproses -->
                    @php
                        $isCodStep2Completed = in_array($order->status, ['processing', 'dikemas', 'shipped', 'dikirim', 'completed', 'selesai']);
                        $isCodStep2Active = in_array($order->status, ['pending', 'belum_bayar']);
                    @endphp
                    <li class="order-step {{ $isCodStep2Completed ? 'completed' : ($isCodStep2Active ? 'active' : '') }}">
                        <div class="order-step-icon-wrap">
                            @if($isCodStep2Completed)
                                <i class="bi bi-check-lg"></i>
                            @else
                                <i class="bi bi-hourglass-split"></i>
                            @endif
                        </div>
                        <div>
                            <div class="order-step-title">Menunggu Diproses</div>
                            <div class="order-step-desc">
                                @if($isCodStep2Completed)
                                    <span class="text-success fw-semibold">Pesanan Dikonfirmasi</span>
                                @else
                                    <span class="text-primary fw-semibold">Menunggu Konfirmasi</span>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!-- Step 3: Pengemasan Toko -->
                    @php
                        $isCodStep3Completed = in_array($order->status, ['shipped', 'dikirim', 'completed', 'selesai']);
                        $isCodStep3Active = in_array($order->status, ['processing', 'dikemas']);
                    @endphp
                    <li class="order-step {{ $isCodStep3Completed ? 'completed' : ($isCodStep3Active ? 'active' : '') }}">
                        <div class="order-step-icon-wrap">
                            @if($isCodStep3Completed)
                                <i class="bi bi-check-lg"></i>
                            @else
                                <i class="bi bi-box-seam"></i>
                            @endif
                        </div>
                        <div>
                            <div class="order-step-title">Pengemasan Toko</div>
                            <div class="order-step-desc">
                                @if($isCodStep3Completed)
                                    <span class="text-success fw-semibold">Selesai Dikemas</span>
                                @elseif($isCodStep3Active)
                                    <span class="text-primary fw-semibold">Sedang Dikemas</span>
                                @else
                                    <span>Menunggu</span>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!-- Step 4: Pengiriman Kurir -->
                    @php
                        $isCodStep4Completed = in_array($order->status, ['completed', 'selesai']);
                        $isCodStep4Active = in_array($order->status, ['shipped', 'dikirim']);
                    @endphp
                    <li class="order-step {{ $isCodStep4Completed ? 'completed' : ($isCodStep4Active ? 'active' : '') }}">
                        <div class="order-step-icon-wrap">
                            @if($isCodStep4Completed)
                                <i class="bi bi-check-lg"></i>
                            @else
                                <i class="bi bi-truck"></i>
                            @endif
                        </div>
                        <div>
                            <div class="order-step-title">Pengiriman Kurir</div>
                            <div class="order-step-desc">
                                @if($isCodStep4Completed)
                                    <span class="text-success fw-semibold">Telah Tiba</span>
                                @elseif($isCodStep4Active)
                                    <span class="text-primary fw-semibold">Dalam Perjalanan</span>
                                @else
                                    <span>Menunggu</span>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!-- Step 5: Selesai & Bayar di Tempat -->
                    <li class="order-step {{ $isCompleted ? 'completed' : '' }}">
                        <div class="order-step-icon-wrap">
                            @if($isCompleted)
                                <i class="bi bi-patch-check"></i>
                            @else
                                <i class="bi bi-cash-coin"></i>
                            @endif
                        </div>
                        <div>
                            <div class="order-step-title">Selesai & Bayar COD</div>
                            <div class="order-step-desc">
                                @if($isCompleted)
                                    <span class="text-success fw-semibold">Lunas & Diterima</span>
                                @else
                                    <span>Bayar Tunai ke Kurir</span>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            @else
                <!-- Stepper Alur Pembayaran Online / Non-COD -->
                <ul class="order-stepper">
                    <!-- Step 1: Pesanan Dibuat -->
                    <li class="order-step completed">
                        <div class="order-step-icon-wrap">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div>
                            <div class="order-step-title">Pesanan Dibuat</div>
                            <div class="order-step-desc">{{ $order->created_at->format('d M, H:i') }} WIB</div>
                        </div>
                    </li>

                    <!-- Step 2: Pembayaran -->
                    <li class="order-step {{ $isPaidOrBeyond ? 'completed' : ($isUnpaid ? 'active-warning' : '') }}">
                        <div class="order-step-icon-wrap">
                            @if($isPaidOrBeyond)
                                <i class="bi bi-check-lg"></i>
                            @elseif($isUnpaid)
                                <i class="bi bi-credit-card"></i>
                            @else
                                <i class="bi bi-circle"></i>
                            @endif
                        </div>
                        <div>
                            <div class="order-step-title">Verifikasi Pembayaran</div>
                            <div class="order-step-desc">
                                @if($isPaidOrBeyond)
                                    <span class="text-success fw-semibold">Lunas Terverifikasi</span>
                                @elseif($isUnpaid)
                                    <span class="text-warning fw-semibold">Menunggu Pembayaran</span>
                                @else
                                    <span>Menunggu</span>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!-- Step 3: Pengemasan -->
                    <li class="order-step {{ $isShippedOrBeyond ? 'completed' : (in_array($order->status, ['processing', 'dikemas']) ? 'active' : '') }}">
                        <div class="order-step-icon-wrap">
                            @if($isShippedOrBeyond)
                                <i class="bi bi-check-lg"></i>
                            @elseif(in_array($order->status, ['processing', 'dikemas']))
                                <i class="bi bi-box-seam"></i>
                            @else
                                <i class="bi bi-box-seam"></i>
                            @endif
                        </div>
                        <div>
                            <div class="order-step-title">Pengemasan Toko</div>
                            <div class="order-step-desc">
                                @if($isShippedOrBeyond)
                                    <span class="text-success fw-semibold">Selesai Dikemas</span>
                                @elseif(in_array($order->status, ['processing', 'dikemas']))
                                    <span class="text-primary fw-semibold">Sedang Dikemas</span>
                                @else
                                    <span>Menunggu</span>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!-- Step 4: Pengiriman -->
                    <li class="order-step {{ $isCompleted ? 'completed' : (in_array($order->status, ['shipped', 'dikirim']) ? 'active' : '') }}">
                        <div class="order-step-icon-wrap">
                            @if($isCompleted)
                                <i class="bi bi-check-lg"></i>
                            @elseif(in_array($order->status, ['shipped', 'dikirim']))
                                <i class="bi bi-truck"></i>
                            @else
                                <i class="bi bi-truck"></i>
                            @endif
                        </div>
                        <div>
                            <div class="order-step-title">Pengiriman Kurir</div>
                            <div class="order-step-desc">
                                @if($isCompleted)
                                    <span class="text-success fw-semibold">Telah Diterima</span>
                                @elseif(in_array($order->status, ['shipped', 'dikirim']))
                                    <span class="text-primary fw-semibold">Dalam Perjalanan</span>
                                @else
                                    <span>Menunggu</span>
                                @endif
                            </div>
                        </div>
                    </li>

                    <!-- Step 5: Selesai -->
                    <li class="order-step {{ $isCompleted ? 'completed' : '' }}">
                        <div class="order-step-icon-wrap">
                            <i class="bi bi-patch-check"></i>
                        </div>
                        <div>
                            <div class="order-step-title">Pesanan Selesai</div>
                            <div class="order-step-desc">
                                @if($isCompleted)
                                    <span class="text-success fw-semibold">Selesai</span>
                                @else
                                    <span>Tahap Akhir</span>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            @endif
        </div>

        @if($order->canBePaid())
            <!-- ==========================================
                 3. ACTION CARD: BAYAR SEKARANG (HANYA MUNCUL JIKA BELUM BAYAR)
                 ========================================== -->
            <div class="card border-0 p-4 mb-4 rounded-4 shadow-sm text-center" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border: 1px solid #FDE68A !important;">
                <div class="mb-2">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 x-small fw-bold">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> Menunggu Pembayaran
                    </span>
                </div>
                <h4 class="fw-bold text-dark mb-1">Selesaikan Pembayaran Sekarang</h4>
                <p class="text-muted small mb-3 mx-auto" style="max-width: 520px;">
                    Klik tombol di bawah ini untuk membuka popup pembayaran aman melalui Midtrans (QRIS, Virtual Account Bank, GoPay, dan E-Wallet lainnya).
                </p>
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary btn-lg px-4 px-sm-5 py-3 fw-bold rounded-3 shadow d-inline-flex align-items-center justify-content-center gap-2 btn-pay-now" style="background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%); border: none;">
                        <i class="bi bi-credit-card-2-front-fill fs-5"></i>
                        <span>Bayar Sekarang ({{ $order->formatted_total_price }})</span>
                        <i class="bi bi-arrow-right fs-5"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg px-3 py-3 fw-semibold rounded-3 d-inline-flex align-items-center gap-1.5 btn-sync-status" title="Periksa status jika baru saja membayar di simulator/tab lain">
                        <i class="bi bi-arrow-repeat fs-5"></i>
                        <span>Cek Status</span>
                    </button>
                </div>
                <div class="x-small text-muted mt-2.5">
                    <i class="bi bi-clock-history me-1"></i> Batas waktu pembayaran 24 jam. Jika sudah membayar di simulator, klik tombol "Cek Status" untuk verifikasi instan.
                </div>
            </div>
        @endif

        @if((in_array($order->status, ['processing', 'dikemas', 'shipped', 'dikirim', 'completed', 'selesai']) && ! $order->isCod()) || ($order->isCod() && in_array($order->status, ['completed', 'selesai'])))
            <!-- ==========================================
                 4. KONFIRMASI LUNAS
                 ========================================== -->
            <div class="card border-0 bg-white p-4 mb-4 rounded-4 shadow-sm">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 fs-6">
                    <i class="bi bi-shield-check text-success fs-5"></i>
                    <span>Konfirmasi Pembayaran</span>
                </h5>
                <div class="alert alert-success border-0 mb-0 rounded-3">
                    <div class="d-flex gap-2.5 align-items-start">
                        <i class="bi bi-check-circle-fill fs-5 text-success mt-0.5"></i>
                        <div>
                            <strong>Pembayaran Telah Dikonfirmasi (Lunas)</strong>
                            <p class="mb-0 small mt-1 leading-relaxed">
                                Transaksi sebesar <strong>{{ $order->formatted_total_price }}</strong> telah lunas melalui <strong>{{ $order->payment_method }}</strong>. Anda tidak perlu melakukan pembayaran lagi. Pesanan Anda saat ini sedang dikemas dan dipersiapkan untuk pengiriman ke alamat tujuan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Payment Instructions Box (HANYA TAMPIL JIKA BELUM BAYAR) -->
            <div class="card border-0 bg-white p-4 mb-4 rounded-4 shadow-sm">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2 fs-6">
                    <i class="bi bi-wallet2 text-primary fs-5"></i>
                    <span>Instruksi Pembayaran: {{ $order->payment_method }}</span>
                </h5>

                @if($order->payment_method === 'Transfer Bank Manual')
                    <div class="p-3 rounded-3 bg-light border mb-4">
                        <div class="d-flex gap-2">
                            <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
                            <div>
                                <div class="small text-muted">Silakan lakukan transfer tepat sebesar:</div>
                                <strong class="text-primary fs-4 d-block">{{ $order->formatted_total_price }}</strong>
                                <div class="x-small text-muted mt-1">Pilih salah satu nomor rekening bank resmi berikut ini:</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- BCA -->
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-white border h-100 position-relative shadow-sm">
                                <span class="badge bg-primary mb-2">BCA</span>
                                <div class="fw-bold font-monospace fs-6 text-dark">123-456-7890</div>
                                <div class="x-small text-muted mb-2">a.n. PT BladeStore Indonesia</div>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 x-small" onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening BCA berhasil disalin!');">
                                    <i class="bi bi-copy me-1"></i> Salin No. Rek
                                </button>
                            </div>
                        </div>
                        <!-- Mandiri -->
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-white border h-100 position-relative shadow-sm">
                                <span class="badge bg-primary mb-2">MANDIRI</span>
                                <div class="fw-bold font-monospace fs-6 text-dark">987-654-3210</div>
                                <div class="x-small text-muted mb-2">a.n. PT BladeStore Indonesia</div>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 x-small" onclick="navigator.clipboard.writeText('9876543210'); alert('Nomor rekening Mandiri berhasil disalin!');">
                                    <i class="bi bi-copy me-1"></i> Salin No. Rek
                                </button>
                            </div>
                        </div>
                        <!-- BRI -->
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-white border h-100 position-relative shadow-sm">
                                <span class="badge bg-primary mb-2">BRI</span>
                                <div class="fw-bold font-monospace fs-6 text-dark">0123-01-0001-501</div>
                                <div class="x-small text-muted mb-2">a.n. PT BladeStore Indonesia</div>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 x-small" onclick="navigator.clipboard.writeText('0123010001501'); alert('Nomor rekening BRI berhasil disalin!');">
                                    <i class="bi bi-copy me-1"></i> Salin No. Rek
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light border d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                        <div>
                            <div class="fw-bold text-dark small">Sudah Melakukan Transfer?</div>
                            <div class="x-small text-muted">Kirimkan foto atau screenshot bukti transfer beserta kode pesanan Anda untuk verifikasi instan.</div>
                        </div>
                        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin BladeStore, saya ingin konfirmasi pembayaran untuk pesanan ' . $order->order_code . ' sebesar ' . $order->formatted_total_price) }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="btn btn-success btn-sm d-flex align-items-center gap-2 text-nowrap px-3 py-2 fw-semibold">
                            <i class="bi bi-whatsapp"></i> Konfirmasi WhatsApp
                        </a>
                    </div>
                @elseif(str_contains(strtolower($order->payment_method), 'cod') || str_contains(strtolower($order->payment_method), 'delivery'))
                    <!-- COD Instructions -->
                    <div class="alert alert-success border-0 mb-3 rounded-3">
                        <div class="d-flex gap-2">
                            <i class="bi bi-cash-coin fs-5 mt-1"></i>
                            <div>
                                <strong>Metode Cash on Delivery (COD) Dipilih</strong>
                                <p class="mb-0 small mt-1">
                                    Anda tidak perlu membayar secara online saat ini. Silakan siapkan uang pas sebesar 
                                    <strong class="text-dark">{{ $order->formatted_total_price }}</strong> 
                                    saat kurir mengantarkan paket pesanan ke alamat Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="x-small text-muted">
                        <i class="bi bi-telephone me-1"></i> Kurir kami akan mengonfirmasi via WhatsApp atau telepon ke nomor <strong>{{ $order->customer_phone }}</strong> sebelum pengiriman dilakukan.
                    </div>
                @else
                    <!-- Online Payment Instructions (QRIS, E-Wallet, etc.) -->
                    <div class="alert alert-info border-0 mb-0 rounded-3">
                        <div class="d-flex gap-2">
                            <i class="bi bi-qr-code-scan fs-5 mt-1"></i>
                            <div>
                                <strong>Pembayaran Online Instan ({{ $order->payment_method }})</strong>
                                <p class="mb-0 small mt-1">
                                    Silakan klik tombol <strong>"Bayar Sekarang"</strong> di atas untuk memunculkan kode QRIS / instruksi pembayaran melalui popup Midtrans.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- ==========================================
             5. RINCIAN BARANG & DATA TRANSAKSI DATABASE
             ========================================== -->
        <div class="card border-0 bg-white p-4 mb-4 rounded-4 shadow-sm">
            <h5 class="fw-bold text-dark mb-3 fs-6 d-flex align-items-center gap-2">
                <i class="bi bi-receipt text-primary"></i>
                <span>Rincian Pembelian</span>
            </h5>
            
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-secondary">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 52px; height: 52px; flex-shrink: 0; background-color: #F8FAFC; border-radius: 8px; overflow: hidden; padding: 4px;" class="border d-flex align-items-center justify-content-center">
                                            <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                                 class="w-100 h-100 object-fit-contain"
                                                 alt="{{ $item->product->name ?? 'Produk' }}"
                                                 onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark small">{{ $item->product->name ?? 'Produk Marketplace' }}</h6>
                                            <span class="x-small text-muted">Kategori: {{ optional($item->product)->category_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center small">
                                    Rp {{ number_format((float)$item->price, 0, ',', '.') }}
                                </td>
                                <td class="text-center small">
                                    {{ $item->quantity }}x
                                </td>
                                <td class="text-end fw-semibold text-primary small">
                                    Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="3" class="text-end text-muted small pt-3 border-0">Subtotal Produk:</td>
                            <td class="text-end fw-semibold text-dark small pt-3 border-0">{{ $order->formatted_total_price }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted small border-0">Ongkos Kirim:</td>
                            <td class="text-end fw-semibold text-success small border-0">Gratis</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold text-dark fs-6 pt-2 border-0">Total Tagihan:</td>
                            <td class="text-end fw-bold text-primary fs-5 pt-2 border-0">{{ $order->formatted_total_price }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Shipping & Transaction Metadata from DB -->
            <div class="border-top pt-3.5 mt-3">
                <div class="row g-3 x-small">
                    <div class="col-sm-6">
                        <span class="text-muted d-block mb-0.5">Alamat Pengiriman:</span>
                        <span class="fw-semibold text-dark">{{ $order->shipping_address }}</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="text-muted d-block mb-0.5">Nomor Telepon:</span>
                        <span class="fw-semibold text-dark font-monospace">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="text-muted d-block mb-0.5">Waktu Pemesanan:</span>
                        <span class="fw-semibold text-dark">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    @if($order->tracking_number)
                        <div class="col-sm-6">
                            <span class="text-muted d-block mb-0.5">Nomor Resi:</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace fw-bold">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                    @if($order->notes)
                        <div class="col-12">
                            <span class="text-muted d-block mb-0.5">Catatan Pengiriman:</span>
                            <span class="fst-italic text-secondary">{{ $order->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ==========================================
             6. ACTION BUTTONS (BOTTOM BAR)
             ========================================== -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2 w-100 w-sm-auto">
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary fw-semibold d-inline-flex align-items-center gap-1.5 flex-grow-1 flex-sm-grow-0">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Lihat Detail Pesanan</span>
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 flex-grow-1 flex-sm-grow-0">
                    <i class="bi bi-receipt"></i>
                    <span>Pesanan Saya</span>
                </a>
            </div>
            
            <div class="d-flex align-items-center gap-2 w-100 w-sm-auto justify-content-sm-end">
                @if($order->canBePaid())
                    <button type="button" class="btn btn-primary fw-bold shadow-sm d-inline-flex align-items-center gap-1.5 btn-pay-now flex-grow-1 flex-sm-grow-0" style="background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%); border: none;">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Bayar Sekarang</span>
                    </button>
                @endif
                <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-primary px-4 fw-bold text-white d-inline-flex align-items-center gap-1.5 flex-grow-1 flex-sm-grow-0" style="background: #0866FF; border-color: #0866FF;">
                    <i class="bi bi-bag"></i>
                    <span>Lanjut Belanja</span>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
@if($order->canBePaid())
    <script src="{{ config('midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/snap.js') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const snapToken = @json($order->snap_token);
            const orderId = @json($order->id);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function syncAndReload(snapResult, btn) {
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
                overlay.innerHTML = '<div class="spinner-border text-light mb-3" style="width: 3rem; height: 3rem;" role="status"></div><h5 class="fw-bold text-white mb-1">Memverifikasi Pembayaran...</h5><p class="small text-light opacity-75 mb-0">Mohon tunggu sebentar, status pesanan sedang diperbarui.</p>';
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

            document.querySelectorAll('.btn-sync-status').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    syncAndReload({}, this);
                });
            });

            function triggerSnapPayment() {
                if (snapToken) {
                    window.snap.pay(snapToken, {
                        onSuccess: function(result) {
                            syncAndReload(result);
                        },
                        onPending: function(result) {
                            syncAndReload(result);
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal atau dibatalkan.');
                        },
                        onClose: function() {
                            syncAndReload({});
                        }
                    });
                } else {
                    // Minta token baru ke controller jika belum tersedia
                    fetch('{{ route("orders.snap-token", $order->id) }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data.success && data.snap_token) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function(result) { syncAndReload(result); },
                                onPending: function(result) { syncAndReload(result); },
                                onError: function() { alert('Pembayaran gagal.'); },
                                onClose: function() { syncAndReload({}); }
                            });
                        } else {
                            alert(data.message || 'Gagal memuat popup pembayaran Midtrans.');
                        }
                    })
                    .catch(function(err) {
                        console.error('Snap error:', err);
                        alert('Terjadi kesalahan saat memuat popup pembayaran.');
                    });
                }
            }

            document.querySelectorAll('.btn-pay-now').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    triggerSnapPayment();
                });
            });
        });
    </script>
@endif
@endpush
