@extends('layouts.app')

@section('title', 'Keranjang Belanja — BladeStore')

@section('content')
<!-- Page Header -->
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
    <div>
        <h2 class="h3 fw-bold text-dark mb-1">Keranjang Belanja</h2>
        <p class="text-muted small mb-0">Periksa kembali daftar belanjaan Anda dan selesaikan pesanan dengan mudah.</p>
    </div>
    <div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 d-inline-flex align-items-center gap-1.5 text-nowrap rounded-2">
            <i class="bi bi-arrow-left"></i> Belanja Lainnya
        </a>
    </div>
</div>

@if($cartItems->isEmpty())
    <!-- Empty Cart State -->
    <div class="card border-0 p-5 text-center my-4 bg-white rounded-4 shadow-sm">
        <div class="mb-3">
            <span class="p-4 rounded-circle d-inline-block bg-blue-50 text-blue-600" style="background-color: #EFF6FF; color: #2563EB;">
                <i class="bi bi-cart-x fs-1"></i>
            </span>
        </div>
        <h4 class="fw-bold text-dark mb-1">Keranjang Belanja Anda Kosong</h4>
        <p class="text-muted small mb-4 col-md-6 mx-auto">
            Anda belum memilih produk untuk dimasukkan ke dalam keranjang. Jelajahi katalog produk pilihan BladeStore sekarang.
        </p>
        <div>
            <a href="{{ route('products.index') }}" class="btn btn-primary bg-blue-600 border-blue-600 px-4 py-2.5 fw-bold text-white shadow-sm rounded-3 hover:bg-blue-700">
                <i class="bi bi-shop me-1"></i> Mulai Belanja Sekarang
            </a>
        </div>
    </div>
