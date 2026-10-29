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
    <form action="{{ route('checkout.process') }}" method="POST">
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
                                    <th scope="col" style="min-width: 250px;" class="ps-4">Produk</th>
                                    <th scope="col" class="text-center">Harga</th>
                                    <th scope="col" class="text-center" style="width: 160px;">Kuantitas</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                    <th scope="col" class="text-center pe-4" style="width: 60px;">Hapus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($cartItems as $item)
                                    <tr id="cart-row-{{ $item->id }}">
                                        <!-- Product Info -->
                                        <td class="ps-4 py-3">
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

                <!-- Card-like Radio Button Pilihan Metode Pembayaran -->
                <div class="card border-0 bg-white rounded-3 shadow-sm p-4">
                    <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                        <i class="bi bi-credit-card-2-front-fill text-blue-600" style="color: #2563EB;"></i>
                        <span>Pilihan Metode Pembayaran</span>
                    </h5>
                    
                    <div class="d-flex flex-column gap-2.5">
                        <!-- 1. Transfer Bank -->
                        <label class="payment-card-label m-0">
                            <input type="radio" name="payment_method" value="Transfer Bank Manual" class="payment-radio-input" checked>
                            <div class="payment-radio-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-card-icon">
                                        <i class="bi bi-bank2"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">Transfer Bank Manual</div>
                                        <div class="text-muted small">BCA, Mandiri, BRI, BNI (Konfirmasi Otomatis/Manual)</div>
                                    </div>
                                </div>
                                <div class="check-indicator">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                            </div>
                        </label>

                        <!-- 2. QRIS -->
                        <label class="payment-card-label m-0">
                            <input type="radio" name="payment_method" value="QRIS Instant" class="payment-radio-input">
                            <div class="payment-radio-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-card-icon">
                                        <i class="bi bi-qr-code-scan"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">QRIS Instant</div>
                                        <div class="text-muted small">Gopay, OVO, Dana, ShopeePay, LinkAja</div>
                                    </div>
                                </div>
                                <div class="check-indicator">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                            </div>
                        </label>

                        <!-- 3. COD -->
                        <label class="payment-card-label m-0">
                            <input type="radio" name="payment_method" value="COD (Bayar di Tempat)" class="payment-radio-input">
                            <div class="payment-radio-card">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="payment-card-icon">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">COD (Bayar di Tempat)</div>
                                        <div class="text-muted small">Bayar tunai kepada kurir saat pesanan sampai di rumah</div>
                                    </div>
                                </div>
                                <div class="check-indicator">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                            </div>
                        </label>
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

                    <!-- Tombol Bayar Sekarang Solid Biru -->
                    <button type="submit" class="btn btn-primary bg-blue-600 border-blue-600 w-100 py-3 d-flex align-items-center justify-content-center gap-2 fw-bold shadow-sm rounded-3 hover:bg-blue-700">
                        <span>Bayar Sekarang</span>
                        <i class="bi bi-arrow-right fs-5"></i>
                    </button>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value;

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

                    // 3. Perbarui Total Pembayaran di Ringkasan Pesanan
                    const totalPriceEl = document.getElementById('cart-total-price');
                    if (totalPriceEl) {
                        totalPriceEl.textContent = result.data.formatted_total_price;
                    }

                    // 4. Perbarui Total Kuantitas di Ringkasan Pesanan
                    const totalQtyEl = document.getElementById('cart-total-quantity');
                    if (totalQtyEl) {
                        totalQtyEl.textContent = `${result.data.total_quantity} unit`;
                    }

                    // 5. Perbarui Header Total Item
                    const headerCountEl = document.getElementById('cart-header-count');
                    if (headerCountEl) {
                        headerCountEl.textContent = `Daftar Produk Terpilih (${result.data.total_quantity} Item)`;
                    }

                    // 6. Atur status tombol disable sesuai kuantitas terbaru & stok
                    if (btnDec) btnDec.disabled = (item.quantity <= 1);
                    if (btnInc) btnInc.disabled = (item.quantity >= item.stock);

                    // 7. Perbarui badge keranjang di navbar jika ada
                    document.querySelectorAll('.nav-cart-badge, .mobile-cart-badge').forEach(badge => {
                        badge.textContent = result.data.total_quantity;
                        if (result.data.total_quantity > 0) {
                            badge.classList.remove('d-none');
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
});
</script>
@endpush
