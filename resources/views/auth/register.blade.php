@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@push('styles')
<style>
    /* ==========================================
       SPLIT-SCREEN AUTH CONTAINER (BLADESTORE)
       ========================================== */
    .auth-container {
        max-width: 1000px;
        margin: 2rem auto 3rem;
        background-color: #FFFFFF;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
        box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.08), 0 2px 8px -2px rgba(15, 23, 42, 0.04);
    }

    /* Left Branding Column */
    .auth-brand-panel {
        background: linear-gradient(150deg, #0F172A 0%, #1E3A8A 50%, #0866FF 100%);
        padding: 3rem 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #FFFFFF;
        position: relative;
    }

    .auth-brand-logo-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        color: #FFFFFF;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .auth-benefit-item {
        display: flex;
        align-items: flex-start;
        gap: 0.9rem;
        padding: 0.85rem 1rem;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        transition: background-color 0.2s ease, transform 0.2s ease;
    }

    .auth-benefit-item:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateX(3px);
    }

    .auth-benefit-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.16);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* Right Form Column */
    .auth-form-panel {
        padding: 3rem 2.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #FFFFFF;
    }

    @media (min-width: 992px) {
        .auth-form-panel {
            padding: 3.5rem 3.25rem;
        }
    }

    .auth-form-wrapper {
        max-width: 400px;
        width: 100%;
        margin: 0 auto;
    }

    .auth-input-group .input-group-text {
        background-color: #F8FAFC;
        border-color: #CBD5E1;
        color: #64748B;
        font-size: 1rem;
        padding-left: 0.85rem;
        padding-right: 0.85rem;
    }

    .auth-input-group .form-control {
        border-color: #CBD5E1;
        padding: 0.65rem 0.85rem;
        font-size: 0.925rem;
        color: #1E293B;
    }

    .auth-input-group .form-control:focus {
        border-color: #0866FF;
        box-shadow: 0 0 0 3px rgba(8, 102, 255, 0.15);
    }

    .auth-input-group .btn-toggle-pw {
        border-color: #CBD5E1;
        background-color: #FFFFFF;
        color: #64748B;
    }

    .auth-input-group .btn-toggle-pw:hover {
        background-color: #F8FAFC;
        color: #0866FF;
    }

    .btn-auth-primary {
        background: linear-gradient(135deg, #0866FF 0%, #1D4ED8 100%);
        border: none;
        color: #FFFFFF;
        padding: 0.72rem 1.25rem;
        font-weight: 700;
        font-size: 0.95rem;
        border-radius: 10px;
        transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
    }

    .btn-auth-primary:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(8, 102, 255, 0.28);
        color: #FFFFFF;
    }

    .btn-auth-primary:active {
        transform: translateY(0);
    }

    /* Responsivitas Mobile */
    @media (max-width: 767.98px) {
        .auth-container {
            margin: 1rem 0 2rem;
            border-radius: 16px;
        }

        .auth-brand-panel {
            padding: 2rem 1.5rem;
            text-align: center;
            align-items: center;
        }

        .auth-brand-panel .auth-benefit-list,
        .auth-brand-panel .auth-brand-footer {
            display: none !important;
        }

        .auth-brand-panel .auth-brand-heading {
            margin-bottom: 0 !important;
        }

        .auth-form-panel {
            padding: 2rem 1.25rem 2.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-11">
        
        <div class="auth-container">
            <div class="row g-0">
                
                <!-- ==========================================
                     KOLOM KIRI: PANEL BRANDING
                     ========================================== -->
                <div class="col-md-5 auth-brand-panel">
                    
                    <!-- Top Logo Brand -->
                    <div class="d-flex align-items-center gap-2.5 mb-3 mb-md-4">
                        <div class="auth-brand-logo-icon">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold text-white tracking-tight" style="line-height: 1;">
                                BLADE<span style="color: #93C5FD;">STORE</span>
                            </div>
                            <span class="x-small text-white text-opacity-75">Registrasi Pelanggan</span>
                        </div>
                    </div>

                    <!-- Center Heading & 3 Keunggulan -->
                    <div class="my-auto py-2 auth-brand-heading">
                        <h2 class="fw-bold text-white mb-2" style="font-size: clamp(1.4rem, 2vw, 1.8rem); letter-spacing: -0.02em;">
                            Mulai Berbelanja Bersama Kami
                        </h2>
                        <p class="text-white text-opacity-80 small mb-4" style="line-height: 1.6;">
                            Daftar akun gratis dan nikmati kemudahan bertransaksi, jaminan mutu produk resmi, serta penawaran hemat setiap hari.
                        </p>

                        <!-- 3 Keunggulan dengan Ikon Sederhana -->
                        <div class="auth-benefit-list d-flex flex-column gap-2.5">
                            <div class="auth-benefit-item">
                                <div class="auth-benefit-icon text-warning">
                                    <i class="bi bi-lightning-charge-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-white small" style="line-height: 1.25;">Registrasi Cepat & Praktis</div>
                                    <div class="text-white text-opacity-65 x-small mt-0.5">Buat akun dalam hitungan detik tanpa syarat rumit</div>
                                </div>
                            </div>

                            <div class="auth-benefit-item">
                                <div class="auth-benefit-icon text-info">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-white small" style="line-height: 1.25;">Perlindungan Transaksi Aman</div>
                                    <div class="text-white text-opacity-65 x-small mt-0.5">Jaminan keamanan data pribadi dan verifikasi pembayaran resmi</div>
                                </div>
                            </div>

                            <div class="auth-benefit-item">
                                <div class="auth-benefit-icon text-success">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-white small" style="line-height: 1.25;">Lacak Pengiriman Real-Time</div>
                                    <div class="text-white text-opacity-65 x-small mt-0.5">Pantau status pesanan dan resi pengiriman kurir kapan saja</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Copyright -->
                    <div class="auth-brand-footer pt-3 border-top border-white border-opacity-15 text-white text-opacity-60 x-small d-none d-md-block">
                        &copy; {{ date('Y') }} BladeStore Marketplace. Hak Cipta Dilindungi.
                    </div>
                </div>

                <!-- ==========================================
                     KOLOM KANAN: FORMULIR REGISTER
                     ========================================== -->
                <div class="col-md-7 auth-form-panel">
                    <div class="auth-form-wrapper">
                        
                        <!-- Header Form -->
                        <div class="mb-4">
                            <span class="text-primary fw-bold x-small text-uppercase tracking-wider">Pendaftaran Baru</span>
                            <h1 class="fw-bold text-dark mb-1" style="font-size: 1.6rem; letter-spacing: -0.02em;">Daftar Akun Baru</h1>
                            <p class="text-muted small mb-0">Isi data di bawah ini untuk membuat akun belanja Anda.</p>
                        </div>

                        <!-- Register Form -->
                        <form action="{{ route('register') }}" method="POST" id="registerForm">
                            @csrf
                            
                            <!-- 1. Input Nama Lengkap -->
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-semibold text-dark mb-1.5">Nama Lengkap</label>
                                <div class="input-group auth-input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           placeholder="Nama Lengkap Anda"
                                           value="{{ old('name') }}"
                                           required
                                           autofocus>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 2. Input Email -->
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

                            <!-- 3. Input Kata Sandi -->
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
                                    <button class="btn btn-toggle-pw border-start-0" type="button" onclick="togglePasswordVisibility('password', 'togglePasswordIcon1')" aria-label="Lihat kata sandi">
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
                                    <button class="btn btn-toggle-pw border-start-0" type="button" onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordIcon2')" aria-label="Lihat konfirmasi kata sandi">
                                        <i class="bi bi-eye" id="togglePasswordIcon2"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- 5. Tombol Submit: Daftar Akun Sekarang -->
                            <button type="submit" class="btn btn-auth-primary w-100 d-flex align-items-center justify-content-center gap-2 mb-3 shadow-sm">
                                <span>Daftar Akun Sekarang</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>

                            <!-- 6. Link Masuk ke Akun -->
                            <div class="text-center small text-muted pt-2">
                                Sudah memiliki akun? <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold">Masuk di Sini</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

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
