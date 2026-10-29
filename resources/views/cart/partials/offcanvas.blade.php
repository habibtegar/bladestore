<!-- Header Panel -->
<div class="offcanvas-header border-bottom py-3 px-3.5 bg-white d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 34px; height: 34px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); font-size: 1rem;">
            <i class="bi bi-cart3"></i>
        </div>
        <h5 class="offcanvas-title fw-bold text-dark mb-0 fs-5" id="cartOffcanvasLabel">Keranjang Belanja</h5>
    </div>
    <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
</div>

<!-- Ringkasan Data Pemesan (Nama, Email, Badge Hijau Akun Terverifikasi) -->
<div class="px-3.5 py-2.5 border-bottom bg-slate-50" style="background-color: #F8FAFC;">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2 overflow-hidden pe-2">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0 bg-blue-600" style="width: 32px; height: 32px; background-color: #2563EB; font-size: 0.8rem;">
                {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'G' }}
            </div>
            <div class="text-truncate">
                <div class="fw-bold text-dark small text-truncate" style="line-height: 1.2;">
                    {{ auth()->check() ? auth()->user()->name : 'Tamu / Pelanggan' }}
                </div>
                <div class="text-muted x-small text-truncate">
                    {{ auth()->check() ? auth()->user()->email : 'guest@bladestore.com' }}
                </div>
            </div>
        </div>
        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2.5 py-1 x-small fw-bold flex-shrink-0">
            <i class="bi bi-patch-check-fill me-1"></i> Akun Terverifikasi
        </span>
    </div>
</div>

@if($navCartItems->isEmpty())
    <!-- Empty State -->
    <div class="offcanvas-body p-4 d-flex flex-column align-items-center justify-content-center text-center">
        <div class="p-3.5 rounded-circle mb-3 bg-blue-50 text-blue-600" style="background-color: #EFF6FF; color: #2563EB;">
            <i class="bi bi-cart-x fs-1"></i>
        </div>
        <h6 class="fw-bold text-dark mb-1">Keranjang Masih Kosong</h6>
        <p class="text-muted small mb-4">Anda belum memasukkan produk apapun ke dalam keranjang belanja.</p>
        <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-sm px-4 py-2 text-white fw-bold rounded-3 shadow-sm btn-primary bg-blue-600 border-blue-600 hover:bg-blue-700" data-bs-dismiss="offcanvas">
            Mulai Belanja Sekarang
        </a>
    </div>
