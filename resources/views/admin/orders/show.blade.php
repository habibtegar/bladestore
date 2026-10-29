@extends('layouts.admin')

@section('title', 'Kelola Pesanan #' . $order->order_code)
@section('page-title', 'Detail Pesanan #' . $order->order_code)

@section('content')
<!-- Breadcrumbs -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted">Admin Pesanan</a></li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">{{ $order->order_code }}</li>
    </ol>
</nav>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-dark small">Rincian Transaksi</span>
            <h3 class="fw-bold text-dark mb-0 font-monospace">{{ $order->order_code }}</h3>
        </div>
        <p class="text-muted small mb-0">Dibuat pada {{ $order->created_at->format('d F Y, H:i') }} WIB</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge {{ $order->status_badge_class }} px-3 py-2 small">
            {{ $order->status_label }}
        </span>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Left Column: Order Items & Customer/Shipping Information -->
    <div class="col-lg-8">
        @if($order->tracking_number)
            <div class="card border-0 p-3.5 mb-4 rounded-3 shadow-sm bg-primary-subtle text-primary border-primary-subtle">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 42px; height: 42px; font-size: 1.25rem;">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <span class="x-small text-muted d-block text-uppercase fw-bold">Nomor Resi Pengiriman</span>
                            <span class="fw-bold font-monospace fs-5 text-dark">{{ $order->tracking_number }}</span>
                        </div>
                    </div>
                    <span class="badge bg-primary text-white px-3 py-2 rounded-pill small">
                        <i class="bi bi-check2-circle me-1"></i> Paket Dalam Pengiriman
                    </span>
                </div>
            </div>
        @endif

        <!-- Rincian Barang yang Dibeli -->
        <div class="card border-0 bg-white p-4 mb-4 rounded-3 shadow-sm">
            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-basket2 text-primary"></i> Daftar Produk Pesanan
            </h5>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-secondary">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Kuantitas</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 48px; height: 62px; flex-shrink: 0; background-color: #F3F4F6; border-radius: 6px; overflow: hidden;" class="border">
                                            <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                                 alt="{{ $item->product->name ?? 'Produk' }}"
                                                 class="w-100 h-100 object-fit-cover"
                                                 onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">{{ $item->product->name ?? 'Produk telah dihapus' }}</div>
                                            <div class="x-small text-muted">
                                                ID: #{{ $item->product_id }}
                                                @if($item->product)
                                                    &bull; Sisa Stok Gudang: <strong>{{ $item->product->stock }} unit</strong>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center text-nowrap small text-secondary">
                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                </td>
                                <td class="text-center fw-semibold small">
                                    {{ $item->quantity }}x
                                </td>
                                <td class="text-end fw-bold text-primary text-nowrap small">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Subtotal Produk:</td>
                            <td class="text-end fw-semibold small">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Ongkos Kirim:</td>
                            <td class="text-end text-success fw-bold small">Gratis</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Total Transaksi:</td>
                            <td class="text-end fw-bold fs-5 text-primary">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Informasi Pengiriman & Customer -->
        <div class="card border-0 bg-white p-4 rounded-3 shadow-sm">
            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt text-primary"></i> Data Pembeli & Tujuan Pengiriman
            </h5>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <span class="text-muted x-small d-block mb-1">Nama Akun Pembeli:</span>
                        <div class="fw-bold text-dark small">{{ $order->user->name ?? 'Pengguna Dihapus' }}</div>
                        <div class="x-small text-muted">{{ $order->user->email ?? '-' }}</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-3 h-100">
                        <span class="text-muted x-small d-block mb-1">Nomor WhatsApp / HP:</span>
                        <div class="fw-bold text-dark font-monospace small">{{ $order->customer_phone ?: '-' }}</div>
                        @if($order->customer_phone)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->customer_phone)) }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="x-small text-success text-decoration-none mt-1 d-inline-block fw-semibold">
                                <i class="bi bi-whatsapp"></i> Hubungi Pembeli via WhatsApp
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-12">
                    <div class="p-3 bg-light rounded-3">
                        <span class="text-muted x-small d-block mb-1">Alamat Lengkap Pengiriman:</span>
                        <div class="text-dark small">{{ $order->shipping_address ?: 'Tidak ada alamat tercatat.' }}</div>
                    </div>
                </div>

                @if($order->notes)
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted x-small d-block mb-1">Catatan Tambahan dari Pembeli:</span>
                            <div class="fst-italic text-secondary small">{{ $order->notes }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Status & Tracking Update Form -->
    <div class="col-lg-4">
        <!-- Form Dropdown Perubahan Status & Nomor Resi Pesanan -->
        <div class="card border-0 bg-white p-4 mb-4 sticky-top rounded-3 shadow-sm" style="top: 85px;">
            <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                <i class="bi bi-truck text-primary"></i> Update Resi & Status
            </h5>

            @if(in_array($order->status, ['pending', 'belum_bayar']))
                <div class="alert alert-warning py-2.5 px-3 small border-0 mb-3 d-flex align-items-center gap-2 rounded-2">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-6 flex-shrink-0"></i>
                    <span style="font-size: 0.82rem;">Pesanan masih <strong>Belum Bayar</strong>. Form input resi dinonaktifkan hingga status berubah menjadi Dikemas (pembayaran diverifikasi).</span>
                </div>
            @endif

            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Nomor Resi Pengiriman -->
                <div class="mb-3">
                    <label for="tracking_number" class="form-label small fw-semibold text-secondary">
                        Nomor Resi Pengiriman:
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="bi bi-upc-scan"></i></span>
                        <input type="text"
                               name="tracking_number"
                               id="tracking_number"
                               class="form-control @error('tracking_number') is-invalid @enderror"
                               placeholder="{{ in_array($order->status, ['pending', 'belum_bayar']) ? 'Menunggu pembayaran selesai...' : 'Contoh: JNE882910291, SICEPAT992' }}"
                               value="{{ old('tracking_number', $order->tracking_number) }}"
                               {{ in_array($order->status, ['pending', 'belum_bayar']) ? 'disabled' : '' }}>
                    </div>
                    @error('tracking_number')
                        <div class="text-danger x-small mt-1">{{ $message }}</div>
                    @enderror
                    <div class="text-muted mt-1.5" style="font-size: 0.78rem;">
                        @if(in_array($order->status, ['pending', 'belum_bayar']))
                            <span class="text-warning fw-semibold"><i class="bi bi-lock me-1"></i> Resi hanya dapat diinput setelah status Dikemas.</span>
                        @else
                            <i class="bi bi-info-circle me-1 text-primary"></i> Menyimpan nomor resi otomatis mengubah status menjadi <strong>Dikirim</strong>.
                        @endif
                    </div>
                </div>

                <!-- Status Pesanan -->
                <div class="mb-3">
                    <label for="status" class="form-label small fw-semibold text-secondary">Pilih Status Baru (Manual):</label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending" {{ in_array($order->status, ['pending', 'belum_bayar']) ? 'selected' : '' }}>
                            Belum Bayar
                        </option>
                        <option value="processing" {{ in_array($order->status, ['processing', 'dikemas']) ? 'selected' : '' }}>
                            Dikemas (Sudah Dibayar)
                        </option>
                        @if(!in_array($order->status, ['pending', 'belum_bayar']))
                            <option value="shipped" {{ in_array($order->status, ['shipped', 'dikirim']) ? 'selected' : '' }}>
                                Dikirim
                            </option>
                        @endif
                        <option value="completed" {{ in_array($order->status, ['completed', 'selesai']) ? 'selected' : '' }}>
                            Selesai
                        </option>
                        <option value="cancelled" {{ in_array($order->status, ['cancelled', 'dibatalkan']) ? 'selected' : '' }}>
                            Dibatalkan
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary py-2 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i> Simpan Resi dan Status
                    </button>
                </div>
            </form>

            <hr class="my-3">

            <!-- Detail Metode Pembayaran -->
            <div class="mb-3">
                <span class="x-small text-muted d-block">Metode Pembayaran:</span>
                <span class="fw-bold text-dark small">{{ $order->payment_method }}</span>
            </div>

            <div class="p-3 bg-light rounded-3 x-small text-muted">
                <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle me-1 text-primary"></i> Alur Bisnis Pesanan:</div>
                <ul class="mb-0 ps-3">
                    <li><strong>Belum Bayar:</strong> Menunggu konfirmasi transfer bank / Snap Midtrans.</li>
                    <li><strong>Dikemas:</strong> Pembayaran valid, barang dalam penyiapan gudang.</li>
                    <li><strong>Dikirim:</strong> Paket diserahkan ke jasa kurir dengan nomor resi aktif.</li>
                    <li><strong>Selesai:</strong> Paket diterima pembeli dengan baik.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
