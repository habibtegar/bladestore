@extends('layouts.app')

@section('title', 'Katalog Produk Pilihan')

@push('styles')
<style>
    /* ==========================================
       1. HERO SECTION (CLEAN 2-COLUMN STATIC HERO)
       ========================================== */
    .hero-fullwidth-wrapper {
        width: 100%;
        margin: 0 !important;
        padding: 3rem 0 !important;
        overflow: hidden;
        position: relative;
        background: linear-gradient(135deg, #FFFFFF 0%, #F4F8FF 45%, #E8F1FF 100%);
        border-bottom: 1px solid #D6E4FF;
        display: flex;
        align-items: center;
        min-height: 480px;
    }

    @media (min-width: 992px) {
        .hero-fullwidth-wrapper {
            min-height: 480px;
            max-height: 540px;
            padding: 3.5rem 0 !important;
        }
    }

    @media (max-width: 991.98px) {
        .hero-fullwidth-wrapper {
            min-height: auto;
            max-height: none;
            padding: 2.5rem 0 2rem !important;
        }
    }

    /* Soft Ambient Glowing Orbs */
    .hero-ambient-orb-1 {
        position: absolute;
        top: -60px;
        right: 10%;
        width: 320px;
        height: 320px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(8, 102, 255, 0.08) 0%, rgba(8, 102, 255, 0) 70%);
        filter: blur(20px);
        pointer-events: none;
    }

    .hero-ambient-orb-2 {
        position: absolute;
        bottom: -60px;
        left: 5%;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.06) 0%, rgba(59, 130, 246, 0) 70%);
        filter: blur(20px);
        pointer-events: none;
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

    /* Hero Typography */
    .hero-title {
        font-size: clamp(1.9rem, 3.2vw, 2.65rem);
        font-weight: 800;
        line-height: 1.16;
        color: var(--dark-navy, #0F172A);
        letter-spacing: -0.03em;
        margin-bottom: 1.15rem !important;
    }

    .hero-desc {
        color: var(--slate-600, #475569);
        font-size: clamp(0.9rem, 1.15vw, 1rem);
        line-height: 1.62;
        max-width: 520px;
        margin-bottom: 1.5rem !important;
    }

    /* Hero Action Buttons */
    .btn-hero-primary {
        padding: 0.65rem 1.45rem;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 10px;
        background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%);
        border: none;
        color: #FFFFFF !important;
        box-shadow: 0 4px 14px rgba(8, 102, 255, 0.28);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
    }

    .btn-hero-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(8, 102, 255, 0.38);
        color: #FFFFFF !important;
    }

    .btn-hero-secondary {
        padding: 0.65rem 1.4rem;
        font-size: 0.9rem;
        font-weight: 600;
        border-radius: 10px;
        background-color: #FFFFFF;
        border: 1px solid #CBD5E1;
        color: var(--slate-700, #334155) !important;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
    }

    .btn-hero-secondary:hover {
        background-color: var(--primary-subtle);
        border-color: var(--primary);
        color: var(--primary) !important;
        transform: translateY(-2px);
    }

    .hero-trust-row {
        font-size: 0.775rem;
        font-weight: 500;
    }

    /* Hero Static Image Showcase (35-40% Area, KantinSkanic Style) */
    .hero-image-showcase {
        max-width: 380px;
        width: 100%;
        position: relative;
        margin: 0 auto;
    }

    @media (min-width: 992px) {
        .hero-image-showcase {
            max-width: 390px;
            margin-left: auto;
            margin-right: 0;
        }
    }

    .hero-image-card {
        position: relative;
        background: #FFFFFF;
        border-radius: 20px;
        padding: 0.65rem;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow: 0 20px 40px -15px rgba(8, 102, 255, 0.12),
                    0 8px 16px -8px rgba(15, 23, 42, 0.05);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
        overflow: hidden;
    }

    .hero-image-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 50px -15px rgba(8, 102, 255, 0.18),
                    0 10px 20px -8px rgba(15, 23, 42, 0.07);
    }

    .hero-static-img {
        width: 100%;
        height: 310px;
        object-fit: cover;
        object-position: center;
        border-radius: 14px;
        display: block;
        transition: transform 0.4s ease;
    }

    .hero-image-card:hover .hero-static-img {
        transform: scale(1.03);
    }

    @media (max-width: 575.98px) {
        .hero-static-img {
            height: 230px;
        }
    }

    /* Floating Polish Badges (KantinSkanic Inspiration) */
    .hero-floating-badge {
        position: absolute;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(226, 232, 240, 0.9);
        border-radius: 12px;
        padding: 0.45rem 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1),
                    0 4px 6px -2px rgba(15, 23, 42, 0.05);
        z-index: 2;
        animation: floatSlow 4s ease-in-out infinite alternate;
    }

    .hero-float-top-left {
        top: -12px;
        left: -12px;
    }

    .hero-float-bottom-right {
        bottom: -12px;
        right: -12px;
        animation-delay: 2s;
    }

    @media (max-width: 767.98px) {
        .hero-float-top-left {
            top: -8px;
            left: -4px;
        }
        .hero-float-bottom-right {
            bottom: -8px;
            right: -4px;
        }
    }

    .hero-float-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .hero-float-title {
        font-size: 0.775rem;
        font-weight: 700;
        color: #0F172A;
        line-height: 1.2;
    }

    .hero-float-sub {
        font-size: 0.675rem;
        color: #64748B;
        line-height: 1.2;
    }

    @keyframes floatSlow {
        0% { transform: translateY(0); }
        100% { transform: translateY(-5px); }
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
        margin-top: 0.5rem;
        margin-bottom: 2.75rem;
    }

    @media (min-width: 576px) {
        .service-trust-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.25rem;
            margin-bottom: 3.25rem;
        }
    }

    @media (min-width: 992px) {
        .service-trust-grid {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.5rem;
            margin-bottom: 3.75rem;
        }
    }

    .service-trust-card {
        background-color: #FFFFFF;
        border-radius: 14px;
        padding: 1.2rem 1.25rem;
        border: 1px solid #E2E8F0;
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
        border-color: #CBD5E1;
    }

    .service-trust-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: var(--primary-subtle);
        color: var(--primary);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
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
        gap: 0.85rem;
    }

    @media (min-width: 768px) {
        .category-marketplace-grid {
            grid-template-columns: repeat(8, minmax(0, 1fr));
            gap: 1rem;
        }
    }

    @media (min-width: 1200px) {
        .category-marketplace-grid {
            gap: 1.15rem;
        }
    }

    .category-square-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 1.05rem 0.5rem 0.95rem;
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
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
        border-color: #0866FF;
        box-shadow: 0 8px 18px -2px rgba(8, 102, 255, 0.16), 0 2px 6px -1px rgba(0, 0, 0, 0.04);
        background-color: #F8FAFF;
        text-decoration: none;
    }

    .category-square-icon {
        font-size: 1.75rem;
        line-height: 1;
        color: var(--primary, #0866FF);
        margin-bottom: 0.45rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.22s ease;
    }

    .category-square-card:hover .category-square-icon {
        transform: scale(1.15);
        color: #0866FF;
    }

    .category-square-name {
        font-size: 0.8rem;
        font-weight: 500;
        line-height: 1.25;
        color: var(--slate-800, #1E293B);
        text-align: center;
        width: 100%;
        padding: 0 0.2rem;
        white-space: normal;
        word-break: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 1.95rem;
        min-height: 1.95rem;
        transition: color 0.2s ease, font-weight 0.2s ease;
    }

    .category-square-card:hover .category-square-name {
        color: #0866FF;
        font-weight: 600;
    }

    /* Active State (Selected Category Filter) */
    .category-square-card.active {
        border-color: #0866FF;
        background: linear-gradient(180deg, #EFF6FF 0%, #DBEAFE 100%);
        box-shadow: 0 4px 12px rgba(8, 102, 255, 0.16);
    }

    .category-square-card.active .category-square-icon {
        color: #0866FF;
        transform: scale(1.1);
    }

    .category-square-card.active .category-square-name {
        color: #1D4ED8;
        font-weight: 700;
    }

    .category-square-active-dot {
        position: absolute;
        top: 6px;
        right: 6px;
        width: 6px;
        height: 6px;
        background-color: #0866FF;
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
            height: 1.7rem;
            min-height: 1.7rem;
        }
    }

    /* ==========================================
       PRODUCT CARD & CATALOG STYLING
       ========================================== */
    .product-card {
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        background-color: #FFFFFF;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                    box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1),
                    border-color 0.25s ease;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-4px);
        border-color: #93C5FD !important;
        box-shadow: 0 12px 24px -4px rgba(8, 102, 255, 0.12), 0 4px 6px -2px rgba(15, 23, 42, 0.04) !important;
    }

    .product-img-box {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1 !important;
        background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px 11px 0 0;
    }

    .product-img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
        object-position: center !important;
        padding: 0.65rem;
        transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        display: block;
    }

    .product-card:hover .product-img {
        transform: scale(1.06);
    }

    /* Wishlist Heart Button on Card */
    .btn-product-wishlist {
        position: absolute;
        top: 8px;
        right: 8px;
        z-index: 4;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(4px);
        border: 1px solid rgba(226, 232, 240, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748B;
        font-size: 0.925rem;
        box-shadow: 0 2px 5px rgba(15, 23, 42, 0.08);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        cursor: pointer;
        padding: 0;
    }

    .btn-product-wishlist:hover {
        color: #EF4444;
        background-color: #FFFFFF;
        transform: scale(1.12);
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
    }

    .btn-product-wishlist.active {
        color: #EF4444 !important;
        background-color: #FFFFFF !important;
        border-color: #FCA5A5 !important;
    }

    .btn-product-wishlist.active i {
        color: #EF4444 !important;
        animation: heartPulse 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    @keyframes heartPulse {
        0% { transform: scale(0.8); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }

    /* Product Title 2-Line Fixed Height */
    .product-card-title {
        font-size: 0.84rem;
        font-weight: 600;
        line-height: 1.3;
        height: 2.2rem;
        min-height: 2.2rem;
        max-height: 2.2rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        color: #0F172A;
        transition: color 0.15s ease;
    }

    .product-card:hover .product-card-title a {
        color: #0866FF !important;
    }

    /* Price Section */
    .product-card-price {
        font-size: 0.975rem;
        font-weight: 700;
        line-height: 1.2;
        color: #0866FF;
    }

    /* Compact Add-to-Cart Button */
    .btn-add-cart-compact {
        font-size: 0.8125rem;
        height: 34px;
        background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%);
        border: none;
        color: #FFFFFF;
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 2px 6px rgba(8, 102, 255, 0.22);
    }

    .btn-add-cart-compact:hover {
        background: linear-gradient(135deg, #0756D6 0%, #173FB5 100%);
        color: #FFFFFF;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(8, 102, 255, 0.32);
    }

    .btn-add-cart-compact:active {
        transform: translateY(0);
    }
</style>
@endpush

@section('hero')
<!-- ==========================================
     1. MODERN MARKETPLACE HERO (STATIC 2-COLUMN)
     ========================================== -->
<div class="w-100 hero-fullwidth-wrapper">
    <!-- Ambient Background Orbs -->
    <div class="hero-ambient-orb-1"></div>
    <div class="hero-ambient-orb-2"></div>

    <div class="container position-relative z-1" style="max-width: 1240px;">
        <div class="row align-items-center g-4 g-lg-5">
            
            <!-- Left Column: Headline, Description & Action Buttons (~60-65% area) -->
            <div class="col-12 col-lg-7">
                <div class="hero-content pe-lg-3">
                    
                    <!-- Small Clean Label Badge -->
                    <div class="mb-3">
                        <span class="hero-pill-badge">
                            <i class="bi bi-patch-check-fill text-primary"></i> Marketplace Pilihan Terpercaya
                        </span>
                    </div>

                    <!-- Main Headline (Max 2 lines, line-height 1.1-1.2) -->
                    <h1 class="hero-title mb-3">
                        Belanja Cerdas & Mudah <br class="d-none d-sm-inline">
                        <span class="text-primary">dalam Satu Platform.</span>
                    </h1>

                    <!-- Short Description (Line-height 1.5-1.7) -->
                    <p class="hero-desc mb-4">
                        Temukan ragam produk pilihan terbaik dengan jaminan mutu terverifikasi, kemudahan bayar di tempat (COD), dan transaksi aman tanpa biaya admin tambahan ke seluruh Indonesia.
                    </p>

                    <!-- Action Buttons (Spacing 16-24px) -->
                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        <a href="#katalog-produk" class="btn btn-hero-primary">
                            <i class="bi bi-bag-check-fill"></i> Mulai Belanja
                        </a>
                        <a href="#kategori-marketplace" class="btn btn-hero-secondary">
                            <i class="bi bi-grid-3x3-gap"></i> Jelajahi Kategori
                        </a>
                    </div>

                    <!-- Discreet Trust Notes -->
                    <div class="hero-trust-row d-flex flex-wrap align-items-center gap-2 gap-sm-3 text-muted">
                        <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-shield-check text-primary"></i> 100% Produk Terverifikasi</span>
                        <span class="d-none d-sm-inline opacity-50">&bull;</span>
                        <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-truck text-primary"></i> Layanan COD</span>
                        <span class="d-none d-sm-inline opacity-50">&bull;</span>
                        <span class="d-inline-flex align-items-center gap-2"><i class="bi bi-credit-card text-primary"></i> Bebas Biaya Admin</span>
                    </div>

                </div>
            </div>

            <!-- Right Column: Single Static Hero Image (~35-40% area, KantinSkanic Style) -->
            <div class="col-12 col-lg-5 text-center text-lg-end">
                <div class="hero-image-showcase">
                    <!-- Clean Card Wrapper -->
                    <div class="hero-image-card">
                        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=700&q=80"
                             alt="Produk Pilihan BladeStore"
                             class="hero-static-img"
                             loading="eager"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=700&q=80';">
                    </div>

                    <!-- Floating Badge 1 (Top Left) -->
                    <div class="hero-floating-badge hero-float-top-left">
                        <div class="hero-float-icon bg-primary text-white">
                            <i class="bi bi-patch-check-fill"></i>
                        </div>
                        <div class="text-start">
                            <div class="hero-float-title">100% Original</div>
                            <div class="hero-float-sub">Produk Terkurasi</div>
                        </div>
                    </div>

                    <!-- Floating Badge 2 (Bottom Right) -->
                    <div class="hero-floating-badge hero-float-bottom-right">
                        <div class="hero-float-icon bg-success text-white">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div class="text-start">
                            <div class="hero-float-title">Garansi Resmi</div>
                            <div class="hero-float-sub">Aman & Terpercaya</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
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
            <span class="fw-bold text-dark small d-block mb-1" style="font-size: 0.875rem; line-height: 1.3;">Bayar di Tempat (COD)</span>
            <span class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.3;">Bayar aman saat paket tiba</span>
        </div>
    </div>

    <!-- Card 2: Transfer Bebas Admin -->
    <div class="service-trust-card">
        <div class="service-trust-icon">
            <i class="bi bi-bank"></i>
        </div>
        <div>
            <span class="fw-bold text-dark small d-block mb-1" style="font-size: 0.875rem; line-height: 1.3;">Transfer Bebas Admin</span>
            <span class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.3;">BCA, Mandiri, BRI, BNI</span>
        </div>
    </div>

    <!-- Card 3: 100% Produk Asli -->
    <div class="service-trust-card">
        <div class="service-trust-icon">
            <i class="bi bi-patch-check"></i>
        </div>
        <div>
            <span class="fw-bold text-dark small d-block mb-1" style="font-size: 0.875rem; line-height: 1.3;">100% Produk Asli</span>
            <span class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.3;">Lolos kurasi mutu terjamin</span>
        </div>
    </div>

    <!-- Card 4: CS WhatsApp -->
    <div class="service-trust-card">
        <div class="service-trust-icon">
            <i class="bi bi-headset"></i>
        </div>
        <div>
            <span class="fw-bold text-dark small d-block mb-1" style="font-size: 0.875rem; line-height: 1.3;">CS WhatsApp</span>
            <span class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.3;">Bantuan responsif setiap hari</span>
        </div>
    </div>
</div>

<!-- ==========================================
     3. KATEGORI MARKETPLACE PILIHAN
     ========================================== -->
<section id="kategori-marketplace" class="mb-5 pb-lg-2">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-1 flex-wrap gap-2">
        <div>
            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.15rem; letter-spacing: -0.01em;">Kategori Pilihan</h5>
            <p class="text-muted small mb-0">Jelajahi berbagai pilihan kategori kebutuhan Anda</p>
        </div>
        @if($category ?? false)
            <a href="{{ route('products.index', array_filter(['search' => $search, 'sort' => $sort])) }}#katalog-produk" class="badge bg-light text-secondary border text-decoration-none py-1.5 px-3 rounded-pill small">
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
            <span class="category-square-name">Rumah Tangga</span>
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
            <span class="category-square-name">Makanan Kemasan</span>
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
<section id="katalog-produk" class="mb-5 pt-1">
    
    <!-- Control Toolbar Card -->
    <div class="card border p-3 p-md-4 mb-4 pb-1 rounded-4 shadow-sm bg-white" style="border: 1px solid #E2E8F0 !important;">
        <form action="{{ route('products.index') }}#katalog-produk" method="GET" id="catalog-filter-form">
            <!-- Baris 1: Header Judul, Counter, Pencarian & Pengurutan Cepat -->
            <div class="row g-3 align-items-center justify-content-between">
                
                <!-- Counter & Filter Information -->
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="fw-bold text-dark mb-0 fs-6">Katalog Produk</h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 x-small fw-semibold rounded-pill">
                            <i class="bi bi-box-seam me-1"></i>{{ $products->total() }} Produk
                        </span>
                    </div>
                </div>

                <!-- Fast Search & Sorting & Toggle Filter Lanjutan -->
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center gap-2 flex-wrap flex-sm-nowrap justify-content-md-end">
                        
                        <!-- Search Input in toolbar -->
                        <div class="input-group input-group-sm flex-grow-1" style="max-width: 280px;">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control bg-light border-start-0" 
                                   placeholder="Cari produk..." 
                                   value="{{ $search ?? '' }}"
                                   aria-label="Cari produk">
                            @if($search)
                                <a href="{{ route('products.index', array_filter(['category' => $category, 'sort' => $sort, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'in_stock' => $inStockOnly ? '1' : null])) }}#katalog-produk" 
                                   class="input-group-text bg-light border-start-0 text-muted text-decoration-none" 
                                   title="Hapus kata kunci pencarian">
                                    <i class="bi bi-x-circle-fill text-secondary"></i>
                                </a>
                            @endif
                        </div>

                        <!-- Sort Select -->
                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                            <select name="sort" class="form-select form-select-sm" style="min-width: 135px;" onchange="this.form.submit()" aria-label="Urutkan Produk">
                                <option value="latest" {{ ($sort ?? 'latest') === 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="price_asc" {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_desc" {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="popular" {{ ($sort ?? '') === 'popular' ? 'selected' : '' }}>Terpopuler</option>
                            </select>
                        </div>

                        <!-- Button Toggle Filter Lanjutan -->
                        <button class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2 flex-shrink-0 {{ ($minPrice || $maxPrice || $inStockOnly || $category) ? 'active border-primary text-primary' : '' }}" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#advancedCatalogFilters" 
                                aria-expanded="{{ ($minPrice || $maxPrice || $inStockOnly) ? 'true' : 'false' }}" 
                                aria-controls="advancedCatalogFilters">
                            <i class="bi bi-sliders"></i>
                            <span>Filter</span>
                            @if(($minPrice || $maxPrice || $inStockOnly))
                                <span class="badge bg-primary text-white rounded-pill px-1.5 py-0.5" style="font-size: 0.65rem;">!</span>
                            @endif
                        </button>

                        <button type="submit" class="btn btn-sm btn-primary px-3 flex-shrink-0 d-none d-sm-inline-flex align-items-center gap-1">
                            <i class="bi bi-check2"></i> Terapkan
                        </button>

                    </div>
                </div>

            </div>

            <!-- Baris 2: Collapse Filter Lanjutan (Kategori, Rentang Harga, Status Stok) -->
            <div class="collapse {{ ($minPrice || $maxPrice || $inStockOnly) ? 'show' : '' }} mt-3 pt-3 border-top" id="advancedCatalogFilters">
                <div class="row g-3 align-items-end">
                    
                    <!-- Filter Kategori -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <label class="form-label x-small fw-bold text-muted mb-1">
                            <i class="bi bi-grid me-1 text-primary"></i>Kategori
                        </label>
                        <select name="category" class="form-select form-select-sm" aria-label="Pilih Kategori">
                            <option value="">Semua Kategori</option>
                            @foreach($availableCategories as $catOption)
                                <option value="{{ $catOption }}" {{ ($category ?? '') === $catOption ? 'selected' : '' }}>
                                    {{ $catOption }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rentang Harga: Min Price -->
                    <div class="col-6 col-sm-3 col-md-2">
                        <label class="form-label x-small fw-bold text-muted mb-1">
                            <i class="bi bi-tag me-1 text-primary"></i>Harga Min (Rp)
                        </label>
                        <input type="number" 
                               name="min_price" 
                               class="form-control form-control-sm" 
                               placeholder="0" 
                               min="0" 
                               step="10000" 
                               value="{{ $minPrice ?? '' }}">
                    </div>

                    <!-- Rentang Harga: Max Price -->
                    <div class="col-6 col-sm-3 col-md-2">
                        <label class="form-label x-small fw-bold text-muted mb-1">
                            Harga Maks (Rp)
                        </label>
                        <input type="number" 
                               name="max_price" 
                               class="form-control form-control-sm" 
                               placeholder="Maksimal" 
                               min="0" 
                               step="10000" 
                               value="{{ $maxPrice ?? '' }}">
                    </div>

                    <!-- Status Stok -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="form-check form-switch mb-1">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="in_stock" 
                                   value="1" 
                                   id="in_stock_switch" 
                                   {{ $inStockOnly ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold text-dark" for="in_stock_switch">
                                Hanya Ready Stock
                            </label>
                        </div>
                        <span class="text-muted x-small d-block">Sembunyikan produk yang habis</span>
                    </div>

                    <!-- Action Button in Filter -->
                    <div class="col-12 col-sm-6 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                            Filter
                        </button>
                        @if($search || ($category ?? false) || ($minPrice ?? false) || ($maxPrice ?? false) || $inStockOnly)
                            <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-sm btn-outline-danger px-3" title="Reset semua filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>

                </div>
            </div>

        </form>

        <!-- Active Filter Pills Bar -->
        @if($search || ($category ?? false) || ($minPrice ?? false) || ($maxPrice ?? false) || $inStockOnly)
            <div class="d-flex align-items-center flex-wrap gap-2 pt-3 mt-3 border-top">
                <span class="text-muted x-small fw-semibold me-1">Filter Aktif:</span>
                
                @if($search)
                    <span class="badge bg-light text-dark border px-2 py-1 x-small fw-normal d-inline-flex align-items-center gap-1">
                        <span>Kata kunci: <strong>"{{ $search }}"</strong></span>
                        <a href="{{ route('products.index', array_filter(['category' => $category, 'sort' => $sort, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'in_stock' => $inStockOnly ? '1' : null])) }}#katalog-produk" class="text-secondary text-decoration-none">&times;</a>
                    </span>
                @endif

                @if($category ?? false)
                    <span class="badge bg-light text-dark border px-2 py-1 x-small fw-normal d-inline-flex align-items-center gap-1">
                        <span>Kategori: <strong>{{ $category }}</strong></span>
                        <a href="{{ route('products.index', array_filter(['search' => $search, 'sort' => $sort, 'min_price' => $minPrice, 'max_price' => $maxPrice, 'in_stock' => $inStockOnly ? '1' : null])) }}#katalog-produk" class="text-secondary text-decoration-none">&times;</a>
                    </span>
                @endif

                @if($minPrice)
                    <span class="badge bg-light text-dark border px-2 py-1 x-small fw-normal d-inline-flex align-items-center gap-1">
                        <span>Min: <strong>Rp {{ number_format((float)$minPrice, 0, ',', '.') }}</strong></span>
                        <a href="{{ route('products.index', array_filter(['search' => $search, 'category' => $category, 'sort' => $sort, 'max_price' => $maxPrice, 'in_stock' => $inStockOnly ? '1' : null])) }}#katalog-produk" class="text-secondary text-decoration-none">&times;</a>
                    </span>
                @endif

                @if($maxPrice)
                    <span class="badge bg-light text-dark border px-2 py-1 x-small fw-normal d-inline-flex align-items-center gap-1">
                        <span>Maks: <strong>Rp {{ number_format((float)$maxPrice, 0, ',', '.') }}</strong></span>
                        <a href="{{ route('products.index', array_filter(['search' => $search, 'category' => $category, 'sort' => $sort, 'min_price' => $minPrice, 'in_stock' => $inStockOnly ? '1' : null])) }}#katalog-produk" class="text-secondary text-decoration-none">&times;</a>
                    </span>
                @endif

                @if($inStockOnly)
                    <span class="badge bg-light text-dark border px-2 py-1 x-small fw-normal d-inline-flex align-items-center gap-1">
                        <span><i class="bi bi-check2 text-success me-0.5"></i> Ready Stock Saja</span>
                        <a href="{{ route('products.index', array_filter(['search' => $search, 'category' => $category, 'sort' => $sort, 'min_price' => $minPrice, 'max_price' => $maxPrice])) }}#katalog-produk" class="text-secondary text-decoration-none">&times;</a>
                    </span>
                @endif

                <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-link btn-sm p-0 text-danger x-small text-decoration-none fw-semibold ms-1">
                    <i class="bi bi-trash3 me-1"></i>Reset Semua
                </a>
            </div>
        @endif
    </div>

    <!-- Product Grid (2 columns on mobile, 3 on tablet, 4 on desktop) -->
    <div class="row g-3 g-md-3 g-lg-4" id="products-catalog-grid">
        @php
            $svgFallback = "data:image/svg+xml;charset=UTF-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20400%20400%22%20width%3D%22400%22%20height%3D%22400%22%3E%3Crect%20fill%3D%22%23F1F5F9%22%20width%3D%22400%22%20height%3D%22400%22%2F%3E%3Crect%20x%3D%22150%22%20y%3D%22140%22%20width%3D%22100%22%20height%3D%2280%22%20rx%3D%2210%22%20fill%3D%22%23CBD5E1%22%2F%3E%3Ccircle%20cx%3D%22180%22%20cy%3D%22170%22%20r%3D%2210%22%20fill%3D%22%2394A3B8%22%2F%3E%3Cpath%20d%3D%22M165%20205l20-22%2015%2015%2022-25%2018%2032z%22%20fill%3D%22%2394A3B8%22%2F%3E%3Ctext%20x%3D%2250%25%22%20y%3D%22260%22%20text-anchor%3D%22middle%22%20fill%3D%22%2394A3B8%22%20font-family%3D%22sans-serif%22%20font-size%3D%2213%22%20font-weight%3D%22600%22%3EBladeStore%3C%2Ftext%3E%3C%2Fsvg%3E";
        @endphp
        @forelse($products as $product)
            @php
                $soldCount = 40 + ($product->id * 7);
            @endphp
            <div class="col-6 col-md-4 col-lg-3 product-grid-col" data-product-id="{{ $product->id }}">
                <div class="card h-100 product-card d-flex flex-column position-relative">
                    
                    <!-- 1:1 Image Box (Contain fit, product is never clipped) -->
                    <div class="product-img-box">
                        <a href="{{ route('products.show', $product->slug) }}" class="d-block w-100 h-100 position-relative z-1" title="{{ $product->name }}">
                            <img src="{{ $product->image ?: $svgFallback }}"
                                 class="product-img"
                                 alt="{{ $product->name }}"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='{{ $svgFallback }}';">
                        </a>

                        <!-- Floating Wishlist Button Top-Right -->
                        <button type="button" 
                                class="btn-product-wishlist" 
                                data-product-id="{{ $product->id }}" 
                                data-product-name="{{ $product->name }}"
                                title="Tambah ke Favorit" 
                                aria-label="Favoritkan {{ $product->name }}">
                            <i class="bi bi-heart"></i>
                        </button>

                        <!-- Badges Stacked Top-Left -->
                        <div class="position-absolute top-0 start-0 m-2 z-2 d-flex flex-column gap-1 align-items-start pointer-events-none">
                            @if($product->discount_percentage > 0)
                                <span class="badge-discount shadow-xs">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            @endif
                            <span class="badge-category shadow-xs">
                                {{ $product->category ?? 'Umum' }}
                            </span>
                            @if($product->stock <= 0)
                                <span class="badge bg-danger text-white shadow-xs x-small px-1.5 py-0.5 fw-bold rounded-1">
                                    Habis
                                </span>
                            @elseif($product->stock <= 5)
                                <span class="badge bg-warning text-dark shadow-xs x-small px-1.5 py-0.5 fw-bold rounded-1">
                                    Sisa {{ $product->stock }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-3 d-flex flex-column">
                        
                        <!-- Rating & Sold Count -->
                        <div class="d-flex align-items-center gap-1 mb-2 text-nowrap" style="height: 1.15rem;">
                            <span class="text-warning small" style="font-size: 0.75rem;">
                                <i class="bi bi-star-fill"></i>
                            </span>
                            <span class="fw-bold text-dark" style="font-size: 0.75rem;">4.9</span>
                            <span class="text-muted" style="font-size: 0.7rem;">&bull; {{ $soldCount }}+ terjual</span>
                        </div>

                        <!-- Product Title (Strict 2-lines clamp with fixed height) -->
                        <h6 class="product-card-title mb-2">
                            <a href="{{ route('products.show', $product->slug) }}" class="text-decoration-none text-dark stretched-link" title="{{ $product->name }}">
                                {{ $product->name }}
                            </a>
                        </h6>

                        <!-- Stock Indicator -->
                        <div class="mb-2 text-nowrap" style="height: 1.15rem;">
                            @if($product->stock > 0)
                                <span class="text-success x-small fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-check-circle-fill" style="font-size: 0.7rem;"></i> Stok Ready ({{ $product->stock }})
                                </span>
                            @else
                                <span class="text-danger x-small fw-semibold d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-x-circle-fill" style="font-size: 0.7rem;"></i> Stok Habis
                                </span>
                            @endif
                        </div>

                        <!-- Price Section (Aligned at Bottom with mt-auto) -->
                        <div class="mb-3 mt-auto pt-1">
                            <div class="fw-bold text-primary product-card-price">
                                @if($product->discount_percentage > 0)
                                    {{ $product->formatted_final_price }}
                                @else
                                    {{ $product->formatted_price }}
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-1.5" style="height: 1.15rem;">
                                @if($product->discount_percentage > 0)
                                    <span class="text-muted text-decoration-line-through" style="font-size: 0.725rem;">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-1 py-0" style="font-size: 0.65rem; font-weight: 700;">
                                        Hemat {{ $product->discount_percentage }}%
                                    </span>
                                @else
                                    <span class="text-muted x-small opacity-0 select-none" aria-hidden="true">&nbsp;</span>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Add To Cart Compact Button -->
                        <div class="position-relative z-3 pt-1">
                            @if($product->stock > 0)
                                <form action="{{ route('cart.store') }}" method="POST" class="form-add-to-cart m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            class="btn btn-primary btn-add-cart-compact w-100 py-1.5 px-2 rounded-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 text-nowrap" 
                                            aria-label="Tambah {{ $product->name }} ke Keranjang">
                                        <i class="bi bi-cart-plus-fill"></i>
                                        <span>+ Keranjang</span>
                                    </button>
                                </form>
                            @else
                                <button type="button" 
                                        class="btn btn-light border text-muted w-100 py-1.5 px-2 rounded-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 disabled" 
                                        style="font-size: 0.775rem;" 
                                        disabled>
                                    <i class="bi bi-dash-circle"></i>
                                    <span>Stok Habis</span>
                                </button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 p-5 text-center rounded-3 bg-white shadow-sm my-3">
                    <div class="mb-3">
                        <span class="p-3 bg-primary-subtle text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 68px; height: 68px;">
                            <i class="bi bi-search fs-2"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Produk Tidak Ditemukan</h5>
                    <p class="text-muted small mb-4 mx-auto" style="max-width: 460px;">
                        @if($search || $category || $minPrice || $maxPrice || $inStockOnly)
                            Tidak ada produk yang sesuai dengan filter atau kata kunci yang Anda masukkan. Silakan ubah filter atau reset pencarian.
                        @else
                            Saat ini belum ada produk yang tersedia di katalog BladeStore.
                        @endif
                    </p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <a href="{{ route('products.index') }}#katalog-produk" class="btn btn-primary btn-sm px-4 py-2 fw-semibold">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Semua Filter
                        </a>
                        <a href="{{ route('products.index') }}#kategori-marketplace" class="btn btn-outline-primary btn-sm px-3 py-2 fw-semibold">
                            <i class="bi bi-grid me-1"></i> Jelajahi Kategori
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