@else
    <!-- Form Checkout Langsung dari Side Panel -->
    <form action="{{ route('checkout.process') }}" method="POST" class="d-flex flex-column h-100 overflow-hidden m-0 p-0">
        @csrf
        <div class="offcanvas-body p-4 flex-grow-1 overflow-y-auto space-y-4">
            
            <!-- 1. Daftar Item Keranjang -->
            <div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark small">Daftar Produk ({{ $navCartItems->sum('quantity') }})</span>
                    <a href="{{ route('cart.index') }}" class="x-small text-decoration-none fw-semibold text-blue-600" style="color: #2563EB;">
                        Kelola Keranjang
                    </a>
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($navCartItems as $cartItem)
                        <div class="p-2.5 rounded-3 border bg-white d-flex align-items-center justify-content-between gap-2.5" style="border-color: #F1F5F9 !important;">
                            <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                                <div style="width: 48px; height: 48px; border-radius: 6px; overflow: hidden; background-color: #F8FAFC; border: 1px solid #E2E8F0; flex-shrink: 0;">
                                     <img src="{{ optional($cartItem->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                         alt="{{ $cartItem->product->name ?? 'Produk' }}"
                                         class="w-100 h-100 object-fit-cover"
                                         onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                </div>
                                <div class="text-truncate">
                                    <div class="fw-semibold text-dark x-small text-truncate" style="line-height: 1.25;">
                                        {{ $cartItem->product->name ?? 'Produk' }}
                                    </div>
                                    <div class="x-small text-muted">
                                        {{ $cartItem->quantity }}x {{ optional($cartItem->product)->discount_percentage > 0 ? $cartItem->product->formatted_final_price : ($cartItem->product->formatted_price ?? '') }}
                                    </div>
                                    <div class="fw-bold x-small text-blue-600" style="color: #2563EB;">
                                        Rp {{ number_format((float)$cartItem->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <form action="{{ route('cart.destroy', $cartItem->id) }}" method="POST" class="d-inline form-delete-nav-cart">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Hapus">
                                        <i class="bi bi-trash3" style="font-size: 0.85rem;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Form Input Alamat & Kontak -->
            <div class="p-3 rounded-3 border" style="background-color: #FAFAFA; border-color: #E2E8F0 !important;">
                <div class="fw-bold text-dark x-small mb-2 d-flex align-items-center gap-1.5">
                    <i class="bi bi-geo-alt-fill text-blue-600" style="color: #2563EB;"></i>
                    <span>Tujuan Pengiriman & WhatsApp</span>
                </div>
                <div class="mb-2">
                    <input type="text" name="customer_phone" class="form-control form-control-sm x-small rounded-2" value="{{ auth()->user()->phone ?? '081234567890' }}" placeholder="Nomor Telepon / WhatsApp" required>
                </div>
                <div>
                    <input type="text" name="shipping_address" class="form-control form-control-sm x-small rounded-2" value="{{ auth()->user()->address ?? 'Jl. Merdeka No. 45, Jakarta Pusat' }}" placeholder="Alamat Pengiriman Lengkap" required>
                </div>
            </div>

            <!-- 3. Form Input Textarea: Catatan Tambahan (Opsional) -->
            <div>
                <label for="offcanvasOrderNotes" class="form-label fw-bold text-dark small mb-1.5 d-flex align-items-center gap-1.5">
                    <i class="bi bi-pencil-square text-blue-600" style="color: #2563EB;"></i>
                    <span>Catatan Tambahan (Opsional)</span>
                </label>
                <textarea name="notes" id="offcanvasOrderNotes" class="form-control rounded-3 text-secondary x-small p-2.5" rows="2" placeholder="Tulis instruksi khusus pengiriman, patokan alamat, atau request kemasan..." style="resize: none; border-color: #CBD5E1;"></textarea>
            </div>

            <!-- Midtrans Automatic Payment Method -->
            <input type="hidden" name="payment_method" value="Midtrans Snap">
            <div class="p-3 rounded-3" style="background-color: #EFF6FF; border: 1px solid #DBEAFE;">
                <div class="d-flex align-items-center gap-2 text-primary small fw-semibold">
                    <i class="bi bi-shield-check fs-5"></i>
                    <span>Pembayaran Otomatis Midtrans</span>
                </div>
                <div class="text-muted x-small mt-1">
                    QRIS, Virtual Account, & E-Wallet akan muncul otomatis saat tombol bayar ditekan.
                </div>
            </div>

        </div>

        <!-- Footer Panel (Sticky Bottom with Top Border & Shadow) -->
        <div class="offcanvas-footer sticky-bottom border-t border-top bg-white shadow-md p-4 p-sm-5" style="border-top: 1px solid #E2E8F0; box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.06); z-index: 10;">
            <div class="d-flex justify-content-between align-items-baseline mb-3">
                <span class="text-muted small fw-semibold">Total Pembayaran</span>
                <span class="h4 fw-bold mb-0 text-blue-600" style="color: #2563EB; font-size: 1.45rem;">
                    Rp {{ number_format($navCartTotal, 0, ',', '.') }}
                </span>
            </div>
            <button type="submit" class="btn w-100 py-3 fw-bold text-white d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm bg-blue-600 hover:bg-blue-700 btn-primary border-blue-600 border-0" style="font-size: 1rem; background-color: #2563EB; transition: background-color 0.2s ease;">
                <span>Bayar Sekarang</span>
                <i class="bi bi-arrow-right fs-5"></i>
            </button>
        </div>
    </form>
@endif

