@extends('layouts.app')

@section('title', 'Marketplace Multi-Kategori Terpercaya — LaraShop')

@push('styles')
<style>
    /* Modern Marketplace Full-Width Hero */
    .hero-fullwidth-wrapper {
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden;
    }

    .hero-banner-fullwidth {
        width: 100%;
        margin: 0 !important;
        padding: 0 !important;
        border-radius: 0 !important;
        border-top: none !important;
        border-bottom: 1px solid #D6E4FF;
        border-left: none !important;
        border-right: none !important;
        box-shadow: 0 4px 20px -2px rgba(8, 102, 255, 0.05);
    }

    .hero-slide-bg-1 {
        background: linear-gradient(135deg, #FFFFFF 0%, #F4F8FF 45%, #E6EFFF 100%);
    }

    .hero-slide-bg-2 {
        background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 50%, #DBEAFE 100%);
    }

    .hero-slide-wrapper {
        position: relative;
        z-index: 1;
        width: 100%;
    }

    /* Small Label Badge */
    .hero-pill-badge {
        background-color: var(--primary-subtle);
        color: var(--primary);
        border: 1px solid var(--primary-border);
        padding: 0.35rem 0.85rem;
        border-radius: 9999px;
        font-size: 0.775rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        letter-spacing: -0.01em;
    }

    /* Typography */
    .hero-title {
        font-size: clamp(1.65rem, 3.2vw, 2.35rem);
        font-weight: 800;
        line-height: 1.18;
        color: var(--dark-navy);
        letter-spacing: -0.03em;
    }

    .hero-desc {
        color: var(--slate-600);
        font-size: clamp(0.85rem, 1.2vw, 0.95rem);
        line-height: 1.55;
        max-width: 520px;
    }

    /* Action Buttons */
    .btn-hero-primary {
        padding: 0.6rem 1.4rem;
        font-size: 0.875rem;
        font-weight: 700;
        border-radius: 8px;
        box-shadow: 0 4px 14px rgba(8, 102, 255, 0.28);
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.2s ease;
    }

    .btn-hero-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(8, 102, 255, 0.35);
    }

    .btn-hero-secondary {
        padding: 0.6rem 1.35rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 8px;
        background-color: #FFFFFF;
        border-color: #CBD5E1;
        color: var(--slate-700);
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.2s ease;
    }

    .btn-hero-secondary:hover {
        background-color: var(--primary-subtle);
        border-color: var(--primary);
        color: var(--primary);
        transform: translateY(-1px);
    }

    .hero-trust-row {
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Hero 6-Category Grid (Slide 1) */
    .hero-category-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.85rem;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }

    @media (min-width: 768px) {
        .hero-category-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1.15rem;
        }
    }

    .hero-cat-card {
        background-color: #FFFFFF;
        border-radius: 0.75rem; /* rounded-xl (12px) */
        box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.06), 0 1px 2px -1px rgba(15, 23, 42, 0.04); /* shadow-sm */
        border: 1px solid rgba(226, 232, 240, 0.85);
        padding: 1rem; /* p-4 */
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.25s ease;
    }

    @media (max-width: 575.98px) {
        .hero-cat-card {
            padding: 0.75rem;
            border-radius: 0.75rem;
        }
    }

    .hero-cat-card:hover {
        transform: translateY(-4px); /* hover:-translate-y-1 */
        box-shadow: 0 10px 20px -3px rgba(8, 102, 255, 0.14), 0 4px 6px -2px rgba(15, 23, 42, 0.05); /* hover:shadow-md */
        border-color: var(--primary-border);
    }

    .hero-cat-img-wrap {
        width: 100%;
        aspect-ratio: 1 / 1; /* aspect-square */
        border-radius: 0.75rem; /* rounded-xl (12px) */
        overflow: hidden;
        background-color: #F8FAFC;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hero-cat-img {
        width: 100%;
        height: 100%;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border-radius: 0.75rem; /* rounded-xl (12px) */
        display: block;
        transition: transform 0.35s ease;
    }

    .hero-cat-card:hover .hero-cat-img {
        transform: scale(1.06);
    }

    .hero-cat-label {
        font-size: 0.875rem; /* text-sm (14px) */
        font-weight: 600; /* font-semibold */
        color: #1E293B;
        text-align: center;
        display: block;
        transition: color 0.2s ease;
        line-height: 1.3;
        margin-top: 0.85rem; /* mt-3 to mt-4 */
    }

    .hero-cat-card:hover .hero-cat-label {
        color: var(--primary);
    }

    @media (max-width: 575.98px) {
        .hero-cat-label {
            font-size: 0.775rem;
            margin-top: 0.65rem;
        }
    }

    /* Multi-Category Collage */
    .hero-collage-container {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .hero-collage-card {
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        overflow: hidden;
        padding: 0.4rem;
        display: flex;
        flex-direction: column;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }

    .hero-collage-card:hover {
        transform: translateY(-3px);
        border-color: var(--primary-border);
        box-shadow: 0 8px 18px rgba(8, 102, 255, 0.14);
    }

    .hero-collage-img-wrap {
        aspect-ratio: 1 / 1;
        border-radius: 8px;
        overflow: hidden;
        background-color: #F8FAFC;
        position: relative;
    }

    .hero-collage-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .hero-collage-card:hover .hero-collage-img {
        transform: scale(1.08);
    }

    .hero-collage-info {
        padding: 0.35rem 0.15rem 0.15rem 0.15rem;
        text-align: center;
    }

    .hero-collage-cat {
        font-size: 0.625rem;
        color: var(--slate-500);
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.04em;
        display: block;
        line-height: 1;
        margin-bottom: 2px;
    }

    .hero-collage-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--dark-navy);
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.2;
    }

    /* Minimalist Carousel Controls */
    .hero-carousel-btn {
        width: 44px;
        opacity: 0.6;
        transition: opacity 0.2s ease;
    }

    .hero-carousel-btn:hover {
        opacity: 1;
    }

    .hero-carousel-arrow {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #FFFFFF;
        color: var(--dark-navy);
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        border: 1px solid var(--border-color);
        transition: all 0.15s ease;
    }

    .hero-carousel-btn:hover .hero-carousel-arrow {
        background-color: var(--primary);
        color: #FFFFFF;
        border-color: var(--primary);
        transform: scale(1.08);
    }

    /* Minimalist Indicators */
    .carousel-indicators [data-bs-target] {
        width: 22px;
        height: 4px;
        border-radius: 9999px;
        border: none;
        background-color: #CBD5E1;
        opacity: 0.6;
        transition: all 0.25s ease;
    }

    .carousel-indicators .active {
        width: 34px;
        background-color: var(--primary);
        opacity: 1;
    }

    /* Solid CTA Add to Cart Button */
    .btn-add-cart-solid {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: #FFFFFF !important;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(8, 102, 255, 0.25);
    }

    .btn-add-cart-solid:hover {
        background-color: #0052CC !important;
        border-color: #0052CC !important;
        color: #FFFFFF !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(8, 102, 255, 0.38);
    }

    /* 4 Individual Service Trust Cards (CSS Grid) */
    .service-trust-grid {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (min-width: 576px) {
        .service-trust-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (min-width: 992px) {
        .service-trust-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.25rem;
        }
    }

    .service-trust-card {
        background-color: #FFFFFF;
        border-radius: 12px;
        padding: 1.15rem 1.25rem;
        border: 1px solid #F1F5F9;
        box-shadow: 0 2px 8px -2px rgba(15, 23, 42, 0.05);
        display: flex;
        align-items: center;
        gap: 0.9rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        height: 100%;
        text-align: left;
    }

    .service-trust-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px -4px rgba(15, 23, 42, 0.09);
        border-color: #E2E8F0;
    }

    .service-trust-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: var(--primary-subtle);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    .service-trust-card:hover .service-trust-icon {
        background-color: var(--primary);
        color: #FFFFFF;
        transform: scale(1.05);
    }

    /* ==========================================
       MODERN COMPACT SQUARE CATEGORY GRID (ECOMMERCE STYLE)
       ========================================== */
    .category-marketplace-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 0.5rem;
    }

    @media (min-width: 768px) {
        .category-marketplace-grid {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 0.65rem;
        }
    }

    .category-square-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 0.75rem 0.35rem 0.65rem;
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        text-decoration: none;
        aspect-ratio: 1 / 1;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.22s cubic-bezier(0.4, 0, 0.2, 1),
                    box-shadow 0.22s ease,
                    border-color 0.22s ease,
                    background-color 0.22s ease;
        position: relative;
        overflow: hidden;
    }

    .category-square-card:hover {
        transform: translateY(-3px);
        border-color: #EA580C;
        box-shadow: 0 8px 18px -2px rgba(234, 88, 12, 0.18), 0 2px 6px -1px rgba(0, 0, 0, 0.04);
        background-color: #FFFBF7;
        text-decoration: none;
    }

    .category-square-icon {
        font-size: 1.65rem;
        line-height: 1;
        color: var(--primary, #0866FF);
        margin-bottom: 0.35rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.22s ease;
    }

    .category-square-card:hover .category-square-icon {
        transform: scale(1.15);
        color: #EA580C;
    }

    .category-square-name {
        font-size: 0.775rem;
        font-weight: 500;
        line-height: 1.25;
        color: var(--slate-800, #1E293B);
        text-align: center;
        width: 100%;
        padding: 0 0.15rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.2s ease, font-weight 0.2s ease;
    }

    .category-square-card:hover .category-square-name {
        color: #EA580C;
        font-weight: 600;
    }

    /* Active State (Selected Category Filter) */
    .category-square-card.active {
        border-color: #EA580C;
        background: linear-gradient(180deg, #FFF7ED 0%, #FFEDD5 100%);
        box-shadow: 0 4px 12px rgba(234, 88, 12, 0.16);
    }

    .category-square-card.active .category-square-icon {
        color: #EA580C;
        transform: scale(1.1);
    }

    .category-square-card.active .category-square-name {
        color: #C2410C;
        font-weight: 700;
    }

    .category-square-active-dot {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 6px;
        height: 6px;
        background-color: #EA580C;
        border-radius: 50%;
    }

    @media (min-width: 992px) {
        .category-square-card {
            padding: 0.85rem 0.4rem 0.75rem;
            border-radius: 14px;
        }

        .category-square-icon {
            font-size: 1.75rem;
            margin-bottom: 0.45rem;
        }

        .category-square-name {
            font-size: 0.825rem;
        }
    }

    @media (max-width: 380px) {
        .category-marketplace-grid {
            gap: 0.375rem;
        }

        .category-square-card {
            padding: 0.5rem 0.2rem 0.45rem;
            border-radius: 10px;
        }

        .category-square-icon {
            font-size: 1.35rem;
            margin-bottom: 0.25rem;
        }

        .category-square-name {
            font-size: 0.7rem;
        }
    }
</style>
@endpush

@section('hero')
<!-- ==========================================
     1. MODERN MARKETPLACE HERO BANNER (FULL WIDTH)
     ========================================== -->
<div class="w-100 hero-fullwidth-wrapper position-relative">
    <div id="heroMarketplaceCarousel" class="carousel slide carousel-fade border-0 hero-banner-fullwidth position-relative" data-bs-ride="carousel" data-bs-interval="8000">
        
        <!-- Slide Indicators (Minimalist & Sleek) -->
        <div class="carousel-indicators mb-2 mb-md-3">
            <button type="button" data-bs-target="#heroMarketplaceCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroMarketplaceCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        </div>

        <div class="carousel-inner">
            
            <!-- SLIDE 1: PRIMARY MULTI-CATEGORY SHOWCASE -->
            <div class="carousel-item active">
                <div class="hero-slide-wrapper hero-slide-bg-1">
                    <div class="container py-4 py-lg-5" style="max-width: 1280px;">
                        <div class="row align-items-center g-4 g-lg-5">
                        
                        <!-- Left Column: Headline, Description & Action Buttons -->
                        <div class="col-12 col-lg-6">
                            <div class="hero-content pe-lg-2">
                                
                                <!-- Small Clean Label Badge -->
                                <div class="mb-2 mb-md-3">
                                    <span class="hero-pill-badge">
                                        <i class="bi bi-patch-check-fill text-primary"></i> Pilihan Produk Terbaik
                                    </span>
                                </div>

                                <!-- Main Headline -->
                                <h1 class="hero-title mb-2 mb-md-3">
                                    Semua Kebutuhan, <br class="d-none d-sm-inline">
                                    <span class="text-primary">Satu Tempat.</span>
                                </h1>

                                <!-- Short Description -->
                                <p class="hero-desc mb-3 mb-md-4">
                                    Temukan produk pilihan dari berbagai kategori dengan pengalaman belanja yang mudah, aman, dan nyaman.
                                </p>

                                <!-- Action Buttons -->
                                <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-3 mb-3 mb-md-4">
                                    <a href="#katalog-produk" class="btn btn-primary btn-hero-primary">
                                        <i class="bi bi-bag-check-fill"></i> Mulai Belanja
                                    </a>
                                    <a href="#kategori-marketplace" class="btn btn-outline-primary btn-hero-secondary">
                                        <i class="bi bi-grid-3x3-gap"></i> Jelajahi Kategori
                                    </a>
                                </div>

                                <!-- Discreet Trust Notes -->
                                <div class="hero-trust-row d-flex flex-wrap align-items-center gap-2 gap-sm-3 text-muted">
                                    <span><i class="bi bi-shield-check text-primary me-1"></i> 100% Produk Terverifikasi</span>
                                    <span class="d-none d-sm-inline opacity-50">&bull;</span>
                                    <span><i class="bi bi-truck text-primary me-1"></i> Layanan COD</span>
                                    <span class="d-none d-sm-inline opacity-50">&bull;</span>
                                    <span><i class="bi bi-credit-card text-primary me-1"></i> Bebas Biaya Admin</span>
                                </div>

                            </div>
                        </div>

                        <!-- Right Column: 6-Grid Category Cards -->
                        <div class="col-12 col-lg-6">
                            <div class="hero-category-grid grid grid-cols-2 md:grid-cols-3 gap-3 gap-md-4">
                                <!-- 1. Elektronik -->
                                <a href="{{ route('products.index', ['category' => 'Elektronik']) }}#katalog-produk" class="hero-cat-card bg-white rounded-xl shadow-sm p-4 transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="hero-cat-img-wrap aspect-square w-full w-100 rounded-xl overflow-hidden bg-slate-50">
                                        <img src="https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=400&q=80"
                                             alt="Kategori Elektronik"
                                             class="hero-cat-img w-full w-100 h-full h-100 object-cover rounded-xl"
                                             loading="eager"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <span class="hero-cat-label text-center text-sm font-semibold text-dark mt-3 d-block">Elektronik</span>
                                </a>

                                <!-- 2. Fashion -->
                                <a href="{{ route('products.index', ['category' => 'Fashion']) }}#katalog-produk" class="hero-cat-card bg-white rounded-xl shadow-sm p-4 transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="hero-cat-img-wrap aspect-square w-full w-100 rounded-xl overflow-hidden bg-slate-50">
                                        <img src="https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=400&q=80"
                                             alt="Kategori Fashion"
                                             class="hero-cat-img w-full w-100 h-full h-100 object-cover rounded-xl"
                                             loading="eager"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <span class="hero-cat-label text-center text-sm font-semibold text-dark mt-3 d-block">Fashion</span>
                                </a>

                                <!-- 3. Kecantikan -->
                                <a href="{{ route('products.index', ['category' => 'Kecantikan']) }}#katalog-produk" class="hero-cat-card bg-white rounded-xl shadow-sm p-4 transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="hero-cat-img-wrap aspect-square w-full w-100 rounded-xl overflow-hidden bg-slate-50">
                                        <img src="https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=400&q=80"
                                             alt="Kategori Kecantikan"
                                             class="hero-cat-img w-full w-100 h-full h-100 object-cover rounded-xl"
                                             loading="eager"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <span class="hero-cat-label text-center text-sm font-semibold text-dark mt-3 d-block">Kecantikan</span>
                                </a>

                                <!-- 4. Rumah Tangga -->
                                <a href="{{ route('products.index', ['category' => 'Rumah Tangga']) }}#katalog-produk" class="hero-cat-card bg-white rounded-xl shadow-sm p-4 transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="hero-cat-img-wrap aspect-square w-full w-100 rounded-xl overflow-hidden bg-slate-50">
                                        <img src="https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=400&q=80"
                                             alt="Kategori Rumah Tangga"
                                             class="hero-cat-img w-full w-100 h-full h-100 object-cover rounded-xl"
                                             loading="eager"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <span class="hero-cat-label text-center text-sm font-semibold text-dark mt-3 d-block">Rumah Tangga</span>
                                </a>

                                <!-- 5. Makanan -->
                                <a href="{{ route('products.index', ['category' => 'Makanan Kemasan']) }}#katalog-produk" class="hero-cat-card bg-white rounded-xl shadow-sm p-4 transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="hero-cat-img-wrap aspect-square w-full w-100 rounded-xl overflow-hidden bg-slate-50">
                                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=400&q=80"
                                             alt="Kategori Makanan"
                                             class="hero-cat-img w-full w-100 h-full h-100 object-cover rounded-xl"
                                             loading="eager"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <span class="hero-cat-label text-center text-sm font-semibold text-dark mt-3 d-block">Makanan</span>
                                </a>

                                <!-- 6. Olahraga -->
                                <a href="{{ route('products.index', ['category' => 'Olahraga']) }}#katalog-produk" class="hero-cat-card bg-white rounded-xl shadow-sm p-4 transition hover:-translate-y-1 hover:shadow-md">
                                    <div class="hero-cat-img-wrap aspect-square w-full w-100 rounded-xl overflow-hidden bg-slate-50">
                                        <img src="https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=400&q=80"
                                             alt="Kategori Olahraga"
                                             class="hero-cat-img w-full w-100 h-full h-100 object-cover rounded-xl"
                                             loading="eager"
                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                    </div>
                                    <span class="hero-cat-label text-center text-sm font-semibold text-dark mt-3 d-block">Olahraga</span>
                                </a>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- SLIDE 2: COMPLEMENTARY CURATED SHOWCASE -->
            <div class="carousel-item">
                <div class="hero-slide-wrapper hero-slide-bg-2">
                    <div class="container py-4 py-lg-5" style="max-width: 1280px;">
                        <div class="row align-items-center g-4 g-lg-5">
                        
                        <!-- Left Column -->
                        <div class="col-12 col-lg-6">
                            <div class="hero-content pe-lg-2">
                                <div class="mb-2 mb-md-3">
                                    <span class="hero-pill-badge">
                                        <i class="bi bi-shield-lock-fill text-primary"></i> Belanja Fleksibel & Terpercaya
                                    </span>
                                </div>
                                <h2 class="hero-title mb-2 mb-md-3">
                                    Pilihan Lengkap, <br class="d-none d-sm-inline">
                                    <span class="text-primary">Transaksi Nyaman.</span>
                                </h2>
                                <p class="hero-desc mb-3 mb-md-4">
                                    Bebas memilih metode pembayaran Cash on Delivery (COD) atau transfer bank resmi tanpa biaya admin tambahan ke seluruh Indonesia.
                                </p>
                                <div class="d-flex flex-wrap align-items-center gap-2 gap-sm-3 mb-3 mb-md-4">
                                    <a href="#katalog-produk" class="btn btn-primary btn-hero-primary">
                                        <i class="bi bi-bag-check-fill"></i> Mulai Belanja
                                    </a>
                                    <a href="#kategori-marketplace" class="btn btn-outline-primary btn-hero-secondary">
                                        <i class="bi bi-grid-3x3-gap"></i> Jelajahi Kategori
                                    </a>
                                </div>
                                <div class="hero-trust-row d-flex flex-wrap align-items-center gap-2 gap-sm-3 text-muted">
                                    <span><i class="bi bi-bank text-primary me-1"></i> Bank BCA, Mandiri, BRI, BNI</span>
                                    <span class="d-none d-sm-inline opacity-50">&bull;</span>
                                    <span><i class="bi bi-box-seam text-primary me-1"></i> Pengemasan Aman</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Curated Collage 2 -->
                        <div class="col-12 col-lg-6">
                            <div class="hero-collage-container">
                                <div class="row g-2 g-sm-2.5">
                                    <!-- Smartwatch -->
                                    <div class="col-4">
                                        <a href="{{ route('products.index', ['category' => 'Elektronik']) }}#katalog-produk" class="hero-collage-card">
                                            <div class="hero-collage-img-wrap">
                                                <img src="https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&w=400&q=80"
                                                     alt="Smartwatch"
                                                     loading="lazy"
                                                     class="hero-collage-img"
                                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                            </div>
                                            <div class="hero-collage-info">
                                                <span class="hero-collage-cat">Elektronik</span>
                                                <span class="hero-collage-title">Smartwatch</span>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- Fashion Pria -->
                                    <div class="col-4">
                                        <a href="{{ route('products.index', ['category' => 'Fashion']) }}#katalog-produk" class="hero-collage-card">
                                            <div class="hero-collage-img-wrap">
                                                <img src="https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=400&q=80"
                                                     alt="Kemeja Casual"
                                                     loading="lazy"
                                                     class="hero-collage-img"
                                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                            </div>
                                            <div class="hero-collage-info">
                                                <span class="hero-collage-cat">Fashion</span>
                                                <span class="hero-collage-title">Kemeja</span>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- Olahraga -->
                                    <div class="col-4">
                                        <a href="{{ route('products.index', ['category' => 'Olahraga']) }}#katalog-produk" class="hero-collage-card">
                                            <div class="hero-collage-img-wrap">
                                                <img src="https://images.unsplash.com/photo-1517838277536-f5f99be501cd?auto=format&fit=crop&w=400&q=80"
                                                     alt="Fitness & Matras"
                                                     loading="lazy"
                                                     class="hero-collage-img"
                                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                            </div>
                                            <div class="hero-collage-info">
                                                <span class="hero-collage-cat">Olahraga</span>
                                                <span class="hero-collage-title">Fitness</span>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- Kopi & Makanan -->
                                    <div class="col-4">
                                        <a href="{{ route('products.index', ['category' => 'Makanan Kemasan']) }}#katalog-produk" class="hero-collage-card">
                                            <div class="hero-collage-img-wrap">
                                                <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=400&q=80"
                                                     alt="Kopi Arabika"
                                                     loading="lazy"
                                                     class="hero-collage-img"
                                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                            </div>
                                            <div class="hero-collage-info">
                                                <span class="hero-collage-cat">Makanan</span>
                                                <span class="hero-collage-title">Kopi Biji</span>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- Dapur & Pisau -->
                                    <div class="col-4">
                                        <a href="{{ route('products.index', ['category' => 'Rumah Tangga']) }}#katalog-produk" class="hero-collage-card">
                                            <div class="hero-collage-img-wrap">
                                                <img src="https://images.unsplash.com/photo-1593618998160-e34014e67546?auto=format&fit=crop&w=400&q=80"
                                                     alt="Pisau Dapur"
                                                     loading="lazy"
                                                     class="hero-collage-img"
                                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                            </div>
                                            <div class="hero-collage-info">
                                                <span class="hero-collage-cat">Rumah</span>
                                                <span class="hero-collage-title">Dapur</span>
                                            </div>
                                        </a>
                                    </div>
                                    <!-- Aksesori Otomotif -->
                                    <div class="col-4">
                                        <a href="{{ route('products.index', ['category' => 'Otomotif']) }}#katalog-produk" class="hero-collage-card">
                                            <div class="hero-collage-img-wrap">
                                                <img src="https://images.unsplash.com/photo-1619642751034-765dfdf7c58e?auto=format&fit=crop&w=400&q=80"
                                                     alt="Aksesori Mobil"
                                                     loading="lazy"
                                                     class="hero-collage-img"
                                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                            </div>
                                            <div class="hero-collage-info">
                                                <span class="hero-collage-cat">Otomotif</span>
                                                <span class="hero-collage-title">Aksesori</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Minimalist Carousel Controls (Subtle Chevrons) -->
        <button class="carousel-control-prev hero-carousel-btn" type="button" data-bs-target="#heroMarketplaceCarousel" data-bs-slide="prev" aria-label="Sebelumnya">
            <span class="hero-carousel-arrow">
                <i class="bi bi-chevron-left"></i>
            </span>
        </button>
        <button class="carousel-control-next hero-carousel-btn" type="button" data-bs-target="#heroMarketplaceCarousel" data-bs-slide="next" aria-label="Selanjutnya">
            <span class="hero-carousel-arrow">
                <i class="bi bi-chevron-right"></i>
            </span>
        </button>
    </div>
</div>
@endsection

@section('content')
<!-- ==========================================
     2. STREAMLINED VALUE TRUST GRID (4 INDIVIDUAL CARDS)
     ========================================== -->
<div class="service-trust-grid">
    <!-- Card 1: Bayar di Tempat (COD) -->
    <div class="service-trust-card">
        <div class="service-trust-icon">
            <i class="bi bi-truck"></i>
        </div>
        <div>
            <span class="fw-bold text-dark small d-block mb-0.5" style="font-size: 0.85rem; line-height: 1.25;">Bayar di Tempat (COD)</span>
            <span class="text-muted d-block" style="font-size: 0.725rem; line-height: 1.25;">Bayar aman saat paket tiba</span>
        </div>
    </div>

    <!-- Card 2: Transfer Bebas Admin -->
    <div class="service-trust-card">
        <div class="service-trust-icon">
            <i class="bi bi-bank"></i>
        </div>
        <div>
            <span class="fw-bold text-dark small d-block mb-0.5" style="font-size: 0.85rem; line-height: 1.25;">Transfer Bebas Admin</span>
            <span class="text-muted d-block" style="font-size: 0.725rem; line-height: 1.25;">BCA, Mandiri, BRI, BNI</span>
        </div>
    </div>

    <!-- Card 3: 100% Produk Asli -->
    <div class="service-trust-card">
        <div class="service-trust-icon">
            <i class="bi bi-patch-check"></i>
        </div>
        <div>
            <span class="fw-bold text-dark small d-block mb-0.5" style="font-size: 0.85rem; line-height: 1.25;">100% Produk Asli</span>
            <span class="text-muted d-block" style="font-size: 0.725rem; line-height: 1.25;">Lolos kurasi mutu terjamin</span>
        </div>
    </div>

    <!-- Card 4: CS WhatsApp -->
    <div class="service-trust-card">
        <div class="service-trust-icon">
            <i class="bi bi-headset"></i>
        </div>
        <div>
            <span class="fw-bold text-dark small d-block mb-0.5" style="font-size: 0.85rem; line-height: 1.25;">CS WhatsApp</span>
            <span class="text-muted d-block" style="font-size: 0.725rem; line-height: 1.25;">Bantuan responsif setiap hari</span>
        </div>
    </div>
</div>

<!-- ==========================================
     3. KATEGORI MARKETPLACE PILIHAN
     ========================================== -->
<section id="kategori-marketplace" class="mb-3 mb-md-4">
    <div class="d-flex justify-content-between align-items-center mb-2.5 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-0" style="font-size: 1.05rem;">Kategori Pilihan</h5>
            <p class="text-muted x-small mb-0">Jelajahi berbagai pilihan kategori kebutuhan Anda</p>
        </div>
        @if($category ?? false)
            <a href="{{ route('products.index', array_filter(['search' => $search, 'sort' => $sort])) }}#katalog-produk" class="badge bg-light text-secondary border text-decoration-none py-1.5 px-2.5 rounded-pill x-small">
                <i class="bi bi-x-circle text-danger me-1"></i> Reset: {{ $category }}
            </a>
        @endif
    </div>

    <!-- 8 Kategori Marketplace Grid (Modern Compact Square) -->
    <div class="category-marketplace-grid">
        
        <!-- 1. Elektronik -->
        <a href="{{ route('products.index', array_filter(['category' => 'Elektronik', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Elektronik' ? 'active' : '' }}"
           title="Kategori Elektronik">
            @if(($category ?? '') === 'Elektronik')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-laptop"></i>
            </div>
            <span class="category-square-name">Elektronik</span>
        </a>

        <!-- 2. Fashion -->
        <a href="{{ route('products.index', array_filter(['category' => 'Fashion', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Fashion' ? 'active' : '' }}"
           title="Kategori Fashion">
            @if(($category ?? '') === 'Fashion')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-bag"></i>
            </div>
            <span class="category-square-name">Fashion</span>
        </a>

        <!-- 3. Kecantikan -->
        <a href="{{ route('products.index', array_filter(['category' => 'Kecantikan', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Kecantikan' ? 'active' : '' }}"
           title="Kategori Kecantikan">
            @if(($category ?? '') === 'Kecantikan')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-stars"></i>
            </div>
            <span class="category-square-name">Kecantikan</span>
        </a>

        <!-- 4. Rumah Tangga -->
        <a href="{{ route('products.index', array_filter(['category' => 'Rumah Tangga', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Rumah Tangga' ? 'active' : '' }}"
           title="Kategori Rumah Tangga">
            @if(($category ?? '') === 'Rumah Tangga')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-house-door"></i>
            </div>
            <span class="category-square-name">
                <span class="d-none d-sm-inline">Rumah Tangga</span>
                <span class="d-inline d-sm-none">Rumah</span>
            </span>
        </a>

        <!-- 5. Makanan Kemasan -->
        <a href="{{ route('products.index', array_filter(['category' => 'Makanan Kemasan', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Makanan Kemasan' ? 'active' : '' }}"
           title="Kategori Makanan Kemasan">
            @if(($category ?? '') === 'Makanan Kemasan')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-cup-hot"></i>
            </div>
            <span class="category-square-name">
                <span class="d-none d-sm-inline">Makanan Kemasan</span>
                <span class="d-inline d-sm-none">Makanan</span>
            </span>
        </a>

        <!-- 6. Olahraga -->
        <a href="{{ route('products.index', array_filter(['category' => 'Olahraga', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Olahraga' ? 'active' : '' }}"
           title="Kategori Olahraga">
            @if(($category ?? '') === 'Olahraga')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-activity"></i>
            </div>
            <span class="category-square-name">Olahraga</span>
        </a>

        <!-- 7. Aksesori -->
        <a href="{{ route('products.index', array_filter(['category' => 'Aksesori', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Aksesori' ? 'active' : '' }}"
           title="Kategori Aksesori">
            @if(($category ?? '') === 'Aksesori')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-smartwatch"></i>
            </div>
            <span class="category-square-name">Aksesori</span>
        </a>

        <!-- 8. Otomotif -->
        <a href="{{ route('products.index', array_filter(['category' => 'Otomotif', 'search' => $search, 'sort' => $sort])) }}#katalog-produk"
           class="category-square-card {{ ($category ?? '') === 'Otomotif' ? 'active' : '' }}"
           title="Kategori Otomotif">
            @if(($category ?? '') === 'Otomotif')
                <span class="category-square-active-dot"></span>
            @endif
            <div class="category-square-icon">
                <i class="bi bi-car-front"></i>
            </div>
            <span class="category-square-name">Otomotif</span>
        </a>

    </div>
</section>

<!-- ==========================================
     4. KATALOG PRODUK & CONTROL TOOLBAR
     ========================================== -->
<section id="katalog-produk" class="mb-5">
    
    <!-- Control Toolbar Card -->
    <div class="card border-0 p-3 mb-4 rounded-3 shadow-sm bg-white">
        <div class="row g-3 align-items-center justify-content-between">
            
            <!-- Counter & Filter Information -->
            <div class="col-12 col-md-5">
                <div class="d-flex align-items-center gap-2">
                    <h5 class="fw-bold text-dark mb-0">Katalog Produk</h5>
                    <span class="badge bg-light text-secondary border small">
                        {{ $products->total() }} Produk
                    </span>
                </div>
                @if($search || ($category ?? false) || ($minPrice ?? false) || ($maxPrice ?? false))
                    <div class="d-flex align-items-center flex-wrap gap-1 mt-2">
                        <span class="text-muted x-small">Filter Aktif:</span>
                        @if($search)
                            <span class="badge bg-light text-dark border">
                                "{{ $search }}"
                                <a href="{{ route('products.index', array_filter(['sort' => $sort, 'category' => $category])) }}#katalog-produk" class="text-danger ms-1 text-decoration-none">&times;</a>
                            </span>
                        @endif
                        @if($category ?? false)
                            <span class="badge bg-light text-dark border">
                                {{ $category }}
                                <a href="{{ route('products.index', array_filter(['sort' => $sort, 'search' => $search])) }}#katalog-produk" class="text-danger ms-1 text-decoration-none">&times;</a>
                            </span>
                        @endif
                        <a href="{{ route('products.index') }}#katalog-produk" class="text-danger x-small ms-1 text-decoration-none fw-semibold">
                            Reset Semua Filter
                        </a>
                    </div>
                @endif
            </div>

            <!-- Sorting & Search Form -->
            <div class="col-12 col-md-7">
                <form action="{{ route('products.index') }}#katalog-produk" method="GET" class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-md-end gap-2">
                    @if($category ?? false)
                        <input type="hidden" name="category" value="{{ $category }}">
                    @endif

                    <!-- Search Input in toolbar -->
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Filter nama produk..." value="{{ $search ?? '' }}">
                    </div>

                    <!-- Sort Select -->
                    <div class="d-flex align-items-center gap-2 text-nowrap">
                        <label class="text-muted small fw-semibold d-none d-lg-inline">Urutkan:</label>
                        <select name="sort" class="form-select form-select-sm" style="min-width: 140px;" onchange="this.form.submit()">
                            <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="popular" {{ ($sort ?? '') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary px-3">
                            Terapkan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Product Grid (2 columns on mobile, 3 on tablet, 4 on desktop) -->
    <div class="row g-2 g-md-3 g-lg-4">
        @php
            $svgFallback = "data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20400%22%20width%3D%22400%22%20height%3D%22400%22%3E%3Crect%20fill%3D%22%23F1F5F9%22%20width%3D%22400%22%20height%3D%22400%22%2F%3E%3Crect%20x%3D%22150%22%20y%3D%22140%22%20width%3D%22100%22%20height%3D%2280%22%20rx%3D%2210%22%20fill%3D%22%23CBD5E1%22%2F%3E%3Ccircle%20cx%3D%22180%22%20cy%3D%22170%22%20r%3D%2210%22%20fill%3D%22%2394A3B8%22%2F%3E%3Cpath%20d%3D%22M165%20205l20-22%2015%2015%2022-25%2018%2032z%22%20fill%3D%22%2394A3B8%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%22260%22%20text-anchor%3D%22middle%22%20fill%3D%22%2394A3B8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2213%22%20font-weight%3D%22600%22%3ELaraShop%3C%2Ftext%3E%3C%2Fsvg%3E";
        @endphp
        @forelse($products as $product)
            @php
                $soldCount = 40 + ($product->id * 7);
            @endphp
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card d-flex flex-column position-relative">
                    
                    <!-- 1:1 Image Box -->
                    <div class="product-img-box">
                        <a href="{{ route('products.show', $product->slug) }}" class="d-block w-100 h-100">
                            <img src="{{ $product->image ?: $svgFallback }}"
                                 class="product-img"
                                 alt="{{ $product->name }}"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='{{ $svgFallback }}';">
                        </a>

                        <!-- Category Badge Top-Left -->
                        <div class="position-absolute top-0 start-0 m-2 z-2">
                            <span class="badge-category">
                                {{ $product->category ?? 'Umum' }}
                            </span>
                        </div>

                        <!-- Discount Badge Top-Right -->
                        <div class="position-absolute top-0 end-0 m-2 z-2">
                            @if($product->discount_percentage > 0)
                                <span class="badge-discount">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            @elseif($product->stock <= 5 && $product->stock > 0)
                                <span class="badge bg-warning text-dark x-small px-1.5 py-0.5 fw-bold rounded-1">
                                    Sisa {{ $product->stock }}
                                </span>
                            @elseif($product->stock <= 0)
                                <span class="badge bg-danger text-white x-small px-1.5 py-0.5 fw-bold rounded-1">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-2.5 p-md-3 d-flex flex-column">
                        
                        <!-- Rating & Sold Count -->
                        <div class="d-flex align-items-center gap-1 mb-1" style="height: 1.15rem;">
                            <span class="text-warning small" style="font-size: 0.725rem;">
                                <i class="bi bi-star-fill"></i>
                            </span>
                            <span class="fw-bold text-dark" style="font-size: 0.725rem;">4.9</span>
                            <span class="text-muted" style="font-size: 0.675rem;">&bull; {{ $soldCount }}+ terjual</span>
                        </div>

                        <!-- Product Title (2 lines clamp, fixed height) -->
                        <h6 class="fw-semibold mb-1" style="font-size: 0.8125rem; line-height: 1.25; height: 2.1rem; min-height: 2.1rem; max-height: 2.1rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark stretched-link">
                                {{ $product->name }}
                            </a>
                        </h6>

                        <!-- Stock Tag -->
                        <div class="mb-2" style="height: 1.15rem;">
                            @if($product->stock > 0)
                                <span class="text-success x-small fw-semibold">
                                    <i class="bi bi-check2-circle"></i> Tersedia: {{ $product->stock }}
                                </span>
                            @else
                                <span class="text-danger x-small fw-semibold">
                                    <i class="bi bi-x-circle"></i> Stok Habis
                                </span>
                            @endif
                        </div>

                        <!-- Price Section (Aligned with mt-auto) -->
                        <div class="mb-2 mt-auto">
                            <div class="fw-bold text-primary" style="font-size: 0.95rem; line-height: 1.2;">
                                @if($product->discount_percentage > 0)
                                    {{ $product->formatted_final_price }}
                                @else
                                    {{ $product->formatted_price }}
                                @endif
                            </div>
                            <div style="height: 1rem;">
                                @if($product->discount_percentage > 0)
                                    <span class="text-muted text-decoration-line-through" style="font-size: 0.7rem;">
                                        {{ $product->formatted_price }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Add To Cart Form -->
                        <div class="position-relative z-3 pt-1">
                            @if($product->stock > 0)
                                <form action="{{ route('cart.store') }}" method="POST" class="form-add-to-cart">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-primary btn-add-cart-solid btn-sm w-100 py-1.5 d-inline-flex align-items-center justify-content-center gap-1.5">
                                        <i class="bi bi-bag-plus-fill"></i>
                                        <span>+ Keranjang</span>
                                    </button>
                                </form>
                            @else
                                <button type="button" class="btn btn-secondary btn-sm w-100 py-1.5" style="font-size: 0.775rem;" disabled>
                                    Stok Habis
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 p-5 text-center rounded-3 bg-white shadow-sm">
                    <div class="mb-3">
                        <span class="p-3 bg-light rounded-circle d-inline-block text-muted">
                            <i class="bi bi-search fs-2"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Produk Tidak Ditemukan</h5>
                    <p class="text-muted small mb-3">Tidak ada produk marketplace yang sesuai dengan kriteria pencarian atau kategori Anda.</p>
                    <div>
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-sm px-4 py-2">
                            Lihat Semua Kategori Produk
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4 pt-2 d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

</section>

@endsection
