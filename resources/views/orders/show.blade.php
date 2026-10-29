@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_code . ' — BladeStore')

@section('content')
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-muted">Pesanan Saya</a></li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $order->order_code }}</li>
    </ol>
</nav>

<div class="row justify-content-center mb-5">
    <div class="col-lg-9">
        <div class="card border-0 bg-white p-4 mb-4 rounded-3 shadow-sm">
            <!-- Header -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 pb-3 mb-4 border-bottom">
                <div>
                    <span class="x-small text-muted text-uppercase fw-semibold d-block">Nomor Transaksi</span>
                    <h3 class="fw-bold text-dark font-monospace mb-1">{{ $order->order_code }}</h3>
                    <p class="text-muted small mb-0">Dipesan pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border small">
                        Metode: {{ $order->payment_method }}
                    </span>
                    <span class="badge {{ $order->status_badge_class }} px-3 py-2 small">
                        {{ $order->status_label }}
                    </span>
                </div>
            </div>

            @if($order->tracking_number)
                <div class="alert alert-primary border-0 shadow-sm d-flex align-items-center justify-content-between p-3 rounded-3 mb-4" style="background-color: #EFF6FF; border: 1px solid #BFDBFE !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.25rem;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <span class="x-small text-muted d-block text-uppercase fw-bold">Nomor Resi Pengiriman</span>
                            <span class="fw-bold font-monospace fs-5 text-dark">{{ $order->tracking_number }}</span>
                        </div>
                    </div>
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill small">
                        <i class="bi bi-box-seam me-1"></i> Paket Sedang Dikirim
                    </span>
                </div>
            @endif

            <!-- Items Table -->
            <h5 class="fw-bold text-dark mb-3">Rincian Barang</h5>
            <div class="table-responsive mb-4">
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
                                        <div style="width: 44px; height: 56px; flex-shrink: 0; background-color: #F3F4F6; border-radius: 6px; overflow: hidden;" class="border">
                                            <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                                 class="w-100 h-100 object-fit-cover"
                                                 alt="{{ $item->product->name ?? 'Produk' }}"
                                                 onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                        </div>
                                        <div>
                                            <a href="{{ $item->product ? route('products.show', $item->product->slug) : '#' }}" class="fw-semibold text-dark text-decoration-none small">
                                                {{ $item->product->name ?? 'Produk telah dihapus' }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center text-nowrap small text-secondary">{{ $item->formatted_price }}</td>
                                <td class="text-center small">{{ $item->quantity }}x</td>
                                <td class="text-end fw-bold text-nowrap small text-primary">{{ $item->formatted_subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Subtotal Produk:</td>
                            <td class="text-end fw-semibold small">{{ $order->formatted_total_price }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Ongkos Kirim:</td>
                            <td class="text-end text-success fw-bold small">Gratis</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Total Pembayaran:</td>
                            <td class="text-end fw-bold fs-5 text-primary">{{ $order->formatted_total_price }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Shipping Information -->
            <div class="row g-3 p-3 rounded-3 bg-light border mb-3">
                <div class="col-md-5">
                    <h6 class="fw-bold text-dark small mb-1">Alamat Pengiriman:</h6>
                    <p class="text-muted small mb-0">{{ $order->shipping_address }}</p>
                </div>
                <div class="col-md-3">
                    <h6 class="fw-bold text-dark small mb-1">Nomor WhatsApp / HP:</h6>
                    <p class="text-muted small mb-0">{{ $order->customer_phone }}</p>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold text-dark small mb-1">Nomor Resi & Catatan:</h6>
                    @if($order->tracking_number)
                        <div class="small fw-semibold text-primary font-monospace mb-1">
                            <i class="bi bi-upc-scan me-1"></i> Resi: {{ $order->tracking_number }}
                        </div>
                    @endif
                    <p class="text-muted small mb-0">Catatan: {{ $order->notes ?: '-' }}</p>
                </div>
            </div>

            @if($order->payment_method === 'Transfer Bank Manual' && $order->status === 'pending')
                <div class="p-3 rounded-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2" style="background-color: var(--primary-subtle); border: 1px solid var(--primary-border);">
                    <div class="small">
                        <span class="fw-bold text-dark d-block">Konfirmasi Pembayaran Transfer</span>
                        <span class="text-muted">Sudah transfer ke rekening BCA, Mandiri, atau BRI? Kirimkan bukti bayar agar paket segera dikirim.</span>
                    </div>
                    <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin BladeStore, saya ingin konfirmasi pembayaran untuk pesanan: ' . $order->order_code . ' sebesar ' . $order->formatted_total_price) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn btn-success btn-sm d-flex align-items-center gap-1 text-nowrap px-3 py-2 fw-semibold">
                        <i class="bi bi-whatsapp"></i> Konfirmasi WhatsApp
                    </a>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Riwayat Pesanan
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-primary px-4">
                Belanja Koleksi Lain
            </a>
        </div>
    </div>
</div>
@endsection
