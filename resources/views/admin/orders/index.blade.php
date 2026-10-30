@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')
@section('page-title', 'Manajemen Pesanan')

@push('styles')
<style>
    /* Compact Metric Stat Cards */
    .stat-card-compact {
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 0.85rem 1rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .stat-card-compact:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
        border-color: #CBD5E1;
    }

    .stat-card-compact.active-filter {
        border-color: #0866FF !important;
        background-color: #F8FAFC;
        box-shadow: 0 0 0 1px #0866FF, 0 4px 12px rgba(8, 102, 255, 0.08);
    }

    .stat-icon-sm {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    /* Admin Table Styling */
    .table-admin-custom {
        margin-bottom: 0;
    }

    .table-admin-custom thead th {
        background-color: #F8FAFC;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
    }

    .table-admin-custom tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.875rem;
    }

    .table-admin-custom tbody tr:hover {
        background-color: #F8FAFC;
    }

    /* Table Column Width Controls */
    .col-order-id { min-width: 180px; }
    .col-customer { min-width: 210px; }
    .col-amount   { min-width: 140px; }
    .col-date     { min-width: 135px; }
    .col-status   { min-width: 135px; }
    .col-tracking { min-width: 165px; }
    .col-actions  { min-width: 135px; }

    /* Copy Button Tooltip Feel */
    .btn-copy-code {
        color: #94A3B8;
        padding: 0;
        line-height: 1;
        transition: color 0.15s ease;
    }
    .btn-copy-code:hover {
        color: #0866FF;
    }
</style>
@endpush

@section('content')
<!-- Header Summary Banner -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
    <div>
        <h4 class="fw-bold text-dark mb-1" style="font-size: 1.35rem; letter-spacing: -0.02em;">Daftar Transaksi Pesanan</h4>
        <p class="text-muted small mb-0">Pantau seluruh alur transaksi, verifikasi pembayaran, input nomor resi kurir, dan kelola status pengiriman.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 rounded-2">
            <i class="bi bi-arrow-clockwise"></i>
            <span>Refresh Data</span>
        </a>
        <a href="{{ route('products.index') }}" target="_blank" class="btn btn-outline-primary btn-sm px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 rounded-2">
            <i class="bi bi-shop"></i>
            <span>Toko Publik</span>
        </a>
    </div>
</div>

<!-- ==========================================
     1. COMPACT METRIC STAT CARDS (REAL DB DATA)
     ========================================== -->
