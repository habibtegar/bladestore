@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@push('styles')
<style>
    /* Metric Stat Card */
    .dashboard-stat-card {
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 1.15rem 1.25rem;
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        border-color: #CBD5E1;
    }

    .stat-icon-wrapper {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Content Cards */
    .dashboard-card {
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        height: 100%;
    }

    .dashboard-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
    }

    /* Table within Dashboard */
    .table-dashboard thead th {
        background-color: #F8FAFC;
        color: #475569;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #E2E8F0;
        white-space: nowrap;
    }

    .table-dashboard tbody td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.875rem;
    }

    .table-dashboard tbody tr:hover {
        background-color: #F8FAFC;
    }
</style>
@endpush

@section('content')
<!-- Header Summary Banner -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.4rem; letter-spacing: -0.02em;">Dashboard Utama</h4>
            <span class="badge bg-primary text-white rounded-pill px-2.5 py-1 x-small fw-semibold">
                BladeStore Live
            </span>
        </div>
        <p class="text-muted small mb-0">Ringkasan performa operasional toko, total omset, status pesanan terbaru, dan kontrol inventaris.</p>
    </div>

    <div class="d-flex flex-wrap align-items-center gap-2">
        <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1.5 small fw-normal">
            <i class="bi bi-clock-history text-muted"></i>
            <span>Pembaruan: <strong class="text-dark fw-semibold">{{ $lastUpdated }}</strong></span>
        </span>

        <a href="{{ route('admin.revenue.index') }}" class="btn btn-outline-primary btn-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 rounded-2">
            <i class="bi bi-wallet2"></i>
            <span>Omset & Keuangan</span>
        </a>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 rounded-2">
            <i class="bi bi-receipt-cutoff"></i>
            <span>Kelola Pesanan</span>
        </a>
    </div>
</div>

<!-- ==========================================
     1. STATISTIK UTAMA (6 KPI CARDS)
     ========================================== -->
<div class="row g-3 mb-4">
    <!-- 1. Total Omset (Lunas) -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Total Omset</span>
                    <div class="h5 fw-bold text-dark mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">Transaksi Lunas Terverifikasi</span>
                <a href="{{ route('admin.revenue.index') }}" class="text-primary text-decoration-none x-small fw-semibold">
                    Detail <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Omset Hari Ini -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Omset Hari Ini</span>
                    <div class="h5 fw-bold text-success mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        Rp {{ number_format($revenueToday, 0, ',', '.') }}
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-success-subtle text-success">
                    <i class="bi bi-calendar-check"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">{{ now()->translatedFormat('d M Y') }}</span>
                <span class="text-success x-small fw-semibold">
                    <i class="bi bi-check-circle-fill me-0.5"></i>Hari Ini
                </span>
            </div>
        </div>
    </div>

    <!-- 3. Total Pesanan -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Total Pesanan</span>
                    <div class="h5 fw-bold text-dark mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        {{ number_format($totalOrdersCount) }} <span class="fs-6 fw-normal text-muted">transaksi</span>
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-info-subtle text-info">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">Semua status pesanan</span>
                <a href="{{ route('admin.orders.index') }}" class="text-info text-decoration-none x-small fw-semibold">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- 4. Pesanan Perlu Diproses -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Perlu Diproses</span>
                    <div class="h5 fw-bold text-primary mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        {{ number_format($processingOrdersCount) }} <span class="fs-6 fw-normal text-muted">pesanan</span>
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-primary-subtle text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">Siap di-packing & kirim</span>
                @if($processingOrdersCount > 0)
                    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="badge bg-primary text-white text-decoration-none rounded-pill px-2 py-0.5 x-small fw-semibold">
                        Proses Sekarang
                    </a>
                @else
                    <span class="text-muted x-small">Tidak ada antrean</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 5. Pesanan Belum Dibayar -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Belum Bayar</span>
                    <div class="h5 fw-bold text-warning mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        {{ number_format($pendingOrdersCount) }} <span class="fs-6 fw-normal text-muted">pesanan</span>
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-warning-subtle text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">Menunggu pembayaran</span>
                @if($pendingOrdersCount > 0)
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-warning text-decoration-none x-small fw-semibold">
                        Cek Status <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <span class="text-muted x-small">Nihil</span>
                @endif
            </div>
        </div>
    </div>

    <!-- 6. Total Produk Aktif -->
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-stat-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Produk Aktif</span>
                    <div class="h5 fw-bold text-dark mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        {{ number_format($totalProductsCount) }} <span class="fs-6 fw-normal text-muted">item</span>
                    </div>
                </div>
                <div class="stat-icon-wrapper bg-secondary-subtle text-secondary">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">Katalog BladeStore</span>
                <a href="{{ route('admin.products.index') }}" class="text-secondary text-decoration-none x-small fw-semibold">
                    Katalog <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     2. GRAFIK OMSET & STATUS DISTRIBUSI
     ========================================== -->
