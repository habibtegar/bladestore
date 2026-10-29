<!-- Header Panel -->
<div class="offcanvas-header border-bottom py-3 px-3.5 bg-white d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 34px; height: 34px; background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%); font-size: 1rem;">
            <i class="bi bi-cart3"></i>
        </div>
        <div>
            <h5 class="offcanvas-title fw-bold text-dark mb-0 fs-5" id="cartOffcanvasLabel">Keranjang Belanja</h5>
            <span class="text-muted x-small">Mini Cart Ringkas</span>
        </div>
    </div>
    <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
</div>

<!-- Ringkasan Data Pemesan (Nama & Status Akun) -->
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
    <!-- Mini Cart Body: Hanya menampilkan ringkasan daftar produk -->
    <div class="d-flex flex-column h-100 overflow-hidden m-0 p-0">
        <div class="offcanvas-body p-3.5 flex-grow-1 overflow-y-auto">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold text-dark small">Daftar Produk ({{ $navCartItems->sum('quantity') }} Item)</span>
                <span class="text-muted x-small">Tersimpan di Keranjang</span>
            </div>

            <!-- List Item Keranjang Singkat -->
            <div class="d-flex flex-column gap-2.5">
                @foreach($navCartItems as $cartItem)
                    <div class="p-2.5 rounded-3 border bg-white d-flex align-items-center justify-content-between gap-2.5 shadow-xs" style="border-color: #E2E8F0 !important;">
                        <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                            <div style="width: 52px; height: 52px; border-radius: 8px; overflow: hidden; background-color: #F8FAFC; border: 1px solid #E2E8F0; flex-shrink: 0;">
                                <img src="{{ optional($cartItem->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                     alt="{{ $cartItem->product->name ?? 'Produk' }}"
                                     class="w-100 h-100 object-fit-cover"
                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                            </div>
                            <div class="text-truncate">
                                <a href="{{ route('products.show', $cartItem->product->slug ?? '#') }}" class="fw-semibold text-dark text-decoration-none x-small text-truncate d-block mb-0.5" style="line-height: 1.3;" title="{{ $cartItem->product->name ?? 'Produk' }}">
                                    {{ $cartItem->product->name ?? 'Produk' }}
                                </a>
                                <div class="x-small text-muted mb-0.5">
                                    {{ $cartItem->quantity }}x {{ optional($cartItem->product)->discount_percentage > 0 ? $cartItem->product->formatted_final_price : ($cartItem->product->formatted_price ?? '') }}
                                </div>
                                <div class="fw-bold x-small text-blue-600" style="color: #2563EB;">
                                    Rp {{ number_format((float)$cartItem->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                            <form action="{{ route('cart.destroy', $cartItem->id) }}" method="POST" class="d-inline form-delete-nav-cart" onsubmit="return confirm('Hapus produk ini dari keranjang?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Hapus dari keranjang">
                                    <i class="bi bi-trash3" style="font-size: 0.95rem;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer Panel: Total Harga Sementara & Tombol "Lihat Keranjang Lengkap" -->
        <div class="offcanvas-footer sticky-bottom border-top bg-white shadow-md p-4" style="border-top: 1px solid #E2E8F0; box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.06); z-index: 10;">
            <div class="d-flex justify-content-between align-items-baseline mb-3">
                <div>
                    <span class="text-muted small fw-semibold d-block">Subtotal Sementara</span>
                    <span class="x-small text-muted">Belum termasuk diskon/opsi checkout</span>
                </div>
                <span class="h4 fw-bold mb-0 text-blue-600" style="color: #2563EB; font-size: 1.4rem;">
                    Rp {{ number_format($navCartTotal, 0, ',', '.') }}
                </span>
            </div>

            <!-- Tombol Utama: Tautan ke Halaman /cart -->
            <a href="{{ route('cart.index') }}" class="btn w-100 py-3 fw-bold text-white d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm bg-blue-600 hover:bg-blue-700 btn-primary border-0" style="font-size: 1rem; background-color: #2563EB; transition: background-color 0.2s ease;">
                <i class="bi bi-cart-check fs-5"></i>
                <span>Lihat Keranjang Lengkap</span>
                <i class="bi bi-arrow-right fs-5"></i>
            </a>

            <div class="text-center mt-2">
                <button type="button" class="btn btn-link text-muted x-small text-decoration-none py-1" data-bs-dismiss="offcanvas">
                    <i class="bi bi-arrow-left me-1"></i> Lanjut Belanja
                </button>
            </div>
        </div>
    </div>
@endif
