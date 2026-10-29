<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BladeStore - {{ trim(preg_replace('/^(BladeStore\s*[-—]\s*|\s*[-—]\s*BladeStore.*$)/i', '', View::yieldContent('title') ?: 'Marketplace Multi-Kategori Terpercaya')) }}</title>

    <!-- Favicon BladeStore Logo -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}">

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

        /* Nav Action Buttons (Wishlist, Notifikasi, & Keranjang) */
        .nav-icon-btn,
        .nav-cart-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: var(--slate-700);
            background-color: #FFFFFF;
            border: 1px solid var(--border-color);
            font-size: 1.15rem;
            text-decoration: none;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .nav-icon-btn:hover,
        .nav-cart-btn:hover {
            color: var(--primary);
            background-color: var(--primary-subtle);
            border-color: var(--primary-border);
            transform: translateY(-1px);
            box-shadow: 0 2px 6px rgba(8, 102, 255, 0.12);
        }

        .nav-icon-badge,
        .nav-cart-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: #EF4444;
            color: #FFFFFF;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.35);
            line-height: 1;
        }

        /* Global Search Bar (Marketplace Standard) */
        .global-search-form {
            max-width: 480px;
            width: 100%;
            position: relative;
        }

        .global-search-input {
            background-color: #F8FAFC !important;
            border: 1.5px solid #E2E8F0 !important;
            border-radius: 9999px !important;
            padding-left: 2.5rem !important;
            padding-right: 4.8rem !important;
            font-size: 0.88rem !important;
            height: 42px !important;
            transition: all 0.2s ease !important;
            color: var(--dark-navy) !important;
        }

        .global-search-input:focus {
            background-color: #FFFFFF !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(8, 102, 255, 0.12) !important;
        }

        .global-search-icon {
            position: absolute;
            left: 0.95rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            font-size: 0.95rem;
            pointer-events: none;
            z-index: 5;
        }

        .global-search-btn {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            height: 34px;
            padding: 0 1.1rem;
            border-radius: 9999px;
            background-color: var(--primary);
            border: none;
            color: #FFFFFF;
            font-size: 0.825rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.35rem;
            transition: background-color 0.2s ease;
            z-index: 5;
        }

        .global-search-btn:hover {
            background-color: var(--primary-hover);
            color: #FFFFFF;
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
            background: linear-gradient(180deg, #F8FAFC 0%, #F1F5F9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-img {
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            object-position: center !important;
            padding: 0.65rem;
            display: block;
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .product-card:hover .product-img {
            transform: scale(1.06);
        }

        /* Global Wishlist Heart Button on Cards */
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

        .space-y-6 > * + * {
            margin-top: 1.5rem !important;
        }

        .sticky {
            position: sticky !important;
        }

        .top-24 {
            top: 6rem !important;
        }

        .top-28 {
            top: 7rem !important;
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
        <div class="container d-flex justify-content-between align-items-center gap-2 gap-lg-3">
            
            <!-- Logo & Brand (Kiri) -->
            <a href="{{ route('products.index') }}" class="brand-link flex-shrink-0">
                <img src="{{ asset('images/logo.svg') }}" alt="BladeStore Logo" class="brand-logo-img" style="width: 36px; height: 36px; border-radius: 8px; box-shadow: 0 3px 8px rgba(8, 102, 255, 0.22); flex-shrink: 0;">
                <div class="d-none d-sm-block">
                    <span class="brand-title">BLADESTORE</span>
                    <span class="brand-tagline">Marketplace</span>
                </div>
            </a>

            @php
                $cartTotalCount = auth()->check() ? \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') : 0;
                $navCartItems = auth()->check() ? \App\Models\Cart::with('product')->where('user_id', auth()->id())->get() : collect();
                $navCartTotal = $navCartItems->sum(fn($i) => (float)$i->subtotal);
            @endphp

            <!-- Global Search Bar (Tengah: Standar Marketplace Profesional) -->
            <form action="{{ route('products.index') }}" method="GET" class="global-search-form flex-grow-1 mx-2 mx-md-3 mx-lg-4 d-none d-md-block">
                <div class="position-relative w-100">
                    <i class="bi bi-search global-search-icon"></i>
                    <input type="text" 
                           name="search" 
                           class="form-control global-search-input" 
                           placeholder="Cari berbagai produk berkualitas di BladeStore..." 
                           value="{{ request('search') }}"
                           aria-label="Cari Produk di BladeStore">
                    <button class="btn global-search-btn" type="submit" aria-label="Cari">
                        <span>Cari</span>
                    </button>
                </div>
            </form>

            <!-- Menu Cepat (Desktop: Kategori & Beranda) -->
            <div class="nav-menu-list d-none d-xl-flex me-1">
                <a href="{{ route('products.index') }}" class="nav-menu-link {{ request()->routeIs('products.index') && !request('category') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('products.index') }}#kategori-marketplace" class="nav-menu-link {{ request('category') ? 'active' : '' }}">
                    Kategori
                </a>
            </div>

            <!-- Grup Aksi Kanan: Ikon Interaksi & Dropdown Profil -->
            <div class="d-flex align-items-center gap-2 gap-lg-3 flex-shrink-0">
                
                <!-- Ikon Interaksi Sejajar (Wishlist, Notifikasi, Keranjang Belanja) -->
                <div class="d-flex align-items-center gap-1.5 gap-sm-2">
                    <!-- Wishlist (Hati) -->
                    <a href="{{ route('products.index') }}#katalog-produk" 
                       class="nav-icon-btn d-none d-sm-inline-flex position-relative" 
                       title="Favorit Saya (Wishlist)" 
                       aria-label="Wishlist" 
                       id="nav-wishlist-trigger">
                        <i class="bi bi-heart" id="nav-wishlist-icon"></i>
                        <span class="nav-icon-badge d-none" id="wishlist-badge-count">0</span>
                    </a>

                    <!-- Notifikasi (Lonceng) dengan Badge Angka Notifikasi Kecil -->
                    <a href="{{ route('orders.index') }}" 
                       class="nav-icon-btn position-relative" 
                       title="Notifikasi Pembaruan Pesanan" 
                       aria-label="Notifikasi">
                        <i class="bi bi-bell"></i>
                        <span class="nav-icon-badge" id="notification-badge-count">2</span>
                    </a>

                    <!-- Keranjang Belanja (Side Panel Trigger) -->
                    <a href="#cartOffcanvas" 
                       data-bs-toggle="offcanvas" 
                       role="button" 
                       aria-controls="cartOffcanvas" 
                       class="nav-cart-btn {{ request()->routeIs('cart.*') ? 'border-primary text-primary' : '' }}" 
                       title="Keranjang Belanja" 
                       aria-label="Keranjang Belanja">
                        <i class="bi bi-cart3"></i>
                        <span id="cart-badge-count" 
                              class="nav-cart-badge {{ $cartTotalCount > 0 ? '' : 'd-none' }}" 
                              style="{{ $cartTotalCount > 0 ? '' : 'display: none;' }}">{{ $cartTotalCount }}</span>
                    </a>
                </div>

                <!-- Divider Halus Antara Ikon dan Profil -->
                <div class="vr my-2 text-secondary opacity-25 d-none d-lg-block" style="height: 24px;"></div>

                <!-- Dropdown Profil Pengguna (Desktop) -->
                @auth
                    <div class="dropdown d-none d-lg-block">
                        <button class="nav-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 26px; height: 26px; font-size: 0.75rem;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                            <span class="text-truncate d-none d-xl-inline" style="max-width: 120px;">
                                {{ auth()->user()->name }}
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 py-2 mt-2" style="min-width: 240px;">
                            <!-- Header Info Pengguna -->
                            <li class="px-3 py-2 border-bottom mb-1 bg-light">
                                <div class="fw-bold text-dark small text-truncate">{{ auth()->user()->name }}</div>
                                <div class="text-muted x-small text-truncate">{{ auth()->user()->email }}</div>
                                <span class="badge {{ auth()->user()->isAdmin() ? 'bg-danger' : 'bg-primary' }} x-small mt-1">
                                    {{ auth()->user()->isAdmin() ? 'Administrator' : 'Pelanggan' }}
                                </span>
                            </li>

                            <!-- 1. Pengaturan Akun -->
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2.5 py-2 small" href="#" onclick="alert('Fitur Pengaturan Akun akan segera hadir.'); return false;">
                                    <i class="bi bi-gear text-secondary fs-6"></i>
                                    <span>Pengaturan Akun</span>
                                </a>
                            </li>

                            <!-- 2. Pesanan Saya -->
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2.5 py-2 small" href="{{ route('orders.index') }}">
                                    <i class="bi bi-receipt text-primary fs-6"></i>
                                    <span>Pesanan Saya</span>
                                </a>
                            </li>

                            {{-- 3. Dashboard Admin (Hanya jika pengguna login memiliki role sebagai Admin) --}}
                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between py-2 small text-danger fw-semibold" href="{{ route('admin.dashboard') }}">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <i class="bi bi-grid-1x2-fill fs-6 text-danger"></i>
                                            <span>Dashboard Admin</span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle x-small">Utama</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between py-2 small text-danger fw-semibold" href="{{ route('admin.orders.index') }}">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <i class="bi bi-receipt-cutoff fs-6 text-danger"></i>
                                            <span>Kelola Pesanan</span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle x-small">Panel Administrator</span>
                                    </a>
                                </li>
                            @endif

                            <!-- 4. Keluar / Logout (dengan form POST yang aman) -->
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2.5 py-2 small text-muted">
                                        <i class="bi bi-box-arrow-right fs-6"></i>
                                        <span>Keluar / Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="dropdown d-none d-lg-block">
                        <button class="nav-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-primary"></i>
                            <span>Akun</span>
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

                <!-- Hamburger Button to trigger Offcanvas (Mobile) -->
                <button class="navbar-hamburger-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNavOffcanvas" aria-controls="mobileNavOffcanvas" aria-label="Buka Menu Navigasi">
                    <i class="bi bi-list"></i>
                </button>
            </div>

        </div>

        <!-- Mobile Global Search Bar Baris Kedua (< 768px) -->
        <div class="container d-md-none mt-2 pt-1">
            <form action="{{ route('products.index') }}" method="GET" class="global-search-form w-100">
                <div class="position-relative w-100">
                    <i class="bi bi-search global-search-icon"></i>
                    <input type="text" 
                           name="search" 
                           class="form-control global-search-input" 
                           style="height: 38px !important; font-size: 0.84rem !important;"
                           placeholder="Cari berbagai produk di BladeStore..." 
                           value="{{ request('search') }}"
                           aria-label="Cari Produk">
                    <button class="btn global-search-btn" type="submit" style="height: 30px; font-size: 0.75rem;">
                        <span>Cari</span>
                    </button>
                </div>
            </form>
        </div>
    </nav>

    <!-- Mobile Offcanvas Navigation Drawer -->
    <div class="offcanvas offcanvas-end border-0 shadow" tabindex="-1" id="mobileNavOffcanvas" aria-labelledby="mobileNavOffcanvasLabel" style="width: 290px;">
        <div class="offcanvas-header border-bottom py-3">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="BladeStore Logo" style="width: 30px; height: 30px; border-radius: 6px; flex-shrink: 0;">
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
                    <span id="cart-badge-count-drawer" class="badge bg-danger rounded-pill {{ $cartTotalCount > 0 ? '' : 'd-none' }}" style="{{ $cartTotalCount > 0 ? '' : 'display: none;' }}">{{ $cartTotalCount }}</span>
                </a>
                <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 border-0 rounded-2 text-dark">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-bell fs-5 text-warning"></i>
                        <span>Notifikasi</span>
                    </div>
                    <span class="badge bg-danger rounded-pill">2</span>
                </a>
                <a href="{{ route('products.index') }}#katalog-produk" id="mobile-drawer-wishlist-trigger" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between py-2.5 px-2 border-0 rounded-2 text-dark">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-heart fs-5 text-danger"></i>
                        <span>Wishlist (Favorit)</span>
                    </div>
                    <span class="badge bg-danger rounded-pill d-none" id="wishlist-badge-count-drawer">0</span>
                </a>
                <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 text-dark">
                    <i class="bi bi-receipt fs-5 text-primary"></i>
                    <span>Pesanan Saya</span>
                </a>
                @auth
                    <a href="#" onclick="alert('Fitur Pengaturan Akun akan segera hadir.'); return false;" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 text-dark">
                        <i class="bi bi-gear fs-5 text-secondary"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 text-danger fw-semibold">
                            <i class="bi bi-grid-1x2-fill fs-5 text-danger"></i>
                            <span>Dashboard Admin</span>
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2.5 px-2 border-0 rounded-2 text-danger fw-semibold">
                            <i class="bi bi-receipt-cutoff fs-5 text-danger"></i>
                            <span>Kelola Pesanan (Panel Administrator)</span>
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
        @include('cart.partials.offcanvas')
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
                
                <!-- Col 1: About BladeStore -->
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('images/logo.svg') }}" alt="BladeStore Logo" style="width: 32px; height: 32px; border-radius: 6px; flex-shrink: 0;">
                        <span class="fs-4 fw-bold text-white tracking-tight">BLADESTORE</span>
                    </div>
                    <p class="small text-light text-opacity-75 leading-relaxed mb-0">
                        BladeStore adalah marketplace terpercaya di Indonesia. Temukan beragam produk pilihan berkualitas dengan transaksi yang mudah, aman, dan nyaman.
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
                    &copy; 2026 <strong>BladeStore</strong>.
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

            // 2. Logika AJAX: Tangkap form tambah keranjang & hapus dari sidebar
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('form');
                if (!form) return;

                const isDeleteCart = form.classList.contains('form-delete-nav-cart');
                const action = form.getAttribute('action') || '';
                const isCartStore = form.classList.contains('form-add-to-cart') ||
                    (form.method.toUpperCase() === 'POST' && (action.includes('/cart') && !action.includes('/cart/') && !action.includes('/cart/sidebar')));

                if (!isCartStore && !isDeleteCart) return;
                if (form.hasAttribute('data-no-ajax')) return;

                e.preventDefault();

                const submitBtn = form.querySelector('button[type="submit"]');
                let originalBtnHtml = '';
                if (submitBtn) {
                    originalBtnHtml = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menambahkan...';
                }

                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
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
                        if (isCartStore) {
                            // Tampilkan toast di tengah layar
                            window.showCartToast(result.data.message || 'Berhasil ditambahkan ke keranjang');
                        }

                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Ditambahkan!';
                            setTimeout(function() {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalBtnHtml;
                            }, 1000);
                        }

                        // Perbarui indikator angka keranjang di navbar (cart badge count)
                        if (typeof result.data.cart_count !== 'undefined') {
                            const cartCount = parseInt(result.data.cart_count, 10);
                            const badgeEl = document.getElementById('cart-badge-count');
                            if (badgeEl) {
                                badgeEl.innerText = result.data.cart_count;
                                if (cartCount > 0) {
                                    badgeEl.classList.remove('d-none');
                                    badgeEl.style.display = 'inline-flex';
                                } else {
                                    badgeEl.classList.add('d-none');
                                    badgeEl.style.display = 'none';
                                }
                            }

                            // Perbarui juga seluruh badge keranjang lainnya (misal tampilan mobile & drawer)
                            document.querySelectorAll('.nav-cart-badge, .mobile-cart-badge, #cart-badge-count-mobile, #cart-badge-count-drawer').forEach(function(badge) {
                                badge.innerText = result.data.cart_count;
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
                        }

                        // Perbarui DOM sidebar keranjang secara langsung
                        const cartOffcanvas = document.getElementById('cartOffcanvas');
                        if (cartOffcanvas && result.data.sidebar_html) {
                            cartOffcanvas.innerHTML = result.data.sidebar_html;
                        }
                    } else {
                        const errorMsg = (result.data && result.data.message) ? result.data.message : 'Gagal memproses keranjang.';
                        alert(errorMsg);
                    }
                })
                .catch(function(err) {
                    console.error('AJAX cart error:', err);
                    form.setAttribute('data-no-ajax', 'true');
                    form.submit();
                })
                .finally(function() {
                    if (submitBtn && !submitBtn.innerHTML.includes('Ditambahkan')) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    }
                });
            });

            // 3. Wishlist Management (LocalStorage Reactive Synchronizer)
            const WISHLIST_STORAGE_KEY = 'bladestore_wishlist_ids';
            function getWishlistIds() {
                try {
                    return JSON.parse(localStorage.getItem(WISHLIST_STORAGE_KEY) || '[]');
                } catch (e) {
                    return [];
                }
            }

            function saveWishlistIds(ids) {
                try {
                    localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(ids));
                } catch (e) {}
                updateWishlistBadges();
            }

            function updateWishlistBadges() {
                const ids = getWishlistIds();
                const count = ids.length;
                document.querySelectorAll('#wishlist-badge-count, #wishlist-badge-count-drawer, .nav-wishlist-badge').forEach(function(badge) {
                    badge.textContent = count;
                    if (count > 0) {
                        badge.classList.remove('d-none');
                        badge.style.display = 'inline-flex';
                    } else {
                        badge.classList.add('d-none');
                        badge.style.display = 'none';
                    }
                });
            }

            window.syncWishlistCardButtons = function() {
                const ids = getWishlistIds();
                document.querySelectorAll('.btn-product-wishlist').forEach(function(btn) {
                    const pid = parseInt(btn.getAttribute('data-product-id'), 10);
                    const icon = btn.querySelector('i');
                    if (ids.includes(pid)) {
                        btn.classList.add('active');
                        if (icon) icon.className = 'bi bi-heart-fill text-danger';
                    } else {
                        btn.classList.remove('active');
                        if (icon) icon.className = 'bi bi-heart';
                    }
                });
                updateWishlistBadges();
            };

            document.addEventListener('DOMContentLoaded', function() {
                window.syncWishlistCardButtons();

                // Delegated click for wishlist buttons
                document.addEventListener('click', function(e) {
                    const btn = e.target.closest('.btn-product-wishlist');
                    if (!btn) return;
                    e.preventDefault();
                    e.stopPropagation();

                    const pid = parseInt(btn.getAttribute('data-product-id'), 10);
                    const name = btn.getAttribute('data-product-name') || 'Produk';
                    let ids = getWishlistIds();

                    if (ids.includes(pid)) {
                        ids = ids.filter(item => item !== pid);
                        saveWishlistIds(ids);
                        window.syncWishlistCardButtons();
                        window.showCartToast(name + ' dihapus dari Favorit');
                    } else {
                        ids.push(pid);
                        saveWishlistIds(ids);
                        window.syncWishlistCardButtons();
                        window.showCartToast(name + ' ditambahkan ke Favorit');
                    }
                });

                // Navbar & Drawer Wishlist Icon Click: filter catalog to show only wishlisted items
                const navWishlistTrigger = document.getElementById('nav-wishlist-trigger');
                const drawerWishlistTrigger = document.getElementById('mobile-drawer-wishlist-trigger');
                
                function handleWishlistNavClick(e) {
                    const catalogEl = document.getElementById('katalog-produk');
                    if (catalogEl) {
                        e.preventDefault();
                        catalogEl.scrollIntoView({ behavior: 'smooth' });
                        const ids = getWishlistIds();
                        const cols = document.querySelectorAll('.product-grid-col');
                        if (cols.length > 0) {
                            if (ids.length === 0) {
                                window.showCartToast('Wishlist Anda masih kosong. Klik ikon hati pada produk.');
                                return;
                            }
                            let activeCount = 0;
                            cols.forEach(function(col) {
                                const pid = parseInt(col.getAttribute('data-product-id'), 10);
                                if (ids.includes(pid)) {
                                    col.style.display = '';
                                    activeCount++;
                                } else {
                                    col.style.display = 'none';
                                }
                            });
                            window.showCartToast('Menampilkan ' + activeCount + ' produk favorit di katalog');
                        }
                    }
                }

                if (navWishlistTrigger) {
                    navWishlistTrigger.addEventListener('click', handleWishlistNavClick);
                }
                if (drawerWishlistTrigger) {
                    drawerWishlistTrigger.addEventListener('click', handleWishlistNavClick);
                }
            });

            // 3. Sinkronisasi sidebar saat dibuka
            const cartOffcanvasEl = document.getElementById('cartOffcanvas');
            if (cartOffcanvasEl) {
                cartOffcanvasEl.addEventListener('show.bs.offcanvas', function() {
                    @if(Auth::check())
                    fetch('{{ route("cart.sidebar") }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) { return res.ok ? res.json() : null; })
                    .then(function(data) {
                        if (data && data.sidebar_html) {
                            cartOffcanvasEl.innerHTML = data.sidebar_html;
                        }
                        if (data && typeof data.cart_count !== 'undefined') {
                            const cartCount = parseInt(data.cart_count, 10);
                            const badgeEl = document.getElementById('cart-badge-count');
                            if (badgeEl) {
                                badgeEl.innerText = data.cart_count;
                                if (cartCount > 0) {
                                    badgeEl.classList.remove('d-none');
                                    badgeEl.style.display = 'inline-flex';
                                } else {
                                    badgeEl.classList.add('d-none');
                                    badgeEl.style.display = 'none';
                                }
                            }

                            document.querySelectorAll('.nav-cart-badge, .mobile-cart-badge, #cart-badge-count-mobile, #cart-badge-count-drawer').forEach(function(badge) {
                                badge.innerText = data.cart_count;
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
                        }
                    })
                    .catch(function(e) {
                        console.error('Error fetching sidebar:', e);
                    });
                    @endif
                });
            }
        })();
    </script>
    @stack('scripts')
</body>
</html>
