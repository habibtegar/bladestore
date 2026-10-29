@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<!-- Header Summary Banner -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Daftar Transaksi Pesanan</h4>
        <p class="text-muted small mb-0">Kelola status pemesanan, verifikasi pembayaran, input nomor resi pengiriman, dan lacak pesanan pelanggan.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
        </a>
    </div>
</div>

<!-- Summary Status Metric Cards (Real DB Data) -->
<div class="row g-3 mb-4">
    <!-- All Orders -->
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ empty($status) ? 'border-primary shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Semua Pesanan</span>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-dark mb-0">{{ $statusCounts['all'] ?? 0 }}</div>
                <div class="text-muted x-small mt-1">Total riwayat transaksi</div>
            </div>
        </a>
    </div>

    <!-- Belum Bayar (Pending) -->
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ $status === 'pending' ? 'border-warning shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Belum Bayar</span>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-warning mb-0">{{ $statusCounts['pending'] ?? 0 }}</div>
                <div class="text-muted x-small mt-1">Menunggu bayar</div>
            </div>
        </a>
    </div>

    <!-- Dikemas (Processing) -->
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ $status === 'processing' ? 'border-info shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Dikemas</span>
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-info mb-0">{{ $statusCounts['processing'] ?? 0 }}</div>
                <div class="text-muted x-small mt-1">Siap dikirim / packing</div>
            </div>
        </a>
    </div>

    <!-- Dikirim (Shipped) -->
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ $status === 'shipped' ? 'border-primary shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Dikirim</span>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-primary mb-0">{{ $statusCounts['shipped'] ?? 0 }}</div>
                <div class="text-muted x-small mt-1">Sedang dalam kurir</div>
            </div>
        </a>
    </div>

    <!-- Selesai (Completed) -->
    <div class="col-12 col-md-4 col-xl">
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ $status === 'completed' ? 'border-success shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Selesai</span>
                    <div class="stat-icon bg-success-subtle text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-success mb-0">{{ $statusCounts['completed'] ?? 0 }}</div>
                <div class="text-muted x-small mt-1">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
            </div>
        </a>
    </div>
</div>