<div class="row g-2.5 mb-3">
    <!-- 1. Semua Pesanan -->
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="stat-card-compact {{ empty($status) ? 'active-filter' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted x-small text-uppercase fw-bold">Semua</span>
                    <div class="stat-icon-sm bg-primary-subtle text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="h5 fw-bold text-dark mb-0 font-monospace">{{ $statusCounts['all'] ?? 0 }}</div>
                <div class="text-muted x-small mt-0.5">Total transaksi</div>
            </div>
        </a>
    </div>

    <!-- 2. Belum Bayar -->
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stat-card-compact {{ $status === 'pending' ? 'active-filter' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted x-small text-uppercase fw-bold">Belum Bayar</span>
                    <div class="stat-icon-sm bg-warning-subtle text-warning">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
                <div class="h5 fw-bold text-warning mb-0 font-monospace">{{ $statusCounts['pending'] ?? 0 }}</div>
                <div class="text-muted x-small mt-0.5">Perlu verifikasi</div>
            </div>
        </a>
    </div>

    <!-- 3. Dikemas -->
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="text-decoration-none">
            <div class="stat-card-compact {{ $status === 'processing' ? 'active-filter' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted x-small text-uppercase fw-bold">Dikemas</span>
                    <div class="stat-icon-sm bg-primary-subtle text-primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="h5 fw-bold text-primary mb-0 font-monospace">{{ $statusCounts['processing'] ?? 0 }}</div>
                <div class="text-muted x-small mt-0.5">Siap di-packing</div>
            </div>
        </a>
    </div>

    <!-- 4. Dikirim -->
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="text-decoration-none">
            <div class="stat-card-compact {{ $status === 'shipped' ? 'active-filter' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted x-small text-uppercase fw-bold">Dikirim</span>
                    <div class="stat-icon-sm bg-info-subtle text-info">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
                <div class="h5 fw-bold text-info mb-0 font-monospace">{{ $statusCounts['shipped'] ?? 0 }}</div>
                <div class="text-muted x-small mt-0.5">Dalam perjalanan</div>
            </div>
        </a>
    </div>

    <!-- 5. Selesai -->
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}" class="text-decoration-none">
            <div class="stat-card-compact {{ $status === 'completed' ? 'active-filter' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="text-muted x-small text-uppercase fw-bold">Selesai</span>
                    <div class="stat-icon-sm bg-success-subtle text-success">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="h5 fw-bold text-success mb-0 font-monospace">{{ $statusCounts['completed'] ?? 0 }}</div>
                <div class="text-muted x-small mt-0.5">Pesanan berhasil</div>
            </div>
        </a>
    </div>

    <!-- 6. Total Omzet (Lunas) -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card-compact bg-light">
            <div class="d-flex align-items-center justify-content-between mb-1">
                <span class="text-muted x-small text-uppercase fw-bold">Total Omzet</span>
                <div class="stat-icon-sm bg-secondary-subtle text-secondary">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="h6 fw-bold text-dark mb-0 font-monospace text-truncate" title="Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}">
                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
            </div>
            <div class="text-muted x-small mt-0.5">Pesanan selesai</div>
        </div>
    </div>
</div>

<!-- ==========================================
     2. FILTER & SEARCH TOOLBAR
     ========================================== -->
<div class="card border-0 bg-white p-3 mb-3 rounded-3 shadow-sm" style="border: 1px solid #E2E8F0 !important;">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
        <!-- Pencarian -->
        <div class="col-md-5 col-lg-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control border-start-0"
                       placeholder="Cari Order ID, nama pelanggan, resi..."
                       value="{{ $search ?? '' }}">
            </div>
        </div>

        <!-- Filter Status -->
        <div class="col-6 col-md-3 col-lg-3">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status Pesanan</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Dikemas (Siap Kirim)</option>
                <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Dikirim (Dalam Kurir)</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Selesai</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
        </div>

        <!-- Filter Periode Waktu -->
        <div class="col-6 col-md-2 col-lg-2">
            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="" {{ empty($period) ? 'selected' : '' }}>Semua Waktu</option>
                <option value="today" {{ ($period ?? '') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                <option value="week" {{ ($period ?? '') === 'week' ? 'selected' : '' }}>7 Hari Terakhir</option>
                <option value="month" {{ ($period ?? '') === 'month' ? 'selected' : '' }}>30 Hari Terakhir</option>
            </select>
        </div>

        <!-- Tombol Aksi Filter & Reset -->
        <div class="col-12 col-md-2 col-lg-3 d-flex gap-2 justify-content-md-end">
            <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold flex-grow-1 flex-md-grow-0 d-inline-flex align-items-center justify-content-center gap-1">
                <i class="bi bi-funnel-fill"></i>
                <span>Filter</span>
            </button>
            @if($search || $status || (!empty($period) && $period !== 'all'))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary px-3 d-inline-flex align-items-center justify-content-center gap-1" title="Hapus Filter">
                    <i class="bi bi-x-circle"></i>
                    <span>Reset</span>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Result Counter Info -->
<div class="d-flex justify-content-between align-items-center px-1 mb-2">
    <div class="x-small text-muted">
        Menampilkan <strong>{{ $orders->count() }}</strong> dari <strong>{{ $orders->total() }}</strong> total pesanan
        @if($status)
            dengan status <span class="badge bg-light text-dark border">{{ ucfirst($status) }}</span>
        @endif
        @if($search)
            kata kunci <span class="badge bg-light text-dark border">"{{ $search }}"</span>
        @endif
    </div>
</div>

<!-- ==========================================
     3. TABEL TRANSAKSI ADMIN TERSTRUKTUR
     ========================================== -->
<div class="card border-0 bg-white rounded-3 shadow-sm mb-4" style="border: 1px solid #E2E8F0 !important;">
    <div class="table-responsive">
        <table class="table table-admin-custom align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col" class="col-order-id">Order ID & Pembayaran</th>
                    <th scope="col" class="col-customer">Pelanggan & Kontak</th>
                    <th scope="col" class="col-amount text-end">Total Harga</th>
                    <th scope="col" class="col-date">Waktu Pemesanan</th>
                    <th scope="col" class="col-status text-center">Status Pesanan</th>
                    <th scope="col" class="col-tracking">Nomor Resi</th>
                    <th scope="col" class="col-actions text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <!-- 1. Order ID & Metode Pembayaran -->
                        <td class="col-order-id">
                            <div class="d-inline-flex align-items-center gap-1 bg-light px-2 py-0.5 rounded border mb-1">
                                <span class="fw-bold font-monospace text-dark x-small">{{ $order->order_code }}</span>
                                <button type="button" 
                                        class="btn btn-link btn-copy-code" 
                                        onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Order ID {{ $order->order_code }} berhasil disalin!');" 
                                        title="Salin Order ID">
                                    <i class="bi bi-clipboard x-small"></i>
                                </button>
                            </div>
                            <div>
                                <span class="badge bg-light text-secondary border x-small fw-normal">
                                    <i class="bi bi-wallet2 text-primary me-1"></i> {{ $order->payment_method }}
                                </span>
                            </div>
                        </td>

                        <!-- 2. Pelanggan & Kontak -->
                        <td class="col-customer">
                            <div class="fw-bold text-dark small text-truncate" style="max-width: 200px;" title="{{ $order->user->name ?? 'Pelanggan' }}">
                                {{ $order->user->name ?? 'Pelanggan' }}
                            </div>
                            <div class="x-small text-muted text-truncate mb-0.5" style="max-width: 200px;" title="{{ $order->user->email ?? '-' }}">
                                {{ $order->user->email ?? '-' }}
                            </div>
                            @if($order->customer_phone)
                                <div class="x-small">
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->customer_phone)) }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer" 
                                       class="text-success text-decoration-none fw-semibold d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-whatsapp"></i>
                                        <span>{{ $order->customer_phone }}</span>
                                    </a>
                                </div>
                            @endif
                        </td>

                        <!-- 3. Total Harga & Jumlah Produk -->
                        <td class="col-amount text-end">
                            <div class="fw-bold text-primary font-monospace fs-6">
                                {{ $order->formatted_total_price }}
                            </div>
                            <div class="x-small text-muted">
                                {{ $order->items->sum('quantity') }} unit produk
                            </div>
                        </td>

                        <!-- 4. Waktu Pemesanan -->
                        <td class="col-date" style="white-space: nowrap;">
                            <div class="small text-dark fw-medium">{{ $order->created_at->format('d M Y') }}</div>
                            <div class="x-small text-muted d-flex align-items-center gap-1">
                                <i class="bi bi-clock"></i>
                                <span>{{ $order->created_at->format('H:i') }} WIB</span>
                            </div>
                        </td>

                        <!-- 5. Status Pesanan (Badge Standar BladeStore) -->
                        <td class="col-status text-center" style="white-space: nowrap;">
                            @if(in_array($order->status, ['pending', 'belum_bayar']))
                                @if($order->isCod())
                                    <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small" style="background-color: #E0F2FE; border: 1px solid #BAE6FD; color: #0369A1;">
                                        <i class="bi bi-clock-history me-1"></i> Menunggu Diproses (COD)
                                    </span>
                                @elseif($order->isExpired())
                                    <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small" style="background-color: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B;">
                                        <i class="bi bi-x-circle-fill me-1"></i> Dibatalkan (Kadaluarsa)
                                    </span>
                                @else
                                    <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small" style="background-color: #FEF3C7; border: 1px solid #FCD34D; color: #92400E;">
                                        <i class="bi bi-clock-history me-1"></i> Belum Bayar
                                    </span>
                                @endif
                            @elseif(in_array($order->status, ['processing', 'dikemas']))
                                <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small" style="background-color: #EFF6FF; border: 1px solid #BFDBFE; color: #1D4ED8;">
                                    <i class="bi bi-box-seam me-1"></i> Dikemas
                                </span>
                            @elseif(in_array($order->status, ['shipped', 'dikirim']))
                                <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small" style="background-color: #E0E7FF; border: 1px solid #C7D2FE; color: #4338CA;">
                                    <i class="bi bi-truck me-1"></i> Dikirim
                                </span>
                            @elseif(in_array($order->status, ['completed', 'selesai']))
                                <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small" style="background-color: #DCFCE7; border: 1px solid #86EFAC; color: #15803D;">
                                    <i class="bi bi-check-circle-fill me-1"></i> Selesai
                                </span>
                            @elseif(in_array($order->status, ['cancelled', 'dibatalkan']))
                                <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small" style="background-color: #FEE2E2; border: 1px solid #FCA5A5; color: #991B1B;">
                                    <i class="bi bi-x-circle-fill me-1"></i> Dibatalkan
                                </span>
                            @else
                                <span class="badge px-2.5 py-1.5 rounded-pill fw-bold x-small bg-secondary text-white">
                                    {{ ucfirst($order->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- 6. Nomor Resi Pengiriman -->
                        <td class="col-tracking">
                            @if($order->tracking_number)
                                <div class="d-inline-flex align-items-center gap-1 bg-light px-2 py-0.5 rounded border mb-0.5">
                                    <span class="font-monospace fw-bold text-dark x-small">{{ $order->tracking_number }}</span>
                                    <button type="button" 
                                            class="btn btn-link btn-copy-code" 
                                            onclick="navigator.clipboard.writeText('{{ $order->tracking_number }}'); alert('Nomor resi disalin!');" 
                                            title="Salin Nomor Resi">
                                        <i class="bi bi-clipboard x-small"></i>
                                    </button>
                                </div>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle x-small d-block" style="width: fit-content;">
                                    <i class="bi bi-check2 me-0.5"></i> Resi Aktif
                                </span>
                            @elseif(in_array($order->status, ['pending', 'belum_bayar']))
                                @if($order->isCod())
                                    <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle x-small">
                                        <i class="bi bi-hourglass-split me-1"></i> Menunggu Proses
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle x-small">
                                        <i class="bi bi-lock me-1"></i> Menunggu Bayar
                                    </span>
                                @endif
                            @else
                                <span class="text-muted fst-italic x-small d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-dash-circle"></i>
                                    <span>Belum ada resi</span>
                                </span>
                            @endif
                        </td>

                        <!-- 7. Tombol Aksi (Selalu Terlihat & Responsif) -->
                        <td class="col-actions text-center" style="white-space: nowrap;">
                            <div class="d-flex justify-content-center align-items-center gap-1.5">
                                <!-- Tombol Kelola & Update Resi (Buka Modal) -->
                                <button type="button" 
                                        class="btn btn-sm btn-primary py-1 px-2.5 small fw-semibold d-inline-flex align-items-center gap-1 rounded-2 shadow-sm"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#orderDetailModal{{ $order->id }}"
                                        title="Buka Modal Kelola Pesanan & Input Resi">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Kelola</span>
                                </button>
                                
                                <!-- Link ke Halaman Faktur Detail Penuh -->
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="btn btn-sm btn-outline-secondary py-1 px-2 small rounded-2"
                                   title="Buka Halaman Detail Faktur Penuh">
                                    <i class="bi bi-arrow-up-right-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <!-- Empty State Bersahabat -->
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="py-3">
                                <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-muted mb-3" style="width: 64px; height: 64px;">
                                    <i class="bi bi-inbox fs-2"></i>
                                </span>
                                <h6 class="fw-bold text-dark mb-1">Tidak Ada Data Pesanan</h6>
                                <p class="text-muted small mb-3">Tidak ditemukan transaksi yang cocok dengan filter atau kata kunci pencarian Anda.</p>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary px-3 py-1.5 fw-semibold rounded-2">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Reset Seluruh Filter
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="card-footer bg-white border-top border-light-subtle py-3 px-3 px-md-4">
        @if($orders->hasPages())
            {{ $orders->links() }}
        @elseif($orders->total() > 0)
            <div class="text-muted small text-center text-md-start">
                Menampilkan <span class="fw-semibold text-dark">{{ $orders->count() }}</span> pesanan
            </div>
        @endif
    </div>
</div>

<!-- ==========================================
     4. MODAL KELOLA PESANAN & UPDATE RESI
     ========================================== -->
@foreach($orders as $order)
    <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderDetailModalLabel{{ $order->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow">
                
                <!-- Modal Header -->
                <div class="modal-header bg-light py-3 border-bottom">
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
                        <h6 class="fw-bold text-dark mb-2.5 d-flex align-items-center gap-2 fs-6">
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
                                                    <div style="width: 42px; height: 42px; border-radius: 6px; overflow: hidden; background-color: #F8FAFC; padding: 2px;" class="border flex-shrink-0 d-flex align-items-center justify-content-center">
                                                        <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                                             alt="{{ $item->product->name ?? 'Produk' }}"
                                                             class="w-100 h-100 object-fit-contain"
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
                                        <td colspan="3" class="text-end fw-bold small">Total Tagihan:</td>
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
                            <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-1.5 fs-6">
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
                                <span style="font-size: 0.8rem;">Pesanan ini masih <strong>Belum Bayar</strong>. Form nomor resi dinonaktifkan hingga pembayaran diterima (status Dikemas).</span>
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
                                        Status akan otomatis berubah ke <strong>Dikirim</strong> saat resi tersimpan.
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
                                    <i class="bi bi-save me-1"></i> Simpan Resi dan Status
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
                        Buka Halaman Detail Faktur <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
@endforeach
@endsection
