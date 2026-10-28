@extends('layouts.app')

@section('title', 'Daftar Akun Baru — BladeStore Marketplace')

@push('styles')
<style>
    /* ==========================================
       SPLIT-SCREEN AUTH LAYOUT (MODERN TWO-COLUMN)
       ========================================== */
    .auth-split-wrapper {
        display: grid;
        grid-template-columns: 1fr;
        min-height: calc(100vh - 140px);
        max-width: 1060px;
        margin: 1rem auto 2.5rem;
        background-color: #FFFFFF;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--border-color, #E2E8F0);
        box-shadow: 0 16px 40px -8px rgba(15, 23, 42, 0.08), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
    }

    .grid-cols-1 {
        display: grid;
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }

    @media (min-width: 768px) {
        .auth-split-wrapper,
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            min-height: 640px;
        }
    }

    /* Kolom Kiri: Visual & Branding */
    .auth-visual-column {
        background: linear-gradient(145deg, #0B1930 0%, #0866FF 55%, #0248BA 100%);
        position: relative;
        overflow: hidden;
        padding: 3rem 2.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #FFFFFF;
    }

    .auth-visual-glow-1 {
        position: absolute;
        top: -60px;
        right: -60px;
        width: 240px;
        height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(234, 88, 12, 0.35) 0%, transparent 70%);
        pointer-events: none;
    }

    .auth-visual-glow-2 {
        position: absolute;
        bottom: -70px;
        left: -50px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(96, 165, 250, 0.3) 0%, transparent 70%);
        pointer-events: none;
    }

    .auth-brand-badge {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.16);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.28);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #FFFFFF;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .auth-visual-title {
        font-size: clamp(1.6rem, 2.4vw, 2.1rem);
        letter-spacing: -0.025em;
        line-height: 1.25;
    }

    .auth-visual-feature {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .auth-visual-feature:hover {
        background: rgba(255, 255, 255, 0.14);
        transform: translateX(4px);
    }

    /* Kolom Kanan: Area Formulir */
    .auth-form-column {
        background-color: #FFFFFF;
        padding: 2.75rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (min-width: 992px) {
        .auth-form-column {
            padding: 3.5rem 3.25rem;
        }
    }

    .auth-form-inner {
        max-width: 410px;
        width: 100%;
        margin: 0 auto;
    }

    .auth-input-group .input-group-text {
        border-color: #CBD5E1;
        background-color: #F8FAFC;
        color: #64748B;
    }

    .auth-input-group .form-control {
        border-color: #CBD5E1;
        padding-top: 0.65rem;
        padding-bottom: 0.65rem;
        font-size: 0.925rem;
    }

    .auth-input-group .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(8, 102, 255, 0.15);
    }

    .w-full {
        width: 100%;
    }

    .min-h-screen {
        min-height: calc(100vh - 140px);
    }
</style>
@endpush

@section('content')
<div class="auth-split-wrapper grid-cols-1 md:grid-cols-2 min-h-screen">
    
    <!-- ==========================================
         KOLOM KIRI: VISUAL & BRANDING
         ========================================== -->
    <div class="auth-visual-column">
        <!-- Glow Orbs -->
        <div class="auth-visual-glow-1"></div>
        <div class="auth-visual-glow-2"></div>

        <!-- Top Header & Logo Brand -->
        <div class="position-relative z-1 mb-4">
            <div class="d-flex align-items-center gap-2.5 mb-3">
                <div class="auth-brand-badge">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <div>
                    <div class="fs-4 fw-bold text-white tracking-tight leading-none">
                        BLADE<span style="color: #93C5FD;">STORE</span>
                    </div>
                    <span class="badge bg-white bg-opacity-20 text-white x-small rounded-pill mt-1">Registrasi Pembeli</span>
                </div>
            </div>
        </div>

        <!-- Center Welcome Text & Hero Message -->
        <div class="position-relative z-1 my-auto py-3">
            <span class="badge bg-white bg-opacity-15 text-white border border-white border-opacity-25 rounded-pill px-3 py-1.5 x-small fw-semibold mb-3 d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-gift-fill text-warning"></i> Keuntungan Akun Baru
            </span>
            <h1 class="auth-visual-title fw-bold text-white mb-2.5">
                Mulai Perjalanan Belanja Anda
            </h1>
            <p class="text-white text-opacity-80 small mb-4 leading-relaxed" style="max-width: 420px;">
                Daftar akun gratis dan nikmati kemudahan transaksi, pelacakan pengiriman instan, serta promo hemat multi-kategori setiap hari.
            </p>

            <!-- 3 Trust Chips (Visual Highlights) -->
            <div class="d-flex flex-column gap-2.5">
                <div class="d-flex align-items-center gap-3 p-2.5 rounded-3 auth-visual-feature">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-20 text-warning flex-shrink-0" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="bi bi-bag-check-fill"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-white small" style="line-height: 1.2;">Gratis & Cepat</div>
                        <div class="text-white text-opacity-60 x-small">Registrasi instan tanpa syarat rumit</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 p-2.5 rounded-3 auth-visual-feature">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-20 text-info flex-shrink-0" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-white small" style="line-height: 1.2;">Garansi Produk Terjamin</div>
                        <div class="text-white text-opacity-60 x-small">Jaminan retur jika pesanan tidak sesuai</div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 p-2.5 rounded-3 auth-visual-feature">
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-white bg-opacity-20 text-success flex-shrink-0" style="width: 36px; height: 36px; font-size: 1.1rem;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div>
                        <div class="fw-bold text-white small" style="line-height: 1.2;">Lacak Pesanan Real-Time</div>
                        <div class="text-white text-opacity-60 x-small">Pantau status pesanan kapan saja</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Quote -->
        <div class="position-relative z-1 pt-3 border-top border-white border-opacity-15 d-none d-md-block text-white text-opacity-70 x-small">
            &copy; 2026 BladeStore Marketplace. Belanja cerdas, aman, dan nyaman.
        </div>
    </div>

    <!-- ==========================================
         KOLOM KANAN: AREA FORMULIR REGISTER
         ========================================== -->
    <div class="auth-form-column flex flex-col justify-center">
        <div class="auth-form-inner">
            
            <!-- Header Form -->
            <div class="mb-4">
                <span class="text-primary fw-bold x-small text-uppercase tracking-wider">Pendaftaran Pelanggan</span>
                <h2 class="fw-bold text-dark mb-1" style="font-size: 1.55rem; letter-spacing: -0.02em;">Daftar Akun Baru</h2>
                <p class="text-muted small mb-0">Isi data di bawah ini untuk membuat akun belanja Anda.</p>
            </div>

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <!-- 1. Nama Lengkap -->
                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-dark mb-1.5">Nama Lengkap</label>
                    <div class="input-group auth-input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Contoh: Budi Pratama"
                               value="{{ old('name') }}"
                               required
                               autofocus>
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 2. Alamat Email -->
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-dark mb-1.5">Alamat Email</label>
                    <div class="input-group auth-input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="nama@email.com"
                               value="{{ old('email') }}"
                               required>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 3. Kata Sandi -->
                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-dark mb-1.5">Kata Sandi (Min. 6 Karakter)</label>
                    <div class="input-group auth-input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-control border-end-0 @error('password') is-invalid @enderror"
                               placeholder="Minimal 6 karakter"
                               required>
                        <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" onclick="togglePasswordVisibility('password', 'togglePasswordIcon1')" aria-label="Lihat kata sandi">
                            <i class="bi bi-eye" id="togglePasswordIcon1"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 4. Konfirmasi Kata Sandi -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-semibold text-dark mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="input-group auth-input-group">
                        <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="form-control border-end-0"
                               placeholder="Ulangi kata sandi"
                               required>
                        <button class="btn btn-outline-secondary border-start-0 bg-white text-muted" type="button" onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordIcon2')" aria-label="Lihat konfirmasi kata sandi">
                            <i class="bi bi-eye" id="togglePasswordIcon2"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button: Daftar Sebagai Pelanggan (w-full / w-100 sejajar rapi) -->
                <button type="submit" class="btn btn-primary w-full w-100 py-2.5 fw-bold mb-3 d-flex align-items-center justify-content-center gap-2 rounded-3 shadow-sm">
                    <span>Daftar Sebagai Pelanggan</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

                <!-- Link to Login -->
                <div class="text-center small text-muted pt-1">
                    Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Masuk di Sini</a>
                </div>
            </form>

        </div>
    </div>

</div>

@push('scripts')
<script>
    function togglePasswordVisibility(fieldId, iconId) {
        const passwordInput = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    }
</script>
@endpush
@endsection
