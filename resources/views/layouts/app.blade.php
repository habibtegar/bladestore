<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', config('app.name', 'BladeStore')) — Marketplace Multi-Kategori Terpercaya</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            /* BladeStore Original Electric Blue Design System */
            --primary: #0866FF;
            --primary-hover: #0052CC;
            --primary-active: #0040A8;
            --primary-subtle: #EEF4FF;
            --primary-border: #BCD4FF;
            
            --dark-navy: #0F172A;
            --slate-800: #1E293B;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748B;
            --slate-400: #94A3B8;
            --border-color: #E2E8F0;
            --bg-page: #F4F6F9;
            --card-bg: #FFFFFF;
            
            --success: #10B981;
            --success-subtle: #ECFDF5;
            --danger: #EF4444;
            --danger-subtle: #FEF2F2;
            --warning: #F59E0B;
            --warning-subtle: #FFFBEB;
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --shadow-subtle: 0 1px 3px rgba(15, 23, 42, 0.05), 0 1px 2px rgba(15, 23, 42, 0.03);
            --shadow-card: 0 4px 12px rgba(15, 23, 42, 0.04), 0 1px 3px rgba(15, 23, 42, 0.02);
            --shadow-hover: 0 10px 25px -3px rgba(8, 102, 255, 0.12), 0 4px 6px -4px rgba(15, 23, 42, 0.05);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--bg-page);
            color: var(--slate-700);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            line-height: 1.5;
            padding-bottom: 65px; /* Safe space for mobile bottom app bar */
        }

        @media (min-width: 768px) {
            body {
                padding-bottom: 0;
            }
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--dark-navy);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.15s ease;
        }

        a:hover {
            color: var(--primary-hover);
        }

        /* Sticky Marketplace Header */
        .marketplace-header {
            background-color: #FFFFFF;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
            padding: 0.65rem 0;
            margin: 0 !important;
        }

        /* Brand Logo */
        .brand-link {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            text-decoration: none;
        }

        .brand-logo-icon {
            background: linear-gradient(135deg, var(--primary) 0%, #1D4ED8 100%);
            color: #FFFFFF;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            box-shadow: 0 3px 8px rgba(8, 102, 255, 0.22);
            flex-shrink: 0;
        }

        .brand-title {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--dark-navy);
            margin: 0;
            line-height: 1.1;
        }

        .brand-title span {
            color: var(--primary);
        }

        .brand-tagline {
            font-size: 0.6rem;
            color: var(--slate-500);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 600;
            display: block;
            line-height: 1;
        }

        /* Horizontal Navigation Menu Links (Minimalist & Airy) */
        .nav-menu-list {
            display: flex;
            align-items: center;
            gap: 2.75rem; /* Wider, very airy and breathable */
        }

        @media (min-width: 1200px) {
            .nav-menu-list {
                gap: 3.25rem; /* Ultra spacious on large screens */
            }
        }

        .nav-menu-link {
            color: var(--slate-600);
            font-weight: 500; /* font-medium */
            font-size: 1rem; /* text-base */
            letter-spacing: -0.01em;
            padding: 0.65rem 0 0.85rem 0; /* padding-bottom gives breathing room to underline */
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            position: relative;
            transition: color 0.2s ease;
            white-space: nowrap;
        }

        .nav-menu-link::after {
            content: '';
            position: absolute;
            bottom: 0; /* Placed at the bottom edge of the 0.85rem padding */
            left: 0;
            width: 100%;
            height: 2.5px;
            background-color: var(--primary);
            border-radius: 9999px;
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.25s ease;
        }

        .nav-menu-link:hover {
            color: var(--primary);
        }

        .nav-menu-link:hover::after,
        .nav-menu-link.active::after {
            transform: scaleX(1);
        }

        .nav-menu-link.active {
            color: var(--primary);
            font-weight: 600;
        }

        /* Cart Icon Only Button */
        .nav-cart-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            color: var(--slate-700);
            background-color: #FFFFFF;
            border: 1px solid var(--border-color);
            font-size: 1.25rem;
            text-decoration: none;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .nav-cart-btn:hover {
            color: var(--primary);
            background-color: var(--primary-subtle);
            border-color: var(--primary-border);
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(8, 102, 255, 0.12);
        }

        .nav-cart-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: var(--danger);
            color: #FFFFFF;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 19px;
            height: 19px;
            padding: 0 4px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.35);
            line-height: 1;
        }

        .nav-profile-btn {
            color: var(--slate-700);
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.45rem 0.85rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid var(--border-color);
            background-color: #FFFFFF;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-profile-btn:hover,
        .nav-profile-btn[aria-expanded="true"] {
            color: var(--primary);
            border-color: var(--primary-border);
            background-color: var(--primary-subtle);
        }

        /* Mobile Hamburger Button */
        .navbar-hamburger-btn {
            border: 1px solid var(--border-color);
            background: #FFFFFF;
            color: var(--slate-700);
            border-radius: var(--radius-sm);
            padding: 0.35rem 0.55rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .navbar-hamburger-btn:hover {
            background-color: var(--primary-subtle);
            color: var(--primary);
            border-color: var(--primary-border);
        }

        /* Cart Indicator */
        .cart-indicator-btn {
            position: relative;
            background-color: var(--primary-subtle);
            color: var(--primary);
            border: 1px solid var(--primary-border);
            padding: 0.42rem 0.85rem;
            border-radius: var(--radius-sm);
            font-size: 0.825rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .cart-indicator-btn:hover {
            background-color: var(--primary);
            color: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 3px 10px rgba(8, 102, 255, 0.22);
        }

        .cart-badge {
            background-color: var(--danger);
            color: #FFFFFF;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 9999px;
            line-height: 1;
        }

        /* Buttons Standard */
        .btn {
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: var(--radius-sm);
            padding: 0.55rem 1.15rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #FFFFFF;
            box-shadow: 0 2px 4px rgba(8, 102, 255, 0.2);
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(8, 102, 255, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary-border);
            background-color: #FFFFFF;
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #FFFFFF;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(8, 102, 255, 0.2);
        }

        /* Product Card Marketplace */
        .product-card {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            box-shadow: var(--shadow-card);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary-border);
            box-shadow: var(--shadow-hover);
        }

        .product-img-box {
            position: relative;
            width: 100%;
            aspect-ratio: 1 / 1 !important;
            overflow: hidden;
            background-color: #F8FAFC;
        }

        .product-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-img {
            transform: scale(1.04);
        }

        .badge-category {
            background-color: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            color: #FFFFFF;
            font-size: 0.675rem;
            font-weight: 600;
            padding: 0.2rem 0.55rem;
            border-radius: var(--radius-sm);
        }

        .badge-discount {
            background-color: var(--danger);
            color: #FFFFFF;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.45rem;
            border-radius: var(--radius-sm);
        }

        .badge-stock-info {
            background-color: #FFFFFF;
            color: var(--slate-700);
            border: 1px solid var(--border-color);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.15rem 0.45rem;
            border-radius: var(--radius-sm);
        }

        /* Mobile Bottom App Navigation */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #FFFFFF;
            border-top: 1px solid var(--border-color);
            z-index: 1040;
            box-shadow: 0 -2px 10px rgba(15, 23, 42, 0.06);
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 0.4rem 0.25rem 0.35rem 0.25rem;
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--slate-500);
            text-decoration: none;
            font-size: 0.675rem;
            font-weight: 600;
            flex: 1;
            position: relative;
            padding: 0.2rem 0;
            transition: color 0.15s ease;
        }

        .mobile-nav-item i {
            font-size: 1.25rem;
            margin-bottom: 2px;
            line-height: 1;
        }

        .mobile-nav-item.active, .mobile-nav-item:hover {
            color: var(--primary);
        }

        .mobile-cart-badge {
            position: absolute;
            top: -2px;
            right: 25%;
            background-color: var(--danger);
            color: #FFFFFF;
            font-size: 0.65rem;
            font-weight: 700;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* Modern Mega Footer */
        .marketplace-footer {
            background-color: var(--dark-navy);
            color: #94A3B8;
            margin-top: auto;
            border-top: 3px solid var(--primary);
        }

        .footer-heading {
            color: #FFFFFF;
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 1.1rem;
            position: relative;
        }

        .footer-heading::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 28px;
            height: 2px;
            background-color: var(--primary);
            border-radius: 2px;
        }

        .footer-link {
            color: #94A3B8;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.15s ease;
        }

        .footer-link:hover {
            color: #FFFFFF;
            padding-left: 3px;
        }

        /* Footer Payment Method Badges (Flex Layout) */
        .footer-payment-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.65rem;
            font-size: 0.725rem;
            font-weight: 600;
            color: #F8FAFC;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 6px;
            letter-spacing: 0.02em;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .footer-payment-badge:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
        }

        .footer-payment-badge-cod {
            background: rgba(34, 197, 94, 0.18);
            border-color: rgba(34, 197, 94, 0.4);
            color: #4ADE80;
            font-weight: 700;
        }

        /* Primary Blue Color Palette & Card-Like Radio Buttons for Cart & Orders */
        .text-orange {
            color: #2563EB !important;
        }

        .bg-orange {
            background-color: #2563EB !important;
        }

        .btn-orange {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%) !important;
            border-color: #2563EB !important;
            color: #FFFFFF !important;
        }

        .btn-orange:hover, .btn-orange:focus {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
            border-color: #1D4ED8 !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35) !important;
        }

        .bg-blue-600 {
            background-color: #2563EB !important;
        }

        .text-blue-600 {
            color: #2563EB !important;
        }

        .border-blue-600 {
            border-color: #2563EB !important;
        }

        .border-t {
            border-top-width: 1px !important;
            border-top-style: solid !important;
        }

        .space-y-4 > * + * {
            margin-top: 1rem !important;
        }

        .space-y-5 > * + * {
            margin-top: 1.25rem !important;
        }

        .cart-sidepanel {
            box-shadow: -4px 0 24px rgba(15, 23, 42, 0.15) !important;
        }

        .payment-card-label {
            display: block;
            cursor: pointer;
        }

        .payment-radio-input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .payment-radio-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 0.95rem;
            border: 1.5px solid #E2E8F0;
            border-radius: 10px;
            background-color: #FFFFFF;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .payment-radio-card:hover {
            border-color: #93C5FD;
            background-color: #F8FAFC;
        }

        .payment-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background-color: #EFF6FF;
            color: #2563EB;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .check-indicator {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid #CBD5E1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            color: transparent;
            transition: all 0.2s ease;
        }

        .payment-radio-input:checked + .payment-radio-card {
            border: 2.5px solid #2563EB !important;
            background-color: #EFF6FF !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15);
        }

        .payment-radio-input:checked + .payment-radio-card .payment-card-icon {
            background-color: #2563EB;
            color: #FFFFFF;
        }

        .payment-radio-input:checked + .payment-radio-card .check-indicator {
            border-color: #2563EB;
            background-color: #2563EB;
            color: #FFFFFF;
        }

        .x-small {
            font-size: 0.75rem;
        }

        /* Center Screen Toast Notification */
        .cart-toast-center {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.9);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .cart-toast-center.show {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
            pointer-events: auto;
        }

        .cart-toast-box {
            background-color: #0F172A; /* bg-slate-900 / dark */
            color: #FFFFFF;
            border-radius: 14px; /* rounded-lg */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.45), 0 8px 10px -6px rgba(0, 0, 0, 0.25); /* shadow-xl */
            border: 1px solid rgba(255, 255, 255, 0.12);
            padding: 1.15rem 1.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.85rem;
            min-width: 290px;
            max-width: 90vw;
        }

        .cart-toast-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: rgba(16, 185, 129, 0.18);
            color: #10B981; /* emerald/green success */
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .cart-toast-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: #FFFFFF;
            line-height: 1.35;
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- MAIN MARKETPLACE NAVBAR -->
    <nav class="marketplace-header">
        <div class="container d-flex justify-content-between align-items-center">
            
            <!-- Logo & Brand (Kiri) -->
            <a href="{{ route('products.index') }}" class="brand-link">
                <div class="brand-logo-icon">
                    <i class="bi bi-shop-window"></i>
                </div>
                <div>
                    <span class="brand-title">BLADESTORE</span>
                    <span class="brand-tagline">Marketplace</span>
                </div>
            </a>

            @php
                $cartTotalCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
                $navCartItems = auth()->check() ? \App\Models\Cart::with('product')->where('user_id', auth()->id())->get() : collect();
                $navCartTotal = $navCartItems->sum(fn($i) => (float)$i->subtotal);
            @endphp

            <!-- Deretan Menu Navigasi Teks Murni (Tengah: Bersih & Lega) -->
            <div class="nav-menu-list d-none d-lg-flex">
                <a href="{{ route('products.index') }}" class="nav-menu-link {{ request()->routeIs('products.index') && !request('category') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('products.index') }}#kategori-marketplace" class="nav-menu-link {{ request('category') ? 'active' : '' }}">
                    Kategori
                </a>
                <a href="{{ route('orders.index') }}" class="nav-menu-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    Pesanan Saya
                </a>
            </div>

            <!-- Grup Aksi Utama: Keranjang Murni Ikon & Profil (Kanan) -->
            <div class="d-none d-lg-flex align-items-center gap-3">
                <!-- Keranjang (Murni Ikon Tanpa Teks: Side Panel Trigger) -->
                <a href="#cartOffcanvas" data-bs-toggle="offcanvas" role="button" aria-controls="cartOffcanvas" class="nav-cart-btn {{ request()->routeIs('cart.*') ? 'border-primary text-primary' : '' }}" title="Keranjang Belanja" aria-label="Keranjang Belanja">
                    <i class="bi bi-cart3"></i>
                    @if($cartTotalCount > 0)
                        <span class="nav-cart-badge">{{ $cartTotalCount }}</span>
                    @endif
                </a>

                <!-- Profil / Akun di Ujung Paling Kanan -->
                @auth
                    <div class="dropdown">
                        <button class="nav-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="text-truncate" style="max-width: 120px;">
                                {{ auth()->user()->name }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2 mt-2" style="min-width: 220px;">
                            <li class="px-3 py-2 border-bottom mb-1 bg-light">
                                <div class="fw-bold text-dark small text-truncate">{{ auth()->user()->name }}</div>
                                <div class="text-muted x-small text-truncate">{{ auth()->user()->email }}</div>
                                <span class="badge {{ auth()->user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} x-small mt-1">
                                    {{ auth()->user()->isAdmin() ? 'Administrator' : 'Pelanggan' }}
                                </span>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 small" href="{{ route('orders.index') }}">
                                    <i class="bi bi-receipt text-primary"></i> Pesanan Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 small" href="{{ route('cart.index') }}">
                                    <i class="bi bi-bag text-primary"></i> Keranjang Belanja
                                </a>
                            </li>

                            <!-- Panel Admin HANYA muncul jika isAdmin() -->
                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 small text-danger fw-semibold" href="{{ route('admin.orders.index') }}">
                                        <i class="bi bi-shield-lock-fill"></i> Panel Administrator
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                     @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 small text-muted">
                                        <i class="bi bi-box-arrow-right"></i> Keluar Akun
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="dropdown">
                        <button class="nav-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-primary"></i>
                            <span>Profil</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2 mt-2" style="min-width: 180px;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 small fw-semibold text-primary" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right"></i> Masuk Akun
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 small text-secondary" href="{{ route('register') }}">
                                    <i class="bi bi-person-plus"></i> Daftar Akun Baru
                                </a>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>

            <!-- Mobile Action Buttons & Hamburger (d-lg-none) -->
            <div class="d-flex align-items-center gap-2 d-lg-none">
                <!-- Cart Shortcut (Side Panel Trigger) -->
                <a href="#cartOffcanvas" data-bs-toggle="offcanvas" role="button" aria-controls="cartOffcanvas" class="nav-cart-btn" style="width: 38px; height: 38px; font-size: 1.15rem;" title="Keranjang Belanja" aria-label="Keranjang Belanja">
                    <i class="bi bi-cart3"></i>
                    @if($cartTotalCount > 0)
                        <span class="nav-cart-badge">{{ $cartTotalCount }}</span>
                    @endif
                </a>

                <!-- Hamburger Button to trigger Offcanvas -->
                <button class="navbar-hamburger-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavOffcanvas" aria-controls="mobileNavOffcanvas" aria-label="Buka Menu Navigasi">
                    <i class="bi bi-list"></i>
                </button>
            </div>

        </div>
    </nav>

    <!-- Mobile Offcanvas Navigation Drawer -->
    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="mobileNavOffcanvas" aria-labelledby="mobileNavOffcanvasLabel" style="width: 290px;">
        <div class="offcanvas-header border-bottom py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="brand-logo-icon" style="width: 30px; height: 30px; font-size: 0.95rem;">
                    <i class="bi bi-shop-window"></i>
                </div>
                <h6 class="offcanvas-title fw-bold text-dark mb-0" id="mobileNavOffcanvasLabel">Menu BladeStore</h6>
            </div>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
        </div>
        <div class="offcanvas-body p-3 d-flex flex-column justify-content-between">
            <div class="list-group list-group-flush border-0">
                <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 {{ request()->routeIs('products.index') && !request('category') ? 'bg-primary-subtle text-primary fw-bold' : 'text-dark' }}">
                    <i class="bi bi-house-door fs-5 text-primary"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('products.index') }}#kategori-marketplace" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 text-dark">
                    <i class="bi bi-grid fs-5 text-primary"></i>
                    <span>Kategori</span>
                </a>
                <a href="#cartOffcanvas" data-bs-toggle="offcanvas" role="button" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 border-0 rounded-2 text-dark">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cart3 fs-5 text-primary"></i>
                        <span>Keranjang</span>
                    </div>
                    @if($cartTotalCount > 0)
                        <span class="badge bg-danger rounded-pill">{{ $cartTotalCount }}</span>
                    @endif
                </a>
                <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 text-dark">
                    <i class="bi bi-receipt fs-5 text-primary"></i>
                    <span>Pesanan Saya</span>
                </a>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 text-danger fw-semibold">
                            <i class="bi bi-shield-lock-fill fs-5 text-danger"></i>
                            <span>Panel Administrator</span>
                        </a>
                    @endif
                @endauth
            </div>

            <!-- User Auth Footer in Mobile Drawer -->
            <div class="border-top pt-3 mt-3">
                @auth
                    <div class="bg-light p-2.5 rounded-3 mb-2.5">
                        <div class="fw-bold text-dark small text-truncate">{{ auth()->user()->name }}</div>
                        <div class="text-muted x-small text-truncate">{{ auth()->user()->email }}</div>
                        <span class="badge {{ auth()->user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} x-small mt-1">
                            {{ auth()->user()->isAdmin() ? 'Administrator' : 'Pelanggan' }}
                        </span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center gap-1.5 py-2">
                            <i class="bi bi-box-arrow-right"></i> Keluar Akun
                        </button>
                    </form>
                @else
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm py-2">
                            <i class="bi bi-person-plus me-1"></i> Daftar
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    <!-- ==========================================
         SHOPPING CART OFF-CANVAS / SIDE PANEL (PRIMARY BLUE THEME)
         ========================================== -->
    <div class="offcanvas offcanvas-end border-0 shadow-lg cart-sidepanel" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="width: 440px; max-width: 95vw; z-index: 1060;">
        
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
                                        <form action="{{ route('cart.destroy', $cartItem->id) }}" method="POST" class="d-inline">
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

                    <!-- 4. Pilihan Metode Pembayaran (Card-like Radio Button) -->
                    <div>
                        <label class="form-label fw-bold text-dark small mb-2 d-flex align-items-center gap-1.5">
                            <i class="bi bi-credit-card-2-front-fill text-blue-600" style="color: #2563EB;"></i>
                            <span>Metode Pembayaran</span>
                        </label>
                        <div class="d-flex flex-column gap-2">
                            
                            <!-- 1. Transfer Bank -->
                            <label class="payment-card-label m-0">
                                <input type="radio" name="payment_method" value="Transfer Bank Manual" class="payment-radio-input" checked>
                                <div class="payment-radio-card">
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="payment-card-icon">
                                            <i class="bi bi-bank2"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">Transfer Bank</div>
                                            <div class="text-muted x-small">BCA, Mandiri, BRI, BNI</div>
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
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="payment-card-icon">
                                            <i class="bi bi-qr-code-scan"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">QRIS Instant</div>
                                            <div class="text-muted x-small">Gopay, OVO, Dana, ShopeePay</div>
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
                                    <div class="d-flex align-items-center gap-2.5">
                                        <div class="payment-card-icon">
                                            <i class="bi bi-cash-stack"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">COD (Bayar di Tempat)</div>
                                            <div class="text-muted x-small">Bayar tunai aman saat kurir tiba</div>
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

    </div>

    <!-- 3. HERO SECTION (FULL WIDTH) -->
    @hasSection('hero')
        @yield('hero')
    @endif

    <!-- 5. FLASH ALERTS & MAIN CONTENT -->
    <main class="py-3 py-md-4 flex-grow-1">
        <div class="container">
            
            <!-- Success Flash -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div class="small fw-semibold">{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Error Flash -->
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 rounded-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                    <div class="small fw-semibold">{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Validation Errors -->
            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1 fw-bold small">
                        <i class="bi bi-exclamation-octagon-fill"></i> Periksa kembali data Anda:
                    </div>
                    <ul class="mb-0 ps-3 x-small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- 5. MOBILE BOTTOM NAVIGATION (App Experience) -->
    <nav class="mobile-bottom-nav d-md-none">
        <a href="{{ route('products.index') }}" class="mobile-nav-item {{ request()->routeIs('products.index') && !request('category') ? 'active' : '' }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('products.index') }}#kategori-marketplace" class="mobile-nav-item {{ request('category') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Kategori</span>
        </a>
        <a href="{{ route('cart.index') }}" class="mobile-nav-item {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="bi bi-bag-fill"></i>
            <span>Keranjang</span>
            @if($cartTotalCount > 0)
                <span class="mobile-cart-badge">{{ $cartTotalCount }}</span>
            @endif
        </a>
        <a href="{{ route('orders.index') }}" class="mobile-nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>Pesanan</span>
        </a>
        @auth
            <a href="{{ route('orders.index') }}" class="mobile-nav-item">
                <i class="bi bi-person-circle"></i>
                <span>Akun</span>
            </a>
        @else
            <a href="{{ route('login') }}" class="mobile-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Masuk</span>
            </a>
        @endauth
    </nav>

    <!-- 6. MODERN MULTI-CATEGORY MARKETPLACE FOOTER -->
    @section('footer')
    <footer class="marketplace-footer py-5" id="bantuan">
        <div class="container">
            <div class="row g-4 g-lg-5 mb-4">
                
                <!-- Col 1: About LaraShop -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="brand-logo-icon" style="width: 32px; height: 32px; font-size: 1rem; border-radius: 6px;">
                            <i class="bi bi-shop-window"></i>
                        </div>
                        <span class="fs-4 fw-bold text-white tracking-tight">LARA<span style="color: var(--primary);">SHOP</span></span>
                    </div>
                    <p class="small text-light text-opacity-75 leading-relaxed mb-0">
                        LaraShop adalah marketplace terpercaya di Indonesia. Temukan beragam produk pilihan berkualitas dengan transaksi yang mudah, aman, dan nyaman.
                    </p>
                </div>

                <!-- Col 2: Kategori Populer -->
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="footer-heading">Kategori Pilihan</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small mb-0">
                        <li><a href="{{ route('products.index', ['category' => 'Elektronik']) }}" class="footer-link">Elektronik</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Fashion']) }}" class="footer-link">Fashion</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Kecantikan']) }}" class="footer-link">Kecantikan</a></li>
                        <li><a href="{{ route('products.index', ['category' => 'Rumah Tangga']) }}" class="footer-link">Rumah Tangga</a></li>
                    </ul>
                </div>

                <!-- Col 3: Customer Care & Layanan -->
                <div class="col-6 col-md-3 col-lg-3">
                    <h6 class="footer-heading">Bantuan & Layanan</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small mb-0">
                        <li><a href="{{ route('cart.index') }}" class="footer-link">Keranjang Belanja</a></li>
                        <li><a href="{{ route('orders.index') }}" class="footer-link">Lacak Pesanan</a></li>
                        <li><a href="#" class="footer-link">Panduan Belanja COD</a></li>
                        <li><a href="#" class="footer-link">Kebijakan Garansi & Retur</a></li>
                        <li class="pt-2">
                            <div class="d-flex align-items-start gap-2 text-light">
                                <i class="bi bi-whatsapp text-success fs-5"></i>
                                <div>
                                    <div class="text-light text-opacity-75 x-small fw-semibold">Customer Care WhatsApp</div>
                                    <div class="fw-bold text-white small">0812-3456-7890</div>
                                    <div class="text-light text-opacity-50 x-small">Senin - Minggu (08.00 - 22.00 WIB)</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Col 4: Metode Pembayaran & Jaminan Keamanan -->
                <div class="col-12 col-md-6 col-lg-3">
                    <h6 class="footer-heading">Metode Pembayaran</h6>
                    <p class="x-small text-light text-opacity-75 mb-2.5">Transfer Bank & Bayar di Tempat:</p>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <span class="footer-payment-badge">BCA</span>
                        <span class="footer-payment-badge">Mandiri</span>
                        <span class="footer-payment-badge">BRI</span>
                        <span class="footer-payment-badge">BNI</span>
                        <span class="footer-payment-badge footer-payment-badge-cod">
                            <i class="bi bi-cash-stack me-1"></i> COD
                        </span>
                    </div>

                    <h6 class="footer-heading">Jaminan Transaksi</h6>
                    <div class="d-flex flex-column gap-1.5 x-small text-light text-opacity-75">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shield-lock-fill text-primary"></i>
                            <span>Enkripsi Data Transaksi 256-bit</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-arrow-repeat text-primary"></i>
                            <span>Garansi Pengembalian Jika Barang Cacat</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-4 border-top border-secondary border-opacity-25 small text-secondary">
                <div>
                    &copy; {{ date('Y') }} <strong>LaraShop</strong>.
                </div>
            </div>
        </div>
    </footer>
    @show

    <!-- Center Screen Toast Notification -->
    <div id="cart-toast" class="cart-toast-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="cart-toast-box">
            <div class="cart-toast-icon">
                <i class="bi bi-cart-check-fill"></i>
            </div>
            <div class="cart-toast-text" id="cart-toast-message">
                Berhasil ditambahkan ke keranjang
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        (function() {
            let toastTimeout = null;

            window.showCartToast = function(message = 'Berhasil ditambahkan ke keranjang') {
                const toast = document.getElementById('cart-toast');
                const msgEl = document.getElementById('cart-toast-message');
                if (!toast) return;

                if (msgEl && message) {
                    msgEl.textContent = message;
                }

                if (toastTimeout) {
                    clearTimeout(toastTimeout);
                }

                toast.classList.add('show');

                // Durasi 2.5 detik (antara 2 hingga 3 detik)
                toastTimeout = setTimeout(function() {
                    toast.classList.remove('show');
                }, 2500);
            };

            // 1. Logika Halaman Reload: Khusus tangkap session cart_added dari Laravel
            @if(session('cart_added'))
                document.addEventListener('DOMContentLoaded', function() {
                    window.showCartToast(@json(session('cart_added')));
                });
            @endif

            // 2. Logika AJAX: Tangkap form tambah keranjang
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form');
                if (!form) return;

                const action = form.getAttribute('action') || '';
                const isCartStore = form.classList.contains('form-add-to-cart') ||
                    (form.method.toUpperCase() === 'POST' && (action.includes('/cart') && !action.includes('/cart/')));

                if (!isCartStore) return;
                if (form.hasAttribute('data-no-ajax')) return;

                e.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                let originalBtnHtml = '';
                if (submitBtn) {
                    originalBtnHtml = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                }

                const formData = new FormData(form);

                fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(function(res) {
                    if (res.status === 401) {
                        window.location.href = '{{ route("login") }}';
                        return null;
                    }
                    return res.json().then(function(data) {
                        return { ok: res.ok, status: res.status, data: data };
                    });
                })
                .then(function(result) {
                    if (!result) return;
                    if (result.ok && result.data && result.data.success) {
                        // Tampilkan toast di tengah layar
                        window.showCartToast(result.data.message || 'Berhasil ditambahkan ke keranjang');

                        // Perbarui indikator angka keranjang di navbar
                        if (typeof result.data.cart_count !== 'undefined') {
                            document.querySelectorAll('.nav-cart-badge, .mobile-cart-badge').forEach(function(badge) {
                                badge.textContent = result.data.cart_count;
                                badge.classList.remove('d-none');
                            });
                        }
                    } else {
                        const errorMsg = (result.data && result.data.message) ? result.data.message : 'Gagal menambahkan ke keranjang.';
                        alert(errorMsg);
                    }
                })
                .catch(function(err) {
                    console.error('AJAX add to cart error:', err);
                    form.setAttribute('data-no-ajax', 'true');
                    form.submit();
                })
                .finally(function() {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    }
                });
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