<!-- Filter and Search Bar -->
<div class="card border-0 bg-white p-3 mb-4 rounded-3 shadow-sm">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
        <!-- Search Field -->
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control border-start-0"
                       placeholder="Cari Order ID, nama pelanggan, no. resi, telepon..."
                       value="{{ $search ?? '' }}">
            </div>
        </div>

        <!-- Filter Status Dropdown -->
        <div class="col-md-4">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status Pesanan</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Dikemas</option>
                <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Dikirim</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <!-- Submit & Reset Buttons -->
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                <i class="bi bi-funnel me-1"></i> Filter
            </button>
            @if($search || $status)
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Table Daftar Pesanan Admin -->
<div class="card border-0 bg-white rounded-3 shadow-sm mb-4">
    <div class="table-responsive">
        <table class="table table-admin align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" style="white-space: nowrap; width: 170px;">Order ID</th>
                    <th scope="col">Nama Pelanggan</th>
                    <th scope="col" class="text-end" style="white-space: nowrap; width: 140px;">Total Harga</th>
                    <th scope="col" style="white-space: nowrap; width: 150px;">Tanggal Pesanan</th>
                    <th scope="col" class="text-center" style="white-space: nowrap; width: 140px;">Status Pesanan</th>
                    <th scope="col" style="white-space: nowrap; width: 150px;">Nomor Resi</th>
                    <th scope="col" class="text-center" style="white-space: nowrap; width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <!-- 1. Order ID -->
                        <td>
                            <div class="fw-bold font-monospace text-dark">{{ $order->order_code }}</div>
                            <div class="x-small text-muted">
                                <span class="badge bg-light text-secondary border">
                                    {{ $order->payment_method }}
                                </span>
                            </div>
                        </td>

                        <!-- 2. Nama Pelanggan -->
                        <td>
                            <div class="fw-semibold text-dark small text-truncate" style="max-width: 180px;">{{ $order->user->name ?? 'Pelanggan' }}</div>
                            <div class="x-small text-muted text-truncate" style="max-width: 180px;">{{ $order->user->email ?? '-' }}</div>
                            @if($order->customer_phone)
                                <div class="x-small">
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->customer_phone)) }}" target="_blank" rel="noopener noreferrer" class="text-success text-decoration-none">
                                        <i class="bi bi-whatsapp"></i> {{ $order->customer_phone }}
                                    </a>
                                </div>
                            @endif
                        </td>

                        <!-- 3. Total Harga -->
                        <td class="text-end fw-bold text-primary text-nowrap">
                            {{ $order->formatted_total_price }}
                        </td>

                        <!-- 4. Tanggal Pesanan -->
                        <td style="white-space: nowrap;">
                            <div class="small text-dark">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="x-small text-muted">{{ $order->created_at->format('H:i') }} WIB</div>
                        </td>

                        <!-- 5. Status Pesanan -->
                        <td class="text-center" style="white-space: nowrap;">
                            @if(in_array($order->status, ['pending', 'belum_bayar']))
                                <span class="badge bg-warning text-dark px-2.5 py-1.5 rounded-pill small">
                                    <i class="bi bi-clock-history me-1"></i> Belum Bayar
                                </span>
                            @elseif(in_array($order->status, ['processing', 'dikemas']))
                                <span class="badge bg-info text-dark px-2.5 py-1.5 rounded-pill small">
                                    <i class="bi bi-box-seam me-1"></i> Dikemas
                                </span>
                            @elseif(in_array($order->status, ['shipped', 'dikirim']))
                                <span class="badge bg-primary text-white px-2.5 py-1.5 rounded-pill small">
                                    <i class="bi bi-truck me-1"></i> Dikirim
                                </span>
                            @elseif(in_array($order->status, ['completed', 'selesai']))
                                <span class="badge bg-success text-white px-2.5 py-1.5 rounded-pill small">
                                    <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                </span>
                            @else
                                <span class="badge bg-danger text-white px-2.5 py-1.5 rounded-pill small">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- 6. Nomor Resi -->
                        <td style="white-space: nowrap;">
                            @if($order->tracking_number)
                                <span class="font-monospace fw-bold small text-dark d-block">
                                    {{ $order->tracking_number }}
                                </span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle x-small">
                                    <i class="bi bi-check2"></i> Terinput
                                </span>
                            @else
                                <span class="text-muted fst-italic small">Belum ada resi</span>
                            @endif
                        </td>

                        <!-- 7. Aksi -->
                        <td class="text-center" style="white-space: nowrap;">
                            <div class="d-flex justify-content-center gap-1.5">
                                <!-- Tombol Modal Detail & Resi -->
                                <button type="button" 
                                        class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 py-1 px-2.5 small"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#orderDetailModal{{ $order->id }}"
                                        title="Buka Modal Detail & Update Resi">
                                    <i class="bi bi-box-seam"></i>
                                    <span>Detail & Resi</span>
                                </button>
                                
                                <!-- Link ke Halaman Penuh -->
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="btn btn-sm btn-outline-secondary py-1 px-2 small"
                                   title="Buka Halaman Detail Penuh">
                                    <i class="bi bi-arrow-up-right-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Tidak ada data pesanan yang sesuai dengan filter atau kata kunci pencarian.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- ==========================================
     MODALS SECTION (OUTSIDE OF TABLE)
     ========================================== -->
