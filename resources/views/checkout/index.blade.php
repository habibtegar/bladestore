@extends('layouts.app')

@section('title', 'Penyelesaian Pesanan (Checkout) — BladeStore')

@push('styles')
<style>
    .payment-option-card {
        border: 2px solid var(--border-light);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    .payment-option-card:hover {
        border-color: #9CA3AF;
        background-color: #FAFAFA;
    }
    .payment-radio:checked + .payment-option-card {
        border-color: var(--primary);
        background-color: var(--primary-subtle);
        box-shadow: 0 4px 12px rgba(8, 102, 245, 0.1);
    }
</style>
@endpush

@section('content')
<!-- Breadcrumbs Navigation -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Keranjang</a></li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Checkout</li>
    </ol>
</nav>

<div class="mb-4">
    <h3 class="fw-bold text-dark mb-1">Penyelesaian Pesanan</h3>
    <p class="text-muted small mb-0">Pastikan data pengiriman akurat dan tentukan metode pembayaran pilihan Anda.</p>
</div>

<form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
    @csrf
    <div class="row g-4">
        <!-- Left Column: Shipping & Payment Method -->
        <div class="col-lg-8">
            <!-- 1. Customer & Shipping Info -->
            <div class="card border-0 bg-white p-4 mb-4 rounded-3 shadow-sm">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <span class="badge bg-primary rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">1</span>
                    <h5 class="fw-bold text-dark mb-0">Data Penerima & Alamat Pengiriman</h5>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold text-secondary">Nama Penerima</label>
                        <input type="text" class="form-control bg-light" value="{{ auth()->user()?->name ?? 'Pelanggan' }}" readonly>
                    </div>

                    <div class="col-md-6">
                        <label for="customer_phone" class="form-label small fw-semibold text-secondary">
                            Nomor WhatsApp / Telepon <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="customer_phone"
                               id="customer_phone"
                               class="form-control @error('customer_phone') is-invalid @enderror"
                               placeholder="Contoh: 081234567890"
                               value="{{ old('customer_phone') }}"
                               required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="shipping_address" class="form-label small fw-semibold text-secondary">
                            Alamat Lengkap Pengiriman <span class="text-danger">*</span>
                        </label>
                        <textarea name="shipping_address"
                                  id="shipping_address"
                                  rows="3"
                                  class="form-control @error('shipping_address') is-invalid @enderror"
                                  placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, dan kode pos tujuan..."
                                  required>{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="notes" class="form-label small fw-semibold text-secondary">Catatan untuk Kurir (Opsional)</label>
                        <textarea name="notes"
                                  id="notes"
                                  rows="2"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Contoh: Titipkan di pos satpam jika tidak ada orang di rumah...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Midtrans Automated Payment -->
            <input type="hidden" name="payment_method" value="Midtrans Snap">

            <div class="card border-0 rounded-3 shadow-sm p-4" style="background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%); border: 1px solid #DBEAFE !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #DBEAFE; color: #2563EB;">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Sistem Pembayaran Otomatis Midtrans</h6>
                        <p class="text-muted small mb-0">
                            Pilihan metode pembayaran (QRIS, GoPay, ShopeePay, Virtual Account Bank, dan Kartu Kredit) akan muncul otomatis melalui popup Midtrans saat Anda menekan tombol di samping.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Order Items Summary & Submit Button -->
        <div class="col-lg-4">
            <div class="card border-0 bg-white p-4 sticky-top rounded-3 shadow-sm" style="top: 85px;">
                <h5 class="fw-bold text-dark mb-3">Pesanan Anda</h5>

                <!-- Items Mini List -->
                <div class="overflow-auto pe-1 mb-3" style="max-height: 260px;">
                    @foreach($cartItems as $item)
                        <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                            <div style="width: 48px; height: 62px; flex-shrink: 0; background-color: #F3F4F6; border-radius: 6px; overflow: hidden;" class="border">
                                <img src="{{ $item->product->image_url }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-100 h-100 object-fit-cover"
                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                            </div>
                            <div class="flex-grow-1 small">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 170px;">
                                    {{ $item->product->name }}
                                </div>
                                <div class="text-muted x-small">
                                    {{ $item->quantity }}x &bull; {{ $item->product->discount_percentage > 0 ? $item->product->formatted_final_price : $item->product->formatted_price }}
                                </div>
                            </div>
                            <div class="fw-bold text-dark small text-end text-nowrap">
                                {{ $item->formatted_subtotal }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Price Breakdown -->
                <div class="d-flex justify-content-between mb-2 text-muted small">
                    <span>Total Item:</span>
                    <span class="text-dark fw-semibold">{{ $cartItems->sum('quantity') }} unit</span>
                </div>
                <div class="d-flex justify-content-between mb-2 text-muted small">
                    <span>Subtotal Produk:</span>
                    <span class="text-dark fw-semibold">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-muted small">
                    <span>Ongkos Kirim:</span>
                    <span class="text-success fw-bold">GRATIS</span>
                </div>

                <hr class="my-2">

                <div class="d-flex justify-content-between align-items-baseline mb-4">
                    <span class="fw-bold text-dark">Total Pembayaran:</span>
                    <span class="h4 fw-bold text-primary mb-0">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 d-flex align-items-center justify-content-center gap-2 fw-bold">
                    <i class="bi bi-shield-check fs-5"></i> Konfirmasi & Buat Pesanan
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('cart.index') }}" class="text-muted small text-decoration-none">
                        <i class="bi bi-chevron-left me-1"></i> Kembali ke Keranjang Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
