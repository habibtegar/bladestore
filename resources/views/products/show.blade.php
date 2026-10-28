@extends('layouts.app')

@section('title', $product->name . ' — LaraShop Marketplace')

@section('content')

<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="mb-3 mb-md-4">
    <ol class="breadcrumb small bg-white px-3 py-2 rounded-3 border shadow-sm">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Beranda</a></li>
        @if($product->category)
            <li class="breadcrumb-item"><a href="{{ route('products.index', ['category' => $product->category]) }}" class="text-decoration-none">{{ $product->category }}</a></li>
        @endif
        <li class="breadcrumb-item active text-dark fw-semibold text-truncate" aria-current="page" style="max-width: 320px;">
            {{ $product->name }}
        </li>
    </ol>
</nav>

@php
    $svgFallback = "data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20400%22%20width%3D%22400%22%20height%3D%22400%22%3E%3Crect%20fill%3D%22%23F1F5F9%22%20width%3D%22400%22%20height%3D%22400%22%2F%3E%3Crect%20x%3D%22150%22%20y%3D%22140%22%20width%3D%22100%22%20height%3D%2280%22%20rx%3D%2210%22%20fill%3D%22%23CBD5E1%22%2F%3E%3Ccircle%20cx%3D%22180%22%20cy%3D%22170%22%20r%3D%2210%22%20fill%3D%22%2394A3B8%22%2F%3E%3Cpath%20d%3D%22M165%20205l20-22%2015%2015%2022-25%2018%2032z%22%20fill%3D%22%2394A3B8%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%22260%22%20text-anchor%3D%22middle%22%20fill%3D%22%2394A3B8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2213%22%20font-weight%3D%22600%22%3ELaraShop%3C%2Ftext%3E%3C%2Fsvg%3E";
@endphp