@foreach($orders as $order)
    <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                
                <!-- Modal Header -->
                <div class="modal-header bg-light py-3">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-dark font-monospace mb-0" id="orderDetailModalLabel{{ $order->id }}">
                                #{{ $order->order_code }}
                            </h5>
                            <div class="x-small text-muted">
                                Dipesan pada {{ $order->created_at->format('d F Y, H:i') }} WIB
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $order->status_badge_class }} px-2.5 py-1.5 rounded-pill small">
                            {{ $order->status_label }}
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4">
                    
                    <!-- 1. Daftar Produk yang Dibeli -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-2.5 d-flex align-items-center gap-2">
                            <i class="bi bi-basket2 text-primary"></i> Daftar Produk yang Dibeli
                        </h6>
                        <div class="table-responsive border rounded-3 overflow-hidden">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light x-small text-secondary">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Harga Satuan</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2.5">
                                                    <div style="width: 40px; height: 40px; border-radius: 6px; overflow: hidden; background-color: #F8FAFC;" class="border flex-shrink-0">
                                                        <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                                             alt="{{ $item->product->name ?? 'Produk' }}"
                                                             class="w-100 h-100 object-fit-cover"
                                                             onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                                    </div>
                                                    <div class="text-truncate">
                                                        <div class="fw-semibold text-dark small text-truncate" style="max-width: 280px;">
                                                            {{ $item->product->name ?? 'Produk telah dihapus' }}
                                                        </div>
                                                        <div class="x-small text-muted">ID: #{{ $item->product_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center text-nowrap small text-secondary">
                                                Rp {{ number_format((float)$item->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center fw-semibold small">
                                                {{ $item->quantity }}x
                                            </td>
                                            <td class="text-end fw-bold text-primary text-nowrap small">
                                                Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light border-top">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold small">Total Pembayaran:</td>
                                        <td class="text-end fw-bold fs-6 text-primary">{{ $order->formatted_total_price }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- 2. Alamat Pengiriman & Data Pelanggan -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <span class="x-small text-muted d-block fw-semibold mb-1">
                                    <i class="bi bi-person me-1"></i> Data Pembeli:
                                </span>
                                <div class="fw-bold text-dark small">{{ $order->user->name ?? 'Pelanggan' }}</div>
                                <div class="x-small text-muted mb-1">{{ $order->user->email ?? '-' }}</div>
                                <div class="small font-monospace text-success">
                                    <i class="bi bi-telephone me-1"></i> {{ $order->customer_phone ?: '-' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 border h-100">
                                <span class="x-small text-muted d-block fw-semibold mb-1">
                                    <i class="bi bi-geo-alt me-1"></i> Alamat Pengiriman:
                                </span>
                                <div class="small text-dark mb-1">
                                    {{ $order->shipping_address ?: 'Tidak ada alamat tercatat.' }}
                                </div>
                                @if($order->notes)
                                    <div class="x-small text-muted fst-italic">
                                        Catatan: "{{ $order->notes }}"
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 3. Form Input Nomor Resi & Update Status -->
                    <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="p-3.5 rounded-3 border" style="background-color: #F8FAFC; border-color: #CBD5E1 !important;">
                        @csrf
                        @method('PATCH')

                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-1.5">
                                <i class="bi bi-truck text-primary"></i> Form Update Nomor Resi & Status
                            </h6>
                            @if(in_array($order->status, ['pending', 'belum_bayar']))
                                <span class="badge bg-warning text-dark x-small">
                                    <i class="bi bi-lock-fill me-1"></i> Resi Terkunci (Belum Bayar)
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary x-small">
                                    Otomatis jadi "Dikirim" saat resi disimpan
                                </span>
                            @endif
                        </div>

                        @if(in_array($order->status, ['pending', 'belum_bayar']))
                            <div class="alert alert-warning py-2 px-3 small border-0 mb-3 d-flex align-items-center gap-2 rounded-2">
                                <i class="bi bi-exclamation-triangle-fill text-warning fs-6 flex-shrink-0"></i>
                                <span style="font-size: 0.8rem;">Pesanan ini masih <strong>Belum Bayar</strong>. Form nomor resi dinonaktifkan hingga pesanan dibayar (status Dikemas).</span>
                            </div>
                        @endif

                        <div class="row g-3 align-items-end">
                            <!-- Nomor Resi Input -->
                            <div class="col-md-6">
                                <label for="tracking_number_{{ $order->id }}" class="form-label small fw-semibold text-dark mb-1">
                                    Nomor Resi Pengiriman:
                                </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="bi bi-upc-scan"></i></span>
                                    <input type="text"
                                           name="tracking_number"
                                           id="tracking_number_{{ $order->id }}"
                                           class="form-control"
                                           placeholder="{{ in_array($order->status, ['pending', 'belum_bayar']) ? 'Menunggu pembayaran selesai...' : 'Contoh: JNE882910291, SICEPAT992' }}"
                                           value="{{ $order->tracking_number }}"
                                           {{ in_array($order->status, ['pending', 'belum_bayar']) ? 'disabled' : '' }}>
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.78rem;">
                                    @if(in_array($order->status, ['pending', 'belum_bayar']))
                                        <span class="text-warning fw-semibold"><i class="bi bi-lock"></i> Resi hanya dapat diinput setelah pembayaran diterima.</span>
                                    @else
                                        Status akan langsung diperbarui ke <strong>Dikirim</strong>.
                                    @endif
                                </div>
                            </div>

                            <!-- Status Dropdown (Manual Override) -->
                            <div class="col-md-6">
                                <label for="status_select_{{ $order->id }}" class="form-label small fw-semibold text-dark mb-1">
                                    Ubah Status Manual:
                                </label>
                                <select name="status" id="status_select_{{ $order->id }}" class="form-select form-select-sm">
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
                                <div class="text-muted mt-1" style="font-size: 0.78rem;">
                                    Pilih status untuk memperbarui pesanan secara manual.
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 pt-2 border-top">
                                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold">
                                    <i class="bi bi-save me-1"></i> Simpan Resi dan Perbarui Status
                                </button>
                            </div>
                        </div>
                    </form>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between align-items-center">
                    <div class="small text-muted">
                        Metode Bayar: <strong>{{ $order->payment_method }}</strong>
                    </div>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-link text-decoration-none">
                        Buka Detail Halaman Penuh <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
@endforeach
@endsection
