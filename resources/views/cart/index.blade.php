@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .payment-option-card {
        border: 2px solid #E2E8F0;
        border-radius: 12px;
        padding: 1.15rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        background-color: #FFFFFF;
        position: relative;
    }
    .payment-option-card:hover {
        border-color: #93C5FD;
        background-color: #F8FAFC;
    }
    .payment-option-card.selected {
        border-color: #0866FF;
        background-color: #EFF6FF;
        box-shadow: 0 4px 12px rgba(8, 102, 255, 0.08);
    }
    .payment-option-card .form-check-input {
        cursor: pointer;
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.15rem;
    }
    .payment-option-card .form-check-input:checked {
        background-color: #0866FF;
        border-color: #0866FF;
    }
</style>
@endpush

@section('content')
<!-- Page Header Breadcrumbs & Title -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small mb-1">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none text-muted">Beranda</a></li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Keranjang Belanja</li>
    </ol>
</nav>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4 pb-1">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">Keranjang Belanja</h2>
        <p class="text-muted small mb-0">Periksa item belanjaan Anda, sesuaikan kuantitas, dan lanjutkan ke pembayaran instan Midtrans.</p>
    </div>
    <div>
        <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-outline-secondary btn-sm px-3 py-2 d-inline-flex align-items-center gap-1.5 text-nowrap rounded-2 shadow-xs">
            <i class="bi bi-arrow-left"></i> Lanjut Belanja
        </a>
    </div>
</div>

@if($cartItems->isEmpty())
    <!-- Empty Cart State -->
    <div class="card border-0 p-5 text-center my-4 bg-white rounded-4 shadow-sm">
        <div class="mb-3">
            <span class="p-4 rounded-circle d-inline-block bg-blue-50 text-blue-600" style="background-color: #EFF6FF; color: #0866FF; width: 84px; height: 84px; display: inline-flex; align-items: center; justify-content: center;">
                <i class="bi bi-cart-x fs-1"></i>
            </span>
        </div>
        <h4 class="fw-bold text-dark mb-2">Keranjang Belanja Anda Kosong</h4>
        <p class="text-muted small mb-4 col-md-6 mx-auto leading-relaxed">
            Anda belum memilih produk untuk dimasukkan ke dalam keranjang. Jelajahi ribuan produk pilihan BladeStore sekarang dan nikmati penawaran terbaik.
        </p>
        <div>
            <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-primary bg-blue-600 border-0 px-4 py-2.5 fw-bold text-white shadow-sm rounded-3 hover:bg-blue-700" style="background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%);">
                <i class="bi bi-shop me-1.5"></i> Mulai Belanja Sekarang
            </a>
        </div>
    </div>