<div class="row g-3 mb-4">
    <!-- Chart: Line Chart 7 Hari Terakhir -->
    <div class="col-12 col-lg-8">
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5">Tren Pendapatan (7 Hari Terakhir)</h6>
                    <span class="text-muted x-small">Fluktuasi omset dari pesanan lunas terverifikasi</span>
                </div>
                <a href="{{ route('admin.revenue.index') }}" class="btn btn-outline-primary btn-sm px-2.5 py-1 fw-semibold d-inline-flex align-items-center gap-1 rounded-pill x-small">
                    <span>Laporan Lengkap</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="dashboardRevenueChart"
                        data-labels='@json($chartLabels)'
                        data-values='@json($chartData)'></canvas>
            </div>
        </div>
    </div>

    <!-- Status Ringkasan Pesanan -->
    <div class="col-12 col-lg-4">
        <div class="dashboard-card d-flex flex-column justify-content-between">
            <div>
                <div class="dashboard-card-header">
                    <div>
                        <h6 class="fw-bold text-dark mb-0.5">Ringkasan Status</h6>
                        <span class="text-muted x-small">Distribusi seluruh pesanan di sistem</span>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2.5">
                    <!-- Selesai -->
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success rounded-circle p-1"></span>
                            <span class="small fw-semibold text-dark">Pesanan Selesai</span>
                        </div>
                        <span class="fw-bold font-monospace text-dark small">{{ $completedOrdersCount }}</span>
                    </div>

                    <!-- Dikirim -->
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-circle p-1"></span>
                            <span class="small fw-semibold text-dark">Dalam Pengiriman</span>
                        </div>
                        <span class="fw-bold font-monospace text-dark small">{{ $shippedOrdersCount }}</span>
                    </div>

                    <!-- Dikemas -->
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info rounded-circle p-1"></span>
                            <span class="small fw-semibold text-dark">Dikemas (Siap Kirim)</span>
                        </div>
                        <span class="fw-bold font-monospace text-dark small">{{ $processingOrdersCount }}</span>
                    </div>

                    <!-- Belum Bayar -->
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning rounded-circle p-1"></span>
                            <span class="small fw-semibold text-dark">Belum Dibayar</span>
                        </div>
                        <span class="fw-bold font-monospace text-dark small">{{ $pendingOrdersCount }}</span>
                    </div>
                </div>
            </div>

            <div class="pt-3 mt-3 border-top border-light-subtle d-grid">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                    Buka Manajemen Pesanan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     3. PESANAN TERBARU & STOK MENIPIS
     ========================================== -->