@else
    <form id="cart-checkout-form" action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row g-4 lg:grid lg:grid-cols-12 lg:gap-6">
            
            <!-- Cart Items List (Left Column) -->
            <div class="col-12 col-lg-8 lg:col-span-8 space-y-6">
                <div class="card border-0 bg-white overflow-hidden rounded-3 shadow-sm">
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-slate-50" style="background-color: #F8FAFC;">
                        <span class="fw-bold text-dark small" id="cart-header-count">Daftar Produk Terpilih ({{ $cartItems->sum('quantity') }} Item)</span>
                        <span class="text-muted x-small">BladeStore Official Checkout</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr>
                                    <th scope="col" style="width: 46px;" class="ps-3 pe-0 text-center">
                                        <div class="form-check d-flex justify-content-center m-0">
                                            <input class="form-check-input" type="checkbox" id="check-all-items" checked title="Pilih Semua Produk" style="cursor: pointer; width: 1.15rem; height: 1.15rem;">
                                        </div>
                                    </th>
                                    <th scope="col" style="min-width: 240px;" class="ps-3">Produk</th>
                                    <th scope="col" class="text-center">Harga</th>
                                    <th scope="col" class="text-center" style="width: 160px;">Kuantitas</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                    <th scope="col" class="text-center pe-4" style="width: 60px;">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($cartItems as $item)
                                    <tr id="cart-row-{{ $item->id }}" data-cart-id="{{ $item->id }}" data-price="{{ (float) $item->product->final_price }}">
                                        <!-- Checkbox Pilihan Produk -->
                                        <td class="ps-3 pe-0 text-center" style="width: 46px;">
                                            <div class="form-check d-flex justify-content-center m-0">
                                                <input class="form-check-input cart-item-checkbox" 
                                                       type="checkbox" 
                                                       name="cart_ids[]" 
                                                       value="{{ $item->id }}" 
                                                       id="cart-check-{{ $item->id }}" 
                                                       data-cart-id="{{ $item->id }}"
                                                       checked
                                                       title="Pilih produk ini"
                                                       style="cursor: pointer; width: 1.15rem; height: 1.15rem;">
                                            </div>
                                        </td>

                                        <!-- Product Info -->
                                        <td class="ps-3 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width: 58px; height: 58px; flex-shrink: 0; background-color: #F8FAFC; border-radius: 8px; overflow: hidden;" class="border">
                                                    <img src="{{ $item->product->image_url }}"
                                                         alt="{{ $item->product->name }}"
                                                         class="w-100 h-100 object-fit-cover"
                                                         onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                                </div>
                                                <div>
                                                    <span class="badge bg-light text-secondary border x-small mb-1">
                                                        {{ $item->product->category ?? 'Umum' }}
                                                    </span>
                                                    <a href="{{ route('products.show', $item->product->slug) }}" class="fw-bold text-dark text-decoration-none small d-block mb-1">
                                                        {{ $item->product->name }}
                                                    </a>
                                                    <span class="text-muted x-small">
                                                        Stok: <strong>{{ $item->product->stock }}</strong> unit
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Price -->
                                        <td class="text-center text-nowrap small fw-semibold text-secondary">
                                            @if($item->product->discount_percentage > 0)
                                                <div class="text-primary fw-bold">{{ $item->product->formatted_final_price }}</div>
                                                <div class="text-muted text-decoration-line-through x-small">{{ $item->product->formatted_price }}</div>
                                            @else
                                                {{ $item->product->formatted_price }}
                                            @endif
                                        </td>

                                        <!-- Quantity Stepper Form (AJAX Dynamic Update) -->
                                        <td class="text-center">
                                            <div class="d-inline-flex align-items-center justify-content-center gap-2">
                                                <button class="btn btn-sm btn-outline-secondary rounded-2 d-inline-flex align-items-center justify-content-center btn-qty-change"
                                                        type="button"
                                                        id="btn-dec-{{ $item->id }}"
                                                        data-cart-id="{{ $item->id }}"
                                                        data-product-id="{{ $item->product_id }}"
                                                        data-stock="{{ $item->product->stock }}"
                                                        data-delta="-1"
                                                        style="width: 32px; height: 32px; padding: 0;"
                                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                        title="Kurangi kuantitas">
                                                    <i class="bi bi-dash-lg"></i>
                                                </button>
                                                <span class="fw-bold text-dark text-center px-2 qty-display"
                                                      id="qty-val-{{ $item->id }}"
                                                      data-cart-id="{{ $item->id }}"
                                                      style="min-width: 38px; font-size: 0.95rem; display: inline-block;">
                                                    {{ $item->quantity }}
                                                </span>
                                                <button class="btn btn-sm btn-outline-secondary rounded-2 d-inline-flex align-items-center justify-content-center btn-qty-change"
                                                        type="button"
                                                        id="btn-inc-{{ $item->id }}"
                                                        data-cart-id="{{ $item->id }}"
                                                        data-product-id="{{ $item->product_id }}"
                                                        data-stock="{{ $item->product->stock }}"
                                                        data-delta="1"
                                                        style="width: 32px; height: 32px; padding: 0;"
                                                        {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                                                        title="Tambah kuantitas">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </td>

                                        <!-- Subtotal -->
                                        <td class="text-end fw-bold text-nowrap fs-6 text-blue-600 subtotal-display"
                                            id="subtotal-val-{{ $item->id }}"
                                            style="color: #2563EB;">
                                            {{ $item->formatted_subtotal }}
                                        </td>

                                        <!-- Remove Button -->
                                        <td class="text-center pe-4">
                                            <button type="button" class="btn btn-sm btn-link text-danger p-0" title="Hapus Produk" onclick="if(confirm('Hapus produk ini dari keranjang?')) document.getElementById('delete-cart-{{ $item->id }}').submit();">
                                                <i class="bi bi-trash3 fs-6"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Input Catatan & Pengiriman -->
                <div class="card border-0 bg-white rounded-3 shadow-sm p-4">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-pencil-square text-blue-600" style="color: #2563EB;"></i>
                        <span>Informasi Pengiriman & Catatan</span>
                    </h5>
                    
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-dark">Nomor Telepon / WhatsApp <span class="text-danger">*</span></label>
                            <input type="text" name="customer_phone" class="form-control rounded-3 text-secondary small" value="{{ auth()->user()->phone ?? '081234567890' }}" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-dark">Alamat Pengiriman Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="shipping_address" class="form-control rounded-3 text-secondary small" value="{{ auth()->user()->address ?? 'Jl. Merdeka No. 45, Jakarta Pusat' }}" placeholder="Nama jalan, nomor rumah, RT/RW, kota" required>
                        </div>
                        <div class="col-12">
                            <label for="pageCartNotes" class="form-label small fw-bold text-dark">Catatan Tambahan (Opsional)</label>
                            <textarea name="notes" id="pageCartNotes" class="form-control rounded-3 text-secondary small" rows="2" placeholder="Tulis instruksi khusus pengiriman, patokan alamat, atau catatan kemasan..." style="resize: none; border-color: #CBD5E1;"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Midtrans Automatic Payment Method (Hidden Field) -->
                <input type="hidden" name="payment_method" value="Midtrans Snap">

                <!-- Midtrans Automated Payment Info Banner -->
                <div class="card border-0 rounded-3 shadow-sm p-3.5" style="background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%); border: 1px solid #DBEAFE !important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background-color: #DBEAFE; color: #2563EB;">
                            <i class="bi bi-shield-lock-fill fs-5"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-dark d-block mb-1 small">Sistem Pembayaran Otomatis Midtrans</span>
                            <span class="text-muted x-small d-block leading-relaxed">
                                Pilihan pembayaran lengkap (<strong>QRIS Instant, GoPay, ShopeePay, Virtual Account Bank BCA / Mandiri / BRI / BNI</strong>, dan <strong>Kartu Kredit</strong>) akan muncul otomatis di popup Midtrans setelah Anda menekan tombol <em>Bayar Sekarang</em>.
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Order Summary (Right Column) -->
            <div class="col-12 col-lg-4 lg:col-span-4">
                <div class="card border-0 bg-white p-4 sticky sticky-top top-24 rounded-3 shadow-sm" style="top: 90px; z-index: 10;">
                    
                    <!-- Header Ringkasan & Data Pemesan -->
                    <div class="pb-3 mb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="fw-bold text-dark mb-0">Ringkasan Pesanan</h5>
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill x-small fw-bold">
                                <i class="bi bi-patch-check-fill me-1"></i> Akun Terverifikasi
                            </span>
                        </div>
                        <div class="text-muted x-small">
                            Pemesan: <strong class="text-dark">{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }})
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2 small text-muted">
                        <span>Total Kuantitas:</span>
                        <span class="text-dark fw-semibold" id="cart-total-quantity">{{ $cartItems->sum('quantity') }} unit</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 small text-muted">
                        <span>Biaya Pengiriman:</span>
                        <span class="text-success fw-bold">GRATIS</span>
                    </div>

                    <hr class="my-3">

                    <div class="d-flex justify-content-between align-items-baseline mb-4">
                        <span class="fw-bold text-dark fs-6">Total Pembayaran:</span>
                        <span class="h3 fw-bold mb-0 text-blue-600" id="cart-total-price" style="color: #2563EB;">
                            Rp {{ number_format($totalPrice, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- Tombol Bayar Sekarang Solid Biru (Midtrans Snap Trigger) -->
                    <button type="submit" id="pay-button" class="btn btn-primary bg-blue-600 border-blue-600 w-100 py-3 d-flex align-items-center justify-content-center gap-2 fw-bold shadow-sm rounded-3 hover:bg-blue-700">
                        <span id="pay-button-text">Bayar Sekarang</span>
                        <i class="bi bi-arrow-right fs-5" id="pay-button-icon"></i>
                    </button>

                    <div class="d-flex align-items-center justify-content-center gap-1.5 mt-3 text-muted x-small">
                        <i class="bi bi-shield-check text-success fs-6"></i>
                        <span>Pembayaran Aman & Otomatis via Midtrans</span>
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
    // KALKULASI DINAMIS CHECKBOX & RINGKASAN
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

        // 2. Kontrol Tombol Bayar Sekarang (Disable jika 0 produk dicentang)
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
                if (payText) payText.textContent = 'Bayar Sekarang';
            }
        }

        // 3. Sinkronkan status checkbox 'Pilih Semua'
        if (checkAllInput && currentCheckboxes.length > 0) {
            checkAllInput.checked = (selectedCount === currentCheckboxes.length);
            checkAllInput.indeterminate = (selectedCount > 0 && selectedCount < currentCheckboxes.length);
        }
    }

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

                    // Kembalikan tombol ke kondisi kuantitas valid sebelumnya
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
    // MIDTRANS SNAP POPUP CHECKOUT
    // ==========================================
    const checkoutForm = document.getElementById('cart-checkout-form');

    if (checkoutForm && payButton) {
        checkoutForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Validasi minimal 1 produk terpilih untuk di-checkout
            const selectedCheckboxes = checkoutForm.querySelectorAll('.cart-item-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Silakan pilih minimal satu produk di keranjang belanja Anda untuk melanjutkan checkout.');
                return;
            }

            // Validasi field kontak & alamat di sisi frontend
            const phoneInput = checkoutForm.querySelector('input[name="customer_phone"]');
            const addressInput = checkoutForm.querySelector('input[name="shipping_address"]');

            if (!phoneInput || !phoneInput.value.trim()) {
                alert('Silakan isi nomor telepon/WhatsApp terlebih dahulu.');
                phoneInput?.focus();
                return;
            }

            if (!addressInput || !addressInput.value.trim()) {
                alert('Silakan isi alamat pengiriman lengkap terlebih dahulu.');
                addressInput?.focus();
                return;
            }

            const originalText = payText ? payText.textContent : 'Bayar Sekarang';
            payButton.disabled = true;
            if (payText) payText.textContent = 'Menghubungkan Midtrans...';
            if (payIcon) payIcon.className = 'spinner-border spinner-border-sm';

            function resetPayButton() {
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

                if (result.ok && result.data && result.data.snap_token) {
                    // Panggil popup Snap Midtrans
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
                                alert('Anda menutup popup sebelum menyelesaikan pembayaran.');
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
                        : 'Gagal membuat transaksi pembayaran Midtrans.';
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