@else
    <form id="cart-checkout-form" action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4">
            
            <!-- Cart Items List (Left Column) -->
            <div class="col-12 col-lg-8 space-y-4">
                
                <!-- Table Card -->
                <div class="card border-0 bg-white overflow-hidden rounded-3 shadow-sm mb-4">
                    <div class="p-3.5 border-bottom d-flex justify-content-between align-items-center bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-dark small" id="cart-header-count">Daftar Produk Terpilih ({{ $cartItems->sum('quantity') }} Item)</span>
                        </div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle x-small rounded-pill">
                            <i class="bi bi-shield-check me-1"></i> Official Store
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase" style="font-size: 0.725rem; letter-spacing: 0.03em;">
                                <tr>
                                    <th scope="col" style="width: 44px;" class="ps-3 pe-0 text-center">
                                        <div class="form-check d-flex justify-content-center m-0">
                                            <input class="form-check-input" type="checkbox" id="check-all-items" checked title="Pilih Semua Produk" style="cursor: pointer; width: 1.15rem; height: 1.15rem;">
                                        </div>
                                    </th>
                                    <th scope="col" style="min-width: 220px;" class="ps-3">Produk</th>
                                    <th scope="col" class="text-center">Harga Satuan</th>
                                    <th scope="col" class="text-center" style="width: 150px;">Kuantitas</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                    <th scope="col" class="text-center pe-3" style="width: 50px;">Hapus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr id="cart-row-{{ $item->id }}" data-cart-id="{{ $item->id }}" data-price="{{ (float) $item->product->final_price }}">
                                        <!-- Checkbox Pilihan Produk -->
                                        <td class="ps-3 pe-0 text-center" style="width: 44px;">
                                            <div class="form-check d-flex justify-content-center m-0">
                                                <input class="form-check-input cart-item-checkbox" 
                                                       type="checkbox" 
                                                       name="cart_ids[]" 
                                                       value="{{ $item->id }}" 
                                                       id="cart-check-{{ $item->id }}" 
                                                       data-cart-id="{{ $item->id }}"
                                                       checked
                                                       title="Pilih produk ini untuk checkout"
                                                       style="cursor: pointer; width: 1.15rem; height: 1.15rem;">
                                            </div>
                                        </td>

                                        <!-- Product Info -->
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <!-- Contain Thumbnail -->
                                                <div style="width: 60px; height: 60px; flex-shrink: 0; background-color: #F8FAFC; border-radius: 8px; overflow: hidden; padding: 4px;" class="border d-flex align-items-center justify-content-center">
                                                    <img src="{{ $item->product->image_url }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="w-100 h-100 object-fit-contain"
                                                         onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                                </div>
                                                <div class="pe-2">
                                                    <div class="d-flex align-items-center gap-1.5 mb-1 flex-wrap">
                                                        <span class="badge bg-light text-secondary border x-small">
                                                            {{ $item->product->category ?? 'Umum' }}
                                                        </span>
                                                        @if($item->product->stock <= 5)
                                                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle x-small fw-semibold">
                                                                <i class="bi bi-exclamation-triangle me-1"></i>Sisa {{ $item->product->stock }} unit
                                                            </span>
                                                        @else
                                                            <span class="badge bg-success-subtle text-success border border-success-subtle x-small fw-semibold">
                                                                <i class="bi bi-check-circle me-1"></i>Stok Tersedia
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="fw-bold text-dark text-decoration-none small d-block mb-1 text-hover-primary" title="{{ $item->product->name }}">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Price -->
                                        <td class="text-center text-nowrap small text-secondary">
                                            @if($item->product->discount_percentage > 0)
                                                <div class="text-primary fw-bold">{{ $item->product->formatted_final_price }}</div>
                                                <div class="text-muted text-decoration-line-through x-small">{{ $item->product->formatted_price }}</div>
                                            @else
                                                <span class="fw-semibold text-dark">{{ $item->product->formatted_price }}</span>
                                            @endif
                                        </td>

                                        <!-- Quantity Stepper Form (AJAX Dynamic Update) -->
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center justify-content-center gap-1.5 p-1 rounded-2 bg-light border">
                                                <button class="btn btn-sm btn-white border shadow-none rounded-2 d-inline-flex align-items-center justify-content-center btn-qty-change"
                                                        type="button"
                                                        id="btn-dec-{{ $item->id }}"
                                                        data-cart-id="{{ $item->id }}"
                                                        data-product-id="{{ $item->product_id }}"
                                                        data-stock="{{ $item->product->stock }}"
                                                        data-delta="-1"
                                                        style="width: 28px; height: 28px; padding: 0; background: #fff;"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                        title="Kurangi kuantitas">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <span class="fw-bold text-dark text-center px-1.5 qty-display"
                                                      id="qty-val-{{ $item->id }}"
                                                      data-cart-id="{{ $item->id }}"
                                                      style="min-width: 32px; font-size: 0.875rem; display: inline-block;">
                                                    {{ $item->quantity }}
                                                </span>
                                                <button class="btn btn-sm btn-white border shadow-none rounded-2 d-inline-flex align-items-center justify-content-center btn-qty-change"
                                                        type="button"
                                                        id="btn-inc-{{ $item->id }}"
                                                        data-cart-id="{{ $item->id }}"
                                                        data-product-id="{{ $item->product_id }}"
                                                        data-stock="{{ $item->product->stock }}"
                                                        data-delta="1"
                                                        style="width: 28px; height: 28px; padding: 0; background: #fff;"
                                                        {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                                                        title="Tambah kuantitas">
                                                    <i class="bi bi-plus"></i>
                                                </button>
                                            </div>
                                        </td>

                                        <!-- Subtotal -->
                                        <td class="text-end fw-bold text-nowrap fs-6 text-blue-600 subtotal-display"
                                            id="subtotal-val-{{ $item->id }}"
                                            style="color: #0866FF;">
                                            {{ $item->formatted_subtotal }}
                                        </td>

                                        <!-- Remove Button -->
                                        <td class="text-center pe-3">
                                            <button type="button" class="btn btn-sm btn-light text-danger p-1 rounded-2 border-0" title="Hapus Produk dari Keranjang" onclick="if(confirm('Hapus {{ addslashes($item->product->name) }} dari keranjang?')) document.getElementById('delete-cart-{{ $item->id }}').submit();">
                                                <i class="bi bi-trash3 fs-6"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Input Kontak & Alamat Pengiriman -->
                <div class="card border-0 bg-white rounded-3 shadow-sm p-4 mb-4" id="section-shipping-form">
                    <h5 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-truck text-primary"></i>
                        <span>Informasi Pengiriman & Catatan</span>
                    </h5>
                    <p class="text-muted small mb-3">Pastikan nomor telepon aktif untuk menerima notifikasi status pengiriman dari kurir.</p>
                    
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="customer_phone" class="form-label small fw-bold text-dark mb-1">
                                Nomor Telepon / WhatsApp <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small"><i class="bi bi-telephone"></i></span>
                                <input type="tel" 
                                       id="customer_phone" 
                                       name="customer_phone" 
                                       class="form-control rounded-end-3 text-dark small" 
                                       value="{{ auth()->user()->phone ?? '081234567890' }}" 
                                       placeholder="Contoh: 08123456789" 
                                       required>
                            </div>
                            <div class="invalid-feedback" id="phone-feedback" style="display: none;">
                                Masukkan nomor telepon/WhatsApp yang valid (minimal 8 angka).
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="shipping_address" class="form-label small fw-bold text-dark mb-1">
                                Alamat Pengiriman Lengkap <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted small"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" 
                                       id="shipping_address" 
                                       name="shipping_address" 
                                       class="form-control rounded-end-3 text-dark small" 
                                       value="{{ auth()->user()->address ?? 'Jl. Merdeka No. 45, Jakarta Pusat' }}" 
                                       placeholder="Nama jalan, nomor rumah, RT/RW, kota/kabupaten" 
                                       required>
                            </div>
                            <div class="invalid-feedback" id="address-feedback" style="display: none;">
                                Masukkan alamat pengiriman lengkap (minimal 5 karakter).
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="pageCartNotes" class="form-label small fw-bold text-dark mb-1">
                                Catatan Tambahan (Opsional)
                            </label>
                            <textarea name="notes" 
                                      id="pageCartNotes" 
                                      class="form-control rounded-3 text-dark small" 
                                      rows="2" 
                                      maxlength="500"
                                      placeholder="Tulis instruksi khusus pengiriman, patokan alamat, atau catatan pesanan..." 
                                      style="resize: none;"></textarea>
                            <div class="x-small text-muted text-end mt-1">Maksimal 500 karakter</div>
                        </div>
                    </div>
                </div>

                <!-- Pilihan Metode Pembayaran (Hanya 2 Pilihan Utama: Midtrans & COD) -->
                <div class="card border-0 bg-white rounded-3 shadow-sm p-4 mb-4" id="section-payment-method">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-wallet2 text-primary"></i>
                            <span>Metode Pembayaran</span>
                        </h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill x-small fw-bold">
                            2 Pilihan Tersedia
                        </span>
                    </div>
                    <p class="text-muted small mb-3">Pilih salah satu metode pembayaran yang Anda inginkan untuk menyelesaikan pesanan.</p>

                    <div class="row g-3">
                        <!-- Pilihan 1: Pembayaran Online via Midtrans Snap -->
                        <div class="col-12 col-md-6">
                            <label class="payment-option-card selected d-flex align-items-start gap-3 h-100" for="pay_method_midtrans">
                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" id="pay_method_midtrans" value="Midtrans Snap" checked>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <strong class="text-dark small d-flex align-items-center gap-1.5">
                                            <i class="bi bi-shield-lock-fill text-primary"></i>
                                            Pembayaran Online
                                        </strong>
                                        <span class="badge bg-primary text-white x-small rounded-pill">Midtrans</span>
                                    </div>
                                    <p class="text-muted x-small mb-2 leading-relaxed">
                                        Bayar instan via <strong>QRIS, GoPay, ShopeePay, Virtual Account BCA / Mandiri / BRI / BNI</strong>, &amp; Kartu Kredit.
                                    </p>
                                    <div class="d-flex align-items-center gap-1 text-primary x-small fw-semibold">
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span>Verifikasi Otomatis</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Pilihan 2: COD (Bayar di Tempat) -->
                        <div class="col-12 col-md-6">
                            <label class="payment-option-card d-flex align-items-start gap-3 h-100" for="pay_method_cod">
                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" id="pay_method_cod" value="Cash on Delivery (COD)">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <strong class="text-dark small d-flex align-items-center gap-1.5">
                                            <i class="bi bi-cash-coin text-success"></i>
                                            COD (Bayar di Tempat)
                                        </strong>
                                        <span class="badge bg-success text-white x-small rounded-pill">Tunai</span>
                                    </div>
                                    <p class="text-muted x-small mb-2 leading-relaxed">
                                        Bayar tunai dengan uang pas langsung kepada kurir saat barang tiba di alamat pengiriman Anda.
                                    </p>
                                    <div class="d-flex align-items-center gap-1 text-success x-small fw-semibold">
                                        <i class="bi bi-truck"></i>
                                        <span>Bayar Saat Barang Diterima</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Order Summary (Right Column) -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 bg-white p-4 sticky-top rounded-3 shadow-sm" style="top: 90px; z-index: 10;">
                    
                    <!-- Header Ringkasan & Data Pemesan -->
                    <div class="pb-3 mb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                            <h5 class="fw-bold text-dark mb-0 fs-6">Ringkasan Pesanan</h5>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill x-small fw-bold">
                                <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi
                            </span>
                        </div>
                        <div class="text-muted x-small">
                            Pemesan: <strong class="text-dark">{{ auth()->user()->name }}</strong>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Total Kuantitas:</span>
                        <span class="text-dark fw-semibold" id="cart-total-quantity">{{ $cartItems->sum('quantity') }} unit</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Biaya Pengiriman:</span>
                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-bold">GRATIS</span>
                    </div>

                    <div class="d-flex justify-content-between mb-3 small text-muted">
                        <span>Biaya Penanganan / Admin:</span>
                        <span class="text-success fw-semibold">Gratis</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4 pb-1">
                        <span class="fw-bold text-dark fs-6">Total Pembayaran:</span>
                        <span class="h3 fw-bold mb-0 text-blue-600" id="cart-total-price" style="color: #0866FF;">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Tombol Bayar Sekarang Solid Biru (Midtrans Snap Trigger) -->
                    <button type="submit" id="pay-button" class="btn btn-primary bg-blue-600 border-0 w-100 py-3 d-flex align-items-center justify-content-center gap-2 fw-bold shadow-sm rounded-3 hover:bg-blue-700" style="background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%);">
                        <span id="pay-button-text">Bayar Sekarang</span>
                        <i class="bi bi-arrow-right fs-5" id="pay-button-icon"></i>
                    </button>

                    <div id="pay-secure-info" class="d-flex align-items-center justify-content-center gap-1.5 mt-3 text-muted x-small text-center">
                        <i class="bi bi-shield-check text-success fs-6"></i>
                        <span>Pembayaran Aman &amp; Terenkripsi via Midtrans</span>
                    </div>
                </div>
            </div>

        </div>
    </form>

    <!-- Hidden standalone forms for deletion -->
    @foreach($cartItems as $item)
        <form id="delete-cart-{{ $item->id }}" action="{{ route('cart.destroy', $item->id) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endif
@endsection

@push('scripts')
<!-- Midtrans Snap.js SDK -->
<script src="{{ config('midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/snap.js') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value;

    const checkAllInput = document.getElementById('check-all-items');
    const itemCheckboxes = document.querySelectorAll('.cart-item-checkbox');
    const payButton = document.getElementById('pay-button');
    const payText = document.getElementById('pay-button-text');
    const payIcon = document.getElementById('pay-button-icon');
    const totalPriceEl = document.getElementById('cart-total-price');
    const totalQtyEl = document.getElementById('cart-total-quantity');
    const headerCountEl = document.getElementById('cart-header-count');

    function formatRupiah(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(number));
    }

    // ==========================================
    // 1. KALKULASI DINAMIS CHECKBOX & RINGKASAN
    // ==========================================
    function updateOrderSummary() {
        let selectedCount = 0;
        let totalSelectedQty = 0;
        let totalSelectedPrice = 0;

        const currentCheckboxes = document.querySelectorAll('.cart-item-checkbox');
        currentCheckboxes.forEach(cb => {
            if (cb.checked) {
                selectedCount++;
                const cartId = cb.value;
                const row = document.getElementById(`cart-row-${cartId}`);
                if (row) {
                    const qtySpan = document.getElementById(`qty-val-${cartId}`);
                    const qty = parseInt(qtySpan ? qtySpan.textContent.trim() : '1', 10) || 1;
                    const price = parseFloat(row.getAttribute('data-price')) || 0;
                    totalSelectedQty += qty;
                    totalSelectedPrice += (qty * price);
                }
            }
        });

        // 1. Perbarui teks Total Kuantitas & Pembayaran
        if (totalQtyEl) {
            totalQtyEl.textContent = `${totalSelectedQty} unit`;
        }
        if (totalPriceEl) {
            totalPriceEl.textContent = formatRupiah(totalSelectedPrice);
        }
        if (headerCountEl) {
            headerCountEl.textContent = `Daftar Produk Terpilih (${totalSelectedQty} Item)`;
        }

        // 2. Kontrol Tombol Bayar Sekarang / Buat Pesanan (COD)
        if (payButton) {
            if (selectedCount === 0) {
                payButton.disabled = true;
                payButton.classList.add('opacity-50');
                payButton.style.cursor = 'not-allowed';
                if (payText) payText.textContent = 'Pilih Produk Dulu';
            } else {
                payButton.disabled = false;
                payButton.classList.remove('opacity-50');
                payButton.style.cursor = 'pointer';
                syncPaymentMethodUI();
            }
        }

        // 3. Sinkronkan status checkbox 'Pilih Semua'
        if (checkAllInput && currentCheckboxes.length > 0) {
            checkAllInput.checked = (selectedCount === currentCheckboxes.length);
            checkAllInput.indeterminate = (selectedCount > 0 && selectedCount < currentCheckboxes.length);
        }
    }

    // ==========================================
    // 1B. SINKRONISASI PILIHAN METODE PEMBAYARAN (MIDTRANS / COD)
    // ==========================================
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const paymentCards = document.querySelectorAll('.payment-option-card');
    const paySecureInfo = document.getElementById('pay-secure-info');

    function syncPaymentMethodUI() {
        const selected = document.querySelector('input[name="payment_method"]:checked')?.value || 'Midtrans Snap';
        const isCod = selected.includes('COD') || selected.includes('Cash on Delivery') || selected.includes('Bayar di Tempat');

        paymentCards.forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });

        if (payButton && !payButton.disabled) {
            if (isCod) {
                if (payText) payText.textContent = 'Buat Pesanan (COD)';
                if (payIcon) payIcon.className = 'bi bi-bag-check fs-5';
                if (paySecureInfo) {
                    paySecureInfo.innerHTML = '<i class="bi bi-cash-coin text-success fs-6"></i><span>Bayar Tunai Pas Saat Kurir Tiba di Alamat</span>';
                }
            } else {
                if (payText) payText.textContent = 'Bayar Sekarang';
                if (payIcon) payIcon.className = 'bi bi-arrow-right fs-5';
                if (paySecureInfo) {
                    paySecureInfo.innerHTML = '<i class="bi bi-shield-check text-success fs-6"></i><span>Pembayaran Aman &amp; Terenkripsi via Midtrans</span>';
                }
            }
        }
    }

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', syncPaymentMethodUI);
    });

    // Listener Checkbox Pilih Semua
    if (checkAllInput) {
        checkAllInput.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.cart-item-checkbox').forEach(cb => {
                cb.checked = isChecked;
            });
            updateOrderSummary();
        });
    }

    // Listener Checkbox Tiap Item Produk
    itemCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateOrderSummary();
        });
    });

    // Jalankan kalkulasi awal saat halaman dimuat
    updateOrderSummary();

    // ==========================================
    // 2. LOGIKA KUANTITAS STEPPER (AJAX)
    // ==========================================
    const updatingCartIds = new Set();
    const qtyButtons = document.querySelectorAll('.btn-qty-change');

    qtyButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            const cartId = this.getAttribute('data-cart-id');
            const productId = this.getAttribute('data-product-id');
            const stock = parseInt(this.getAttribute('data-stock'), 10) || 0;
            const delta = parseInt(this.getAttribute('data-delta'), 10) || 0;

            if (!cartId || updatingCartIds.has(cartId)) {
                return;
            }

            const qtySpan = document.getElementById(`qty-val-${cartId}`);
            const currentQty = parseInt(qtySpan ? qtySpan.textContent.trim() : '1', 10);
            const newQty = currentQty + delta;

            // Validasi batas minimal kuantitas
            if (newQty < 1) {
                return;
            }

            // Validasi batas maksimal stok di sisi client
            if (stock > 0 && newQty > stock) {
                const stockMsg = `Jumlah melebihi stok yang tersedia (${stock} unit).`;
                if (typeof window.showCartToast === 'function') {
                    window.showCartToast(stockMsg);
                } else {
                    alert(stockMsg);
                }
                const btnInc = document.getElementById(`btn-inc-${cartId}`);
                if (btnInc) btnInc.disabled = true;
                return;
            }

            const btnDec = document.getElementById(`btn-dec-${cartId}`);
            const btnInc = document.getElementById(`btn-inc-${cartId}`);

            // Tandai item sedang diproses dan kunci tombol sementara
            updatingCartIds.add(cartId);
            if (btnDec) btnDec.disabled = true;
            if (btnInc) btnInc.disabled = true;

            fetch(`{{ url('cart') }}/${cartId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: newQty,
                    product_id: productId,
                    _token: csrfToken
                })
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    return null;
                }
                return response.json().then(data => ({
                    ok: response.ok,
                    status: response.status,
                    data: data
                }));
            })
            .then(result => {
                if (!result) return;

                if (result.ok && result.data && result.data.success) {
                    const item = result.data.item;

                    // 1. Perbarui teks kuantitas pada baris produk
                    if (qtySpan) {
                        qtySpan.textContent = item.quantity;
                    }

                    // 2. Perbarui subtotal pada baris produk
                    const subtotalEl = document.getElementById(`subtotal-val-${cartId}`);
                    if (subtotalEl) {
                        subtotalEl.textContent = item.formatted_subtotal;
                    }

                    // 3. Sinkronkan Ringkasan Pesanan (hanya produk yang sedang dicentang)
                    updateOrderSummary();

                    // 4. Atur status tombol disable sesuai kuantitas terbaru & stok
                    if (btnDec) btnDec.disabled = (item.quantity <= 1);
                    if (btnInc) btnInc.disabled = (item.quantity >= item.stock);

                    // 5. Perbarui badge keranjang di navbar jika ada (total seluruh isi keranjang)
                    const cartCount = parseInt(result.data.total_quantity, 10);
                    const navBadge = document.getElementById('cart-badge-count');
                    if (navBadge) {
                        navBadge.innerText = cartCount;
                        if (cartCount > 0) {
                            navBadge.classList.remove('d-none');
                            navBadge.style.display = 'inline-flex';
                        } else {
                            navBadge.classList.add('d-none');
                            navBadge.style.display = 'none';
                        }
                    }

                    document.querySelectorAll('.nav-cart-badge, .mobile-cart-badge, #cart-badge-count-mobile, #cart-badge-count-drawer').forEach(badge => {
                        badge.innerText = cartCount;
                        if (cartCount > 0) {
                            badge.classList.remove('d-none');
                            if (badge.classList.contains('nav-cart-badge')) {
                                badge.style.display = 'inline-flex';
                            } else {
                                badge.style.display = '';
                            }
                        } else {
                            badge.classList.add('d-none');
                            badge.style.display = 'none';
                        }
                    });
                } else {
                    const errorMsg = (result.data && result.data.message)
                        ? result.data.message
                        : 'Gagal memperbarui kuantitas produk.';

                    if (typeof window.showCartToast === 'function') {
                        window.showCartToast(errorMsg);
                    } else {
                        alert(errorMsg);
                    }

                    if (btnDec) btnDec.disabled = (currentQty <= 1);
                    if (btnInc) btnInc.disabled = (stock > 0 && currentQty >= stock);
                }
            })
            .catch(error => {
                console.error('Error saat update kuantitas keranjang:', error);
                alert('Terjadi kesalahan koneksi saat memperbarui keranjang.');
                if (btnDec) btnDec.disabled = (currentQty <= 1);
                if (btnInc) btnInc.disabled = (stock > 0 && currentQty >= stock);
            })
            .finally(() => {
                updatingCartIds.delete(cartId);
            });
        });
    });

    // ==========================================
    // 3. MIDTRANS SNAP CHECKOUT (ANTI DOUBLE-CLICK DEBOUNCE)
    // ==========================================
    const checkoutForm = document.getElementById('cart-checkout-form');
    let isSubmitting = false;

    if (checkoutForm && payButton) {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) {
                return;
            }

            // Validasi minimal 1 produk terpilih untuk di-checkout
            const selectedCheckboxes = checkoutForm.querySelectorAll('.cart-item-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Silakan centang minimal satu produk di keranjang belanja Anda untuk melanjutkan checkout.');
                return;
            }

            // Validasi field kontak & alamat di sisi frontend
            const phoneInput = document.getElementById('customer_phone');
            const addressInput = document.getElementById('shipping_address');
            const phoneFeedback = document.getElementById('phone-feedback');
            const addressFeedback = document.getElementById('address-feedback');

            let isValid = true;

            // Validasi nomor telepon (min 8 karakter)
            const phoneVal = phoneInput ? phoneInput.value.trim() : '';
            if (!phoneVal || phoneVal.length < 8) {
                if (phoneInput) phoneInput.classList.add('is-invalid');
                if (phoneFeedback) phoneFeedback.style.display = 'block';
                isValid = false;
            } else {
                if (phoneInput) phoneInput.classList.remove('is-invalid');
                if (phoneFeedback) phoneFeedback.style.display = 'none';
            }

            // Validasi alamat pengiriman (min 5 karakter)
            const addressVal = addressInput ? addressInput.value.trim() : '';
            if (!addressVal || addressVal.length < 5) {
                if (addressInput) addressInput.classList.add('is-invalid');
                if (addressFeedback) addressFeedback.style.display = 'block';
                isValid = false;
            } else {
                if (addressInput) addressInput.classList.remove('is-invalid');
                if (addressFeedback) addressFeedback.style.display = 'none';
            }

            if (!isValid) {
                document.getElementById('section-shipping-form')?.scrollIntoView({ behavior: 'smooth' });
                return;
            }

            // Kunci tombol untuk mencegah transaksi ganda (double-click debounce)
            isSubmitting = true;
            const originalText = payText ? payText.textContent : 'Bayar Sekarang';
            payButton.disabled = true;

            const selectedMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'Midtrans Snap';
            const isCodChoice = selectedMethod.includes('COD') || selectedMethod.includes('Cash on Delivery') || selectedMethod.includes('Bayar di Tempat');

            if (payText) payText.textContent = isCodChoice ? 'Menyimpan Pesanan COD...' : 'Menghubungkan Midtrans...';
            if (payIcon) payIcon.className = 'spinner-border spinner-border-sm';

            function resetPayButton() {
                isSubmitting = false;
                payButton.disabled = false;
                if (payText) payText.textContent = originalText;
                if (payIcon) payIcon.className = 'bi bi-arrow-right fs-5';
                updateOrderSummary();
            }

            const formData = new FormData(checkoutForm);

            fetch('{{ route("checkout.snap-token") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => {
                if (res.status === 401) {
                    window.location.href = '{{ route("login") }}';
                    return null;
                }
                return res.json().then(data => ({
                    ok: res.ok,
                    status: res.status,
                    data: data
                }));
            })
            .then(result => {
                if (!result) return;

                if (result.ok && result.data) {
                    // Jika metode COD atau tidak membutuhkan Snap Token, langsung arahkan ke halaman sukses
                    if (result.data.is_cod || !result.data.snap_token) {
                        if (result.data.redirect_url) {
                            window.location.href = result.data.redirect_url;
                        } else {
                            window.location.reload();
                        }
                        return;
                    }

                    // Panggil popup Snap Midtrans untuk pembayaran online
                    if (typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function') {
                        window.snap.pay(result.data.snap_token, {
                            onSuccess: function (res) {
                                window.location.href = result.data.redirect_url;
                            },
                            onPending: function (res) {
                                window.location.href = result.data.redirect_url;
                            },
                            onError: function (res) {
                                alert('Pembayaran gagal atau dibatalkan.');
                                resetPayButton();
                            },
                            onClose: function () {
                                if (result.data.redirect_url) {
                                    window.location.href = result.data.redirect_url;
                                } else {
                                    resetPayButton();
                                }
                            }
                        });
                    } else {
                        // Fallback jika Snap SDK gagal termuat (misal kendala jaringan/adblock)
                        if (result.data.redirect_url) {
                            window.location.href = result.data.redirect_url;
                        } else {
                            alert('Snap SDK Midtrans belum siap dimuat. Silakan muat ulang halaman.');
                            resetPayButton();
                        }
                    }
                } else {
                    const message = (result.data && result.data.message)
                        ? result.data.message
                        : 'Gagal membuat transaksi pembayaran.';
                    alert(message);
                    resetPayButton();
                }
            })
            .catch(err => {
                console.error('Midtrans checkout error:', err);
                alert('Terjadi kesalahan koneksi saat memproses checkout.');
                resetPayButton();
            });
        });
    }
});
</script>
@endpush