<div class="row g-4 g-lg-5 mb-5">
    
    <!-- Left: Product Image & Trust Indicators -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 overflow-hidden bg-white mb-3 shadow-sm rounded-4">
            <div class="position-relative" style="aspect-ratio: 1 / 1; overflow: hidden; background-color: #F8FAFC;">
                <img src="{{ $product->image ?: $svgFallback }}"
                     alt="{{ $product->name }}"
                     class="w-100 h-100 object-fit-cover"
                     onerror="this.onerror=null; this.src='{{ $svgFallback }}';">
                
                <!-- Category Badge Top-Left -->
                <div class="position-absolute top-0 start-0 m-3 z-2">
                    <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2 rounded-pill small">
                        <i class="bi bi-tag me-1"></i> {{ $product->category ?? 'Umum' }}
                    </span>
                </div>

                <!-- Stock status & Discount Top-Right -->
                <div class="position-absolute top-0 end-0 m-3 z-2 d-flex flex-column align-items-end gap-1">
                    @if($product->discount_percentage > 0)
                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill small fw-bold shadow-sm">
                            -{{ $product->discount_percentage }}%
                        </span>
                    @endif
                    @if($product->stock > 0)
                        <span class="badge bg-success text-white px-3 py-2 rounded-pill small">
                            <i class="bi bi-check-circle-fill me-1"></i> Tersedia: {{ $product->stock }}
                        </span>
                    @else
                        <span class="badge bg-danger text-white px-3 py-2 rounded-pill small">
                            <i class="bi bi-x-circle-fill me-1"></i> Stok Habis
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3 Value Pillars -->
        <div class="row g-2">
            <div class="col-4">
                <div class="card border-0 p-3 text-center rounded-3 bg-white h-100 shadow-sm">
                    <i class="bi bi-truck text-primary fs-4 mb-1"></i>
                    <div class="fw-bold text-dark x-small">Tersedia COD</div>
                    <div class="text-muted x-small">Bayar di Tempat</div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 p-3 text-center rounded-3 bg-white h-100 shadow-sm">
                    <i class="bi bi-patch-check text-primary fs-4 mb-1"></i>
                    <div class="fw-bold text-dark x-small">100% Asli</div>
                    <div class="text-muted x-small">Lolos Kurasi</div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-0 p-3 text-center rounded-3 bg-white h-100 shadow-sm">
                    <i class="bi bi-shield-check text-primary fs-4 mb-1"></i>
                    <div class="fw-bold text-dark x-small">Bebas Biaya</div>
                    <div class="text-muted x-small">Transfer Bank</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Product Information & Purchase Form -->
    <div class="col-12 col-lg-6">
        <div class="card border-0 bg-white p-3 p-md-4 rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between">
            <div>
                <!-- Category & Verification -->
                <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 small fw-semibold">
                        Kategori: {{ $product->category ?? 'Umum' }}
                    </span>
                    <span class="badge bg-light text-secondary border small">
                        <i class="bi bi-check2-seal text-primary me-1"></i> Official LaraShop Store
                    </span>
                </div>

                <!-- Product Title -->
                <h1 class="h3 fw-bold text-dark mb-2 lh-sm">{{ $product->name }}</h1>

                <!-- Rating & Sold Summary -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="text-warning small">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <span class="fw-bold text-dark small">4.9</span>
                    <span class="text-muted x-small">&bull; Terjual 150+ unit</span>
                    <span class="text-muted x-small">&bull; 48 Rating Pembeli</span>
                </div>

                <!-- Price Box -->
                <div class="p-3 p-md-4 rounded-3 mb-4" style="background-color: var(--primary-subtle); border: 1px solid var(--primary-border);">
                    <div class="d-flex align-items-baseline gap-3 flex-wrap">
                        @if($product->discount_percentage > 0)
                            <span class="display-6 fw-bold text-primary">{{ $product->formatted_final_price }}</span>
                            <span class="text-muted text-decoration-line-through fs-6">
                                {{ $product->formatted_price }}
                            </span>
                            <span class="badge bg-danger text-white x-small">-{{ $product->discount_percentage }}%</span>
                        @else
                            <span class="display-6 fw-bold text-primary">{{ $product->formatted_price }}</span>
                        @endif
                    </div>
                    <div class="text-muted x-small mt-1">
                        Harga resmi terverifikasi &bull; Sudah termasuk jaminan pengemasan aman
                    </div>
                </div>

                <!-- Product Specifications Grid -->
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="p-2 border rounded-2 bg-light">
                            <span class="text-muted x-small d-block">Kategori:</span>
                            <strong class="text-dark small">{{ $product->category ?? 'Umum' }}</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded-2 bg-light">
                            <span class="text-muted x-small d-block">Kondisi:</span>
                            <strong class="text-dark small">Baru & Segel Asli</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded-2 bg-light">
                            <span class="text-muted x-small d-block">Stok Tersedia:</span>
                            <strong class="text-dark small">{{ $product->stock }} Unit</strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded-2 bg-light">
                            <span class="text-muted x-small d-block">Metode Bayar:</span>
                            <strong class="text-dark small">COD & Transfer Bank</strong>
                        </div>
                    </div>
                </div>

                <!-- Purchase Form -->
                @if($product->stock > 0)
                    <form action="{{ route('cart.store') }}" method="POST" class="mb-4 form-add-to-cart">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="mb-3">
                            <label for="quantity" class="form-label small fw-semibold text-dark">Jumlah Kuantitas:</label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="input-group" style="width: 140px;">
                                    <button class="btn btn-outline-secondary px-3" type="button" onclick="decrementQty()">-</button>
                                    <input type="number" id="quantity" name="quantity" class="form-control text-center bg-white fw-bold" value="1" min="1" max="{{ $product->stock }}">
                                    <button class="btn btn-outline-secondary px-3" type="button" onclick="incrementQty({{ $product->stock }})">+</button>
                                </div>
                                <span class="text-muted x-small">
                                    Batas maksimal stok: <strong>{{ $product->stock }}</strong> unit
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-sm-flex">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1 py-3 fw-bold">
                                <i class="bi bi-bag-plus fs-5"></i> Tambah ke Keranjang
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                Katalog Lainnya
                            </a>
                        </div>
                    </form>
                @else
                    <div class="alert alert-secondary rounded-3 mb-4 small">
                        <i class="bi bi-info-circle me-1"></i> Stok produk saat ini sedang habis. Silakan pilih produk lainnya di katalog marketplace kami.
                    </div>
                @endif

                <!-- Product Specifications Tabs -->
                <div class="border rounded-3 p-3 bg-light">
                    <ul class="nav nav-tabs border-bottom mb-3" id="productDetailTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active small py-2 px-3 fw-semibold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button" role="tab">
                                Deskripsi Produk
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link small py-2 px-3 fw-semibold" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping-pane" type="button" role="tab">
                                Pengiriman & COD
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link small py-2 px-3 fw-semibold" id="warranty-tab" data-bs-toggle="tab" data-bs-target="#warranty-pane" type="button" role="tab">
                                Jaminan Garansi
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content small text-muted" id="productDetailTabsContent">
                        <div class="tab-pane fade show active" id="desc-pane" role="tabpanel">
                            <p class="mb-0 text-dark" style="line-height: 1.6;">
                                {{ $product->description ?: 'Produk marketplace berkualitas tinggi yang telah lolos uji keaslian dan standar mutu LaraShop untuk memenuhi kebutuhan belanja online Anda.' }}
                            </p>
                        </div>
                        <div class="tab-pane fade" id="shipping-pane" role="tabpanel">
                            <p class="mb-1 text-dark"><strong>Cash on Delivery (COD):</strong> Bayar secara tunai kepada kurir pada saat pesanan sampai di alamat tujuan Anda.</p>
                            <p class="mb-0 text-dark"><strong>Transfer Bank Manual:</strong> Tersedia untuk rekening Bank BCA, Mandiri, BRI, dan BNI tanpa biaya admin.</p>
                        </div>
                        <div class="tab-pane fade" id="warranty-pane" role="tabpanel">
                            <p class="mb-0 text-dark">Jaminan kepuasan konsumen: Jika barang yang Anda terima cacat produksi atau tidak sesuai dengan spesifikasi, tim layanan pelanggan siap membantu proses tukar barang.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Related Products in Category -->
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <div class="mt-5 pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-0">Rekomendasi Produk Serupa</h4>
                <p class="text-muted small mb-0">Pilihan produk menarik lainnya di kategori {{ $product->category ?? 'Marketplace' }}</p>
            </div>
            <a href="{{ route('products.index', array_filter(['category' => $product->category])) }}" class="text-primary text-decoration-none small fw-semibold">
                Lihat Semua <i class="bi bi-chevron-right"></i>
            </a>
        </div>
        <div class="row g-2 g-md-3">
            @foreach($relatedProducts as $related)
                <div class="col-6 col-md-3">
                    <div class="card h-100 product-card d-flex flex-column">
                        <div class="product-img-box">
                            <a href="{{ route('products.show', $related->slug) }}" class="d-block w-100 h-100">
                                <img src="{{ $related->image ?: $svgFallback }}"
                                     class="product-img"
                                     alt="{{ $related->name }}"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ $svgFallback }}';">
                            </a>
                            <div class="position-absolute top-0 start-0 m-2 z-2">
                                <span class="badge-category">{{ $related->category ?? 'Umum' }}</span>
                            </div>
                            @if($related->discount_percentage > 0)
                                <div class="position-absolute top-0 end-0 m-2 z-2">
                                    <span class="badge-discount">-{{ $related->discount_percentage }}%</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body p-2 p-md-3 d-flex flex-column">
                            <h6 class="fw-bold mb-1 text-truncate" style="font-size: 0.85rem;">
                                <a href="{{ route('products.show', $related->slug) }}" class="text-decoration-none text-dark stretched-link">
                                    {{ $related->name }}
                                </a>
                            </h6>
                            <div class="text-primary fw-bold mt-auto" style="font-size: 0.95rem;">
                                @if($related->discount_percentage > 0)
                                    {{ $related->formatted_final_price }}
                                    <span class="text-muted text-decoration-line-through small fw-normal ms-1" style="font-size: 0.75rem;">
                                        {{ $related->formatted_price }}
                                    </span>
                                @else
                                    {{ $related->formatted_price }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@push('scripts')
<script>
    function incrementQty(max) {
        const input = document.getElementById('quantity');
        let current = parseInt(input.value) || 1;
        if (current < max) {
            input.value = current + 1;
        }
    }

    function decrementQty() {
        const input = document.getElementById('quantity');
        let current = parseInt(input.value) || 1;
        if (current > 1) {
            input.value = current - 1;
        }
    }
</script>
@endpush
@endsection
