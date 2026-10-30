@extends('layouts.app')

@section('title', 'Penyelesaian Pesanan (Checkout)')

@push('styles')
<style>
    .payment-option-card {
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    .payment-option-card:hover {
        border-color: #93C5FD;
        background-color: #FAFAFA;
    }
    .payment-radio:checked + .payment-option-card {
        border-color: #0866FF;
        background-color: #EFF6FF;
        box-shadow: 0 4px 12px rgba(8, 102, 255, 0.1);
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
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">1</span>
                        <h5 class="fw-bold text-dark mb-0">Data Penerima & Alamat Pengiriman</h5>
                    </div>
                    @auth
                        <a href="{{ route('profile.index', ['tab' => 'addresses']) }}" target="_blank" class="small text-primary text-decoration-none fw-semibold">
                            <i class="bi bi-geo-alt me-1"></i>Buku Alamat Saya
                        </a>
                    @endauth
                </div>

                @php
                    $userSavedAddresses = auth()->user()?->addresses()->orderByDesc('is_primary')->latest()->get() ?? collect();
                    $defaultSavedAddress = $userSavedAddresses->firstWhere('is_primary', true) ?? $userSavedAddresses->first();
                @endphp

                @if($userSavedAddresses->count() > 0)
                    <div class="mb-3 p-2.5 rounded-3 border bg-light">
                        <label class="form-label small fw-semibold text-secondary mb-1.5 d-flex align-items-center gap-1.5">
                            <i class="bi bi-bookmark-check text-primary"></i>
                            <span>Pilih dari Alamat Tersimpan:</span>
                        </label>
                        <select class="form-select form-select-sm" id="selectSavedAddress" onchange="applySavedAddress(this);">
                            <option value="">-- Pilih Alamat Tersimpan --</option>
                            @foreach($userSavedAddresses as $addr)
                                <option value="{{ $addr->id }}" 
                                        data-phone="{{ $addr->phone }}" 
                                        data-address="{{ $addr->full_address }}{{ $addr->city ? ', '.$addr->city : '' }}{{ $addr->postal_code ? ' '.$addr->postal_code : '' }}"
                                        {{ $addr->is_primary ? 'selected' : '' }}>
                                    {{ $addr->label }} - {{ $addr->recipient_name }} ({{ $addr->phone }}) {{ $addr->is_primary ? '[Alamat Utama]' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

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
                               value="{{ old('customer_phone', auth()->user()?->phone ?? $defaultSavedAddress?->phone) }}"
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
                                  required>{{ old('shipping_address', $defaultSavedAddress ? ($defaultSavedAddress->full_address . ($defaultSavedAddress->city ? ', ' . $defaultSavedAddress->city : '') . ($defaultSavedAddress->postal_code ? ' ' . $defaultSavedAddress->postal_code : '')) : '') }}</textarea>
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

            <!-- 2. Pilihan Metode Pembayaran (Hanya 2 Pilihan: Midtrans & COD) -->
            <div class="card border-0 bg-white p-4 mb-4 rounded-3 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-circle" style="width: 26px; height: 26px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.85rem;">2</span>
                        <h5 class="fw-bold text-dark mb-0">Metode Pembayaran</h5>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill x-small fw-bold">
                        2 Pilihan Tersedia
                    </span>
                </div>

                <div class="row g-3">
                    <!-- Pilihan 1: Midtrans Snap -->
                    <div class="col-12 col-md-6">
                        <input type="radio" class="btn-check payment-radio" name="payment_method" id="pay_method_midtrans" value="Midtrans Snap" checked>
                        <label class="payment-option-card p-3 d-flex flex-column h-100" for="pay_method_midtrans">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                    <i class="bi bi-shield-lock-fill text-primary"></i>
                                    Pembayaran Online
                                </span>
                                <span class="badge bg-primary text-white x-small rounded-pill">Midtrans</span>
                            </div>
                            <p class="text-muted x-small mb-2 flex-grow-1 leading-relaxed">
                                Bayar instan via <strong>QRIS, GoPay, ShopeePay, Virtual Account BCA / Mandiri / BRI / BNI</strong>, dan Kartu Kredit.
                            </p>
                            <div class="d-flex align-items-center gap-1 text-primary x-small fw-semibold mt-auto">
                                <i class="bi bi-check-circle-fill"></i>
                                <span>Verifikasi Otomatis</span>
                            </div>
                        </label>
                    </div>

                    <!-- Pilihan 2: COD -->
                    <div class="col-12 col-md-6">
                        <input type="radio" class="btn-check payment-radio" name="payment_method" id="pay_method_cod" value="Cash on Delivery (COD)">
                        <label class="payment-option-card p-3 d-flex flex-column h-100" for="pay_method_cod">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark small d-flex align-items-center gap-1.5">
                                    <i class="bi bi-cash-coin text-success"></i>
                                    COD (Bayar di Tempat)
                                </span>
                                <span class="badge bg-success text-white x-small rounded-pill">Tunai</span>
                            </div>
                            <p class="text-muted x-small mb-2 flex-grow-1 leading-relaxed">
                                Bayar tunai dengan uang pas langsung kepada kurir saat pesanan tiba di alamat pengiriman Anda.
                            </p>
                            <div class="d-flex align-items-center gap-1 text-success x-small fw-semibold mt-auto">
                                <i class="bi bi-truck"></i>
                                <span>Bayar Saat Barang Diterima</span>
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

@push('scripts')
<script>
    function applySavedAddress(selectEl) {
        const selectedOpt = selectEl.options[selectEl.selectedIndex];
        if (!selectedOpt || !selectedOpt.value) return;

        const phone = selectedOpt.getAttribute('data-phone');
        const address = selectedOpt.getAttribute('data-address');

        if (phone) {
            const phoneInput = document.getElementById('customer_phone');
            if (phoneInput) phoneInput.value = phone;
        }

        if (address) {
            const addressInput = document.getElementById('shipping_address');
            if (addressInput) addressInput.value = address;
        }
    }
</script>
@endpush
