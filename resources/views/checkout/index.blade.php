@extends('layouts.app')

@section('title', 'Penyelesaian Pesanan (Checkout) — LaraShop')

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

            <!-- 2. Payment Method -->
            <div class="card border-0 bg-white p-4 rounded-3 shadow-sm">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <span class="badge bg-primary rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">2</span>
                    <h5 class="fw-bold text-dark mb-0">Metode Pembayaran Resmi</h5>
                </div>
                <p class="text-muted small mb-3">
                    Pilih salah satu metode pembayaran manual yang tersedia di bawah ini:
                </p>

                @error('payment_method')
                    <div class="alert alert-danger py-2 small rounded-3">{{ $message }}</div>
                @enderror

                <div class="row g-3">
                    <!-- Method 1: Transfer Bank Manual -->
                    <div class="col-12">
                        <input type="radio"
                               class="btn-check payment-radio"
                               name="payment_method"
                               id="method_transfer"
                               value="Transfer Bank Manual"
                               {{ old('payment_method', 'Transfer Bank Manual') === 'Transfer Bank Manual' ? 'checked' : '' }}
                               required>
                        <label class="card p-3 payment-option-card w-100 mb-0" for="method_transfer">
                            <div class="d-flex align-items-start gap-3">
                                <div class="p-2 text-primary rounded-2 mt-1" style="background-color: var(--primary-subtle);">
                                    <i class="bi bi-bank fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 text-dark">Transfer Bank Manual</h6>
                                        <span class="badge bg-primary">Verifikasi Manual Resmi</span>
                                    </div>
                                    <p class="small text-muted mb-2">
                                        Transfer langsung ke rekening kami (BCA, Mandiri, BRI, BNI). Nomor rekening resmi dan panduan konfirmasi via WhatsApp akan ditampilkan setelah pesanan dibuat.
                                    </p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-white text-dark border x-small">Bank BCA</span>
                                        <span class="badge bg-white text-dark border x-small">Bank Mandiri</span>
                                        <span class="badge bg-white text-dark border x-small">Bank BRI</span>
                                        <span class="badge bg-white text-dark border x-small">Bank BNI</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Method 2: Cash on Delivery (COD) -->
                    <div class="col-12">
                        <input type="radio"
                               class="btn-check payment-radio"
                               name="payment_method"
                               id="method_cod"
                               value="Cash on Delivery (COD)"
                               {{ old('payment_method') === 'Cash on Delivery (COD)' ? 'checked' : '' }}
                               required>
                        <label class="card p-3 payment-option-card w-100 mb-0" for="method_cod">
                            <div class="d-flex align-items-start gap-3">
                                <div class="p-2 text-success rounded-2 mt-1" style="background-color: var(--success-subtle);">
                                    <i class="bi bi-truck fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 text-dark">Cash on Delivery (COD)</h6>
                                        <span class="badge bg-success">Bayar di Tempat</span>
                                    </div>
                                    <p class="small text-muted mb-0">
                                        Bayar tunai kepada kurir saat pesanan sampai di alamat Anda. Harap siapkan uang pas sesuai total tagihan pesanan.
                                    </p>
                                </div>
                            </div>
                        </label>
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
