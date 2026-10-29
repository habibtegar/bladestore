<!-- Header Panel -->
<div class="offcanvas-header border-bottom py-3 px-3.5 bg-white d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2.5">
        <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-xs" style="width: 36px; height: 36px; background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%); font-size: 1.05rem;">
            <i class="bi bi-bag-check-fill"></i>
        </div>
        <div>
            <h5 class="offcanvas-title fw-bold text-dark mb-0 fs-6" id="cartOffcanvasLabel">Keranjang Belanja</h5>
            <span class="text-muted x-small">
                {{ $navCartItems->sum('quantity') }} produk terpilih
            </span>
        </div>
    </div>
    <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
</div>

@if($navCartItems->isEmpty())
    <!-- Empty Cart State -->
    <div class="offcanvas-body p-4 d-flex flex-column align-items-center justify-content-center text-center">
        <div class="p-4 rounded-circle mb-3 d-inline-flex align-items-center justify-content-center" style="background-color: #EFF6FF; color: #0866FF; width: 72px; height: 72px;">
            <i class="bi bi-cart-x fs-1"></i>
        </div>
        <h6 class="fw-bold text-dark mb-1">Keranjang Masih Kosong</h6>
        <p class="text-muted small mb-4 px-2" style="max-width: 280px;">
            Yuk, jelajahi berbagai produk pilihan terbaik di BladeStore dan tambahkan ke keranjang!
        </p>
        <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-sm px-4 py-2 text-white fw-bold rounded-3 shadow-sm btn-primary bg-blue-600 border-0" data-bs-dismiss="offcanvas">
            <i class="bi bi-shop me-1.5"></i> Mulai Belanja Sekarang
        </a>
    </div>
@else
    <!-- Mini Cart Body: Daftar produk ringkas -->
    <div class="d-flex flex-column h-100 overflow-hidden m-0 p-0">
        
        <!-- Summary Sub-Header (Simple item count, no redundant profile clutter) -->
        <div class="px-3.5 py-2 border-bottom bg-light d-flex justify-content-between align-items-center">
            <span class="x-small text-muted fw-semibold">
                <i class="bi bi-bag-check me-1 text-primary"></i> Total Item: <strong class="text-dark">{{ $navCartItems->sum('quantity') }} unit</strong>
            </span>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle x-small rounded-pill">
                {{ $navCartItems->count() }} Produk
            </span>
        </div>

        <div class="offcanvas-body p-3 flex-grow-1 overflow-y-auto">
            <!-- List Item Keranjang Singkat -->
            <div class="d-flex flex-column gap-2.5">
                @foreach($navCartItems as $cartItem)
                    <div class="p-2.5 rounded-3 border bg-white d-flex align-items-center justify-content-between gap-2.5 shadow-xs transition hover:border-primary" style="border-color: #E2E8F0 !important;">
                        <div class="d-flex align-items-center gap-2.5 overflow-hidden">
                            <!-- Product Image (Contain, no distorted crop) -->
                            <div style="width: 52px; height: 52px; border-radius: 8px; overflow: hidden; background-color: #F8FAFC; border: 1px solid #E2E8F0; padding: 4px; flex-shrink: 0;" class="d-flex align-items-center justify-content-center">
                                <img src="{{ optional($cartItem->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                     alt="{{ $cartItem->product->name ?? 'Produk' }}"
                                     class="w-100 h-100 object-fit-contain"
                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                            </div>
                            <div class="text-truncate">
                                <a href="{{ route('products.show', $cartItem->product->slug ?? '#') }}" class="fw-semibold text-dark text-decoration-none x-small text-truncate d-block mb-0.5" style="line-height: 1.3;" title="{{ $cartItem->product->name ?? 'Produk' }}">
                                    {{ $cartItem->product->name ?? 'Produk' }}
                                </a>
                                <div class="x-small text-muted mb-0.5">
                                    <span class="badge bg-light text-secondary border x-small py-0 px-1.5 me-1">{{ $cartItem->product->category ?? 'Umum' }}</span>
                                    <span>{{ $cartItem->quantity }}x</span>
                                    @if(optional($cartItem->product)->discount_percentage > 0)
                                        <span class="text-primary fw-semibold">{{ $cartItem->product->formatted_final_price }}</span>
                                    @else
                                        <span>{{ $cartItem->product->formatted_price ?? '' }}</span>
                                    @endif
                                </div>
                                <div class="fw-bold x-small text-blue-600" style="color: #0866FF;">
                                    Subtotal: Rp {{ number_format((float)$cartItem->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delete Button (AJAX Form) -->
                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                            <form action="{{ route('cart.destroy', $cartItem->id) }}" method="POST" class="d-inline form-delete-nav-cart" onsubmit="return confirm('Hapus produk ini dari keranjang?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger p-1 rounded-2 border-0 hover:bg-danger-subtle" title="Hapus produk">
                                    <i class="bi bi-trash3" style="font-size: 0.95rem;"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer Panel: Total Harga Sementara & Tombol CTA "Lihat Keranjang Lengkap" -->
        <div class="offcanvas-footer sticky-bottom border-top bg-white p-3.5 shadow-md" style="border-top: 1px solid #E2E8F0; box-shadow: 0 -4px 16px rgba(15, 23, 42, 0.06); z-index: 10;">
            <div class="d-flex justify-content-between align-items-baseline mb-3">
                <div>
                    <span class="text-muted small fw-semibold d-block">Subtotal Sementara</span>
                    <span class="x-small text-success fw-semibold"><i class="bi bi-truck me-1"></i>Bebas Ongkir</span>
                </div>
                <span class="h4 fw-bold mb-0 text-blue-600" style="color: #0866FF; font-size: 1.35rem;">
                    Rp {{ number_format($navCartTotal, 0, ',', '.') }}
                </span>
            </div>

            <!-- Tombol Utama: Tautan ke Halaman /cart -->
            <a href="{{ route('cart.index') }}" class="btn w-100 py-2.5 fw-bold text-white d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm btn-primary bg-blue-600 border-0" style="background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%); font-size: 0.95rem;">
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