<div class="row g-3">
    <!-- Pesanan Terbaru (Recent Orders) -->
    <div class="col-12 col-lg-8">
        <div class="dashboard-card p-0 overflow-hidden">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5">Pesanan Terbaru</h6>
                    <span class="text-muted x-small">Daftar transaksi pesanan paling mutakhir</span>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm px-2.5 py-1 fw-semibold rounded-pill x-small">
                    Semua Pesanan
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-dashboard align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Waktu</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary font-monospace text-decoration-none small">
                                        {{ $order->order_code }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small text-truncate" style="max-width: 140px;">
                                        {{ $order->user->name ?? 'Guest/Pelanggan' }}
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark font-monospace small">
                                        {{ $order->formatted_total_price }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $order->status_badge_class }} rounded-pill px-2 py-0.5 x-small fw-semibold">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted x-small">
                                        {{ $order->created_at ? $order->created_at->diffForHumans() : '-' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-secondary btn-sm py-0.5 px-2 x-small fw-semibold rounded-2" title="Lihat Detail">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">
                                    Belum ada transaksi pesanan yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Informasi Stok Menipis (Low Stock Alert) -->
    <div class="col-12 col-lg-4">
        <div class="dashboard-card d-flex flex-column justify-content-between">
            <div>
                <div class="dashboard-card-header">
                    <div>
                        <div class="d-flex align-items-center gap-1.5">
                            <h6 class="fw-bold text-dark mb-0">Stok Menipis</h6>
                            @if($lowStockCount > 0)
                                <span class="badge bg-danger rounded-pill px-2 py-0.5 x-small fw-bold">
                                    {{ $lowStockCount }} Item
                                </span>
                            @endif
                        </div>
                        <span class="text-muted x-small">Produk dengan stok &le; 5 unit</span>
                    </div>
                </div>

                @if($lowStockProducts->count() > 0)
                    <div class="d-flex flex-column gap-2.5">
                        @foreach($lowStockProducts as $prod)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border bg-light-subtle">
                                <div class="overflow-hidden me-2">
                                    <div class="fw-bold text-dark small text-truncate" style="max-width: 170px;">
                                        {{ $prod->name }}
                                    </div>
                                    <span class="text-muted x-small">{{ $prod->category ?? 'Umum' }}</span>
                                </div>
                                <div class="text-end flex-shrink-0">
                                    <span class="badge {{ $prod->stock == 0 ? 'bg-danger text-white' : 'bg-warning text-dark' }} rounded-pill px-2 py-0.5 x-small fw-bold">
                                        {{ $prod->stock == 0 ? 'Habis (0)' : 'Sisa '.$prod->stock }}
                                    </span>
                                    <a href="{{ route('admin.products.edit', $prod->id) }}" class="d-block text-primary x-small fw-semibold text-decoration-none mt-0.5">
                                        Update <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="stat-icon-wrapper bg-success-subtle text-success rounded-circle mx-auto mb-2" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h6 class="fw-bold text-dark small mb-1">Semua Stok Aman</h6>
                        <p class="text-muted x-small mb-0">Tidak ada produk aktif dengan stok menipis (&le; 5 unit).</p>
                    </div>
                @endif
            </div>

            <div class="pt-3 mt-3 border-top border-light-subtle d-grid">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm fw-semibold">
                    Kelola Semua Produk
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    function formatRupiah(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }

    const chartCanvas = document.getElementById('dashboardRevenueChart');
    if (chartCanvas) {
        const ctx = chartCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(8, 102, 255, 0.22)');
        gradient.addColorStop(1, 'rgba(8, 102, 255, 0.01)');

        const labels = JSON.parse(chartCanvas.getAttribute('data-labels') || '[]');
        const dataValues = JSON.parse(chartCanvas.getAttribute('data-values') || '[]');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omset Harian',
                    data: dataValues,
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#0866FF',
                    borderWidth: 2.5,
                    tension: 0.35,
                    pointBackgroundColor: '#FFFFFF',
                    pointBorderColor: '#0866FF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#0866FF',
                    pointHoverBorderColor: '#FFFFFF',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleColor: '#94A3B8',
                        bodyColor: '#FFFFFF',
                        padding: 10,
                        borderRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Omset: ' + formatRupiah(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748B',
                            font: { size: 11 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#F1F5F9',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748B',
                            font: { size: 11 },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000).toFixed(1).replace('.0', '') + ' jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000).toFixed(0) + ' rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    }
                }
            }
        });
    }
</script>
@endpush

