@extends('layouts.admin')

@section('title', 'Omset & Keuangan')
@section('page-title', 'Omset & Keuangan')

@push('styles')
<style>
    /* Metric Cards */
    .metric-revenue-card {
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

    .metric-revenue-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
        border-color: #CBD5E1;
    }

    .metric-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    /* Chart Containers & Cards */
    .chart-card {
        background-color: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 14px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        height: 100%;
    }

    .chart-header {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
    }

    /* Period Filter Pills */
    .btn-period-pill {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        color: #64748B;
        background-color: #F8FAFC;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-period-pill:hover {
        background-color: #EEF2F6;
        color: #0F172A;
    }

    .btn-period-pill.active {
        background-color: #0866FF;
        border-color: #0866FF;
        color: #FFFFFF;
        box-shadow: 0 2px 6px rgba(8, 102, 255, 0.3);
    }

    /* Table & Column Controls */
    .table-revenue-custom {
        margin-bottom: 0;
    }

    .table-revenue-custom thead th {
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

    .table-revenue-custom tbody td {
        padding: 0.85rem 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #F1F5F9;
        font-size: 0.875rem;
    }

    .table-revenue-custom tbody tr:hover {
        background-color: #F8FAFC;
    }

    .col-rev-date    { min-width: 145px; }
    .col-rev-code    { min-width: 185px; }
    .col-rev-user    { min-width: 220px; }
    .col-rev-method  { min-width: 155px; }
    .col-rev-total   { min-width: 150px; }
    .col-rev-status  { min-width: 140px; }

    /* Copy Button */
    .btn-copy-code {
        color: #94A3B8;
        padding: 0;
        line-height: 1;
        background: transparent;
        border: none;
        transition: color 0.15s ease;
    }
    .btn-copy-code:hover {
        color: #0866FF;
    }
</style>
@endpush

@section('content')
<!-- Header Summary Banner -->
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h4 class="fw-bold text-dark mb-0" style="font-size: 1.4rem; letter-spacing: -0.02em;">Omset & Keuangan</h4>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 fw-semibold x-small">
                <i class="bi bi-shield-check me-1"></i>Data Terverifikasi
            </span>
        </div>
        <p class="text-muted small mb-0">Pantau performa pendapatan, arus kas transaksi lunas, dan perbandingan omset BladeStore secara akurat.</p>
    </div>
    
    <div class="d-flex flex-wrap align-items-center gap-2">
        <!-- Last Updated Pill -->
        <span class="badge bg-light text-secondary border rounded-pill px-3 py-2 d-inline-flex align-items-center gap-1.5 small fw-normal">
            <i class="bi bi-clock-history text-muted"></i>
            <span>Pembaruan: <strong class="text-dark fw-semibold">{{ $lastUpdated }}</strong></span>
        </span>

        <!-- CSV Export Button -->
        <a href="{{ route('admin.revenue.export', request()->query()) }}" class="btn btn-success btn-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 rounded-2 shadow-sm">
            <i class="bi bi-file-earmark-arrow-down"></i>
            <span>Ekspor CSV</span>
        </a>

        <!-- Refresh Button -->
        <a href="{{ route('admin.revenue.index') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1.5 rounded-2">
            <i class="bi bi-arrow-clockwise"></i>
            <span>Refresh</span>
        </a>
    </div>
</div>

<!-- ==========================================
     1. RINGKASAN OMSET (5 KPI CARDS)
     ========================================== -->
<div class="row g-3 mb-4">
    <!-- Card 1: Total Omset (Filtered/All-Time) -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="metric-revenue-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Total Omset</span>
                    <div class="h5 fw-bold text-dark mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        Rp {{ number_format($filteredRevenue, 0, ',', '.') }}
                    </div>
                </div>
                <div class="metric-icon-box bg-primary-subtle text-primary">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">
                    {{ $period === 'all' ? 'Sepanjang Waktu' : 'Periode Terpilih' }}
                </span>
                <span class="badge bg-primary text-white rounded-pill px-2 py-0.5 x-small fw-semibold">
                    Lunas
                </span>
            </div>
        </div>
    </div>

    <!-- Card 2: Omset Hari Ini -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="metric-revenue-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Omset Hari Ini</span>
                    <div class="h5 fw-bold text-success mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        Rp {{ number_format($revenueToday, 0, ',', '.') }}
                    </div>
                </div>
                <div class="metric-icon-box bg-success-subtle text-success">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">{{ now()->translatedFormat('d M Y') }}</span>
                <span class="text-success x-small fw-semibold">
                    <i class="bi bi-check2-circle me-0.5"></i>Hari Berjalan
                </span>
            </div>
        </div>
    </div>

    <!-- Card 3: Omset Bulan Ini -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="metric-revenue-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Omset Bulan Ini</span>
                    <div class="h5 fw-bold text-info mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}
                    </div>
                </div>
                <div class="metric-icon-box bg-info-subtle text-info">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">{{ now()->translatedFormat('F Y') }}</span>
                <span class="text-info x-small fw-semibold">Bulan Berjalan</span>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Transaksi Berhasil -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="metric-revenue-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Transaksi Lunas</span>
                    <div class="h5 fw-bold text-dark mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        {{ number_format($filteredTransactionCount) }} <span class="fs-6 fw-normal text-muted">order</span>
                    </div>
                </div>
                <div class="metric-icon-box bg-warning-subtle text-dark">
                    <i class="bi bi-bag-check-fill text-warning"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">Pesanan Terverifikasi</span>
                <span class="badge bg-success-subtle text-success rounded-pill px-2 py-0.5 x-small fw-semibold">Sukses</span>
            </div>
        </div>
    </div>

    <!-- Card 5: Rata-rata Nilai Transaksi (AOV) -->
    <div class="col-12 col-sm-6 col-xl">
        <div class="metric-revenue-card">
            <div class="d-flex align-items-start justify-content-between mb-2">
                <div>
                    <span class="text-muted x-small text-uppercase fw-bold letter-spacing-wider">Rata-rata Nilai (AOV)</span>
                    <div class="h5 fw-bold text-primary mb-0 mt-1 font-monospace" style="font-size: 1.35rem;">
                        Rp {{ number_format($averageOrderValue, 0, ',', '.') }}
                    </div>
                </div>
                <div class="metric-icon-box bg-secondary-subtle text-secondary">
                    <i class="bi bi-calculator"></i>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light-subtle">
                <span class="text-muted x-small">Per transaksi lunas</span>
                <span class="text-primary x-small fw-semibold">Rata-rata</span>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     2. REVENUE CHARTS & PAYMENT BREAKDOWN
     ========================================== -->
<div class="row g-3 mb-4">
    <!-- Chart 1: Line Chart Tren Omset Harian (Left Column) -->
    <div class="col-12 col-lg-8">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5">Tren Pendapatan Harian</h6>
                    <span class="text-muted x-small">Fluktuasi omset pesanan lunas berdasarkan tanggal transaksi</span>
                </div>

                <!-- Period Filter Pills -->
                <div class="d-flex flex-wrap align-items-center gap-1.5">
                    @php
                        $queryParams = request()->except(['period', 'page']);
                    @endphp
                    <a href="{{ route('admin.revenue.index', array_merge($queryParams, ['period' => '7_days'])) }}"
                       class="btn-period-pill {{ $period === '7_days' ? 'active' : '' }}">
                        7 Hari
                    </a>
                    <a href="{{ route('admin.revenue.index', array_merge($queryParams, ['period' => '30_days'])) }}"
                       class="btn-period-pill {{ $period === '30_days' ? 'active' : '' }}">
                        30 Hari
                    </a>
                    <a href="{{ route('admin.revenue.index', array_merge($queryParams, ['period' => 'this_month'])) }}"
                       class="btn-period-pill {{ $period === 'this_month' ? 'active' : '' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.revenue.index', array_merge($queryParams, ['period' => 'this_year'])) }}"
                       class="btn-period-pill {{ $period === 'this_year' ? 'active' : '' }}">
                        Tahun Ini
                    </a>
                    <button type="button" class="btn-period-pill {{ $period === 'custom' ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#customDateCollapse" aria-expanded="{{ $period === 'custom' ? 'true' : 'false' }}">
                        <i class="bi bi-calendar3 me-0.5"></i>Kustom
                    </button>
                </div>
            </div>

            <!-- Custom Date Range Collapse -->
            <div class="collapse {{ $period === 'custom' ? 'show' : '' }} mb-3" id="customDateCollapse">
                <form method="GET" action="{{ route('admin.revenue.index') }}" class="p-2.5 bg-light rounded-3 border">
                    <input type="hidden" name="period" value="custom">
                    @if($paymentMethodFilter)
                        <input type="hidden" name="payment_method" value="{{ $paymentMethodFilter }}">
                    @endif
                    @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-sm-5">
                            <label class="x-small text-muted fw-bold mb-1">Mulai Tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDateInput ?? request('start_date') }}" required>
                        </div>
                        <div class="col-12 col-sm-5">
                            <label class="x-small text-muted fw-bold mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDateInput ?? request('end_date') }}" required>
                        </div>
                        <div class="col-12 col-sm-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold" style="margin-top: 1.25rem;">
                                Terapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Line Chart Canvas -->
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="dailyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Payment Method Distribution Breakdown (Right Column) -->
    <div class="col-12 col-lg-4">
        <div class="chart-card d-flex flex-column justify-content-between">
            <div>
                <div class="chart-header">
                    <div>
                        <h6 class="fw-bold text-dark mb-0.5">Metode Pembayaran</h6>
                        <span class="text-muted x-small">Distribusi omset per metode pembayaran</span>
                    </div>
                </div>

                @if(count($paymentBreakdown) > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($paymentBreakdown as $method)
                            <div>
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="stat-icon-sm" style="background-color: {{ $method['color'] }}15; color: {{ $method['color'] }}; width: 28px; height: 28px; border-radius: 6px; font-size: 0.9rem;">
                                            <i class="bi {{ $method['icon'] }}"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark small d-block">{{ $method['name'] }}</span>
                                            <span class="text-muted x-small">{{ $method['count'] }} transaksi</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-dark font-monospace small d-block">
                                            Rp {{ number_format($method['total'], 0, ',', '.') }}
                                        </span>
                                        <span class="badge rounded-pill x-small px-2 py-0.5 fw-semibold" style="background-color: {{ $method['color'] }}20; color: {{ $method['color'] }};">
                                            {{ $method['percentage'] }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #F1F5F9; border-radius: 4px;">
                                    <div class="progress-bar rounded-pill" role="progressbar" style="width: {{ $method['percentage'] }}%; background-color: {{ $method['color'] }};" aria-valuenow="{{ $method['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-credit-card-2-front display-6 text-secondary opacity-50 d-block mb-2"></i>
                        <span class="small">Belum ada transaksi pada periode ini</span>
                    </div>
                @endif
            </div>

            <!-- Footer note -->
            <div class="pt-3 mt-3 border-top border-light-subtle text-muted x-small d-flex align-items-center justify-content-between">
                <span>Total Omset Terhitung:</span>
                <strong class="text-dark font-monospace">Rp {{ number_format($filteredRevenue, 0, ',', '.') }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     3. MONTHLY COMPARISON BAR CHART
     ========================================== -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="chart-card">
            <div class="chart-header">
                <div>
                    <h6 class="fw-bold text-dark mb-0.5">Perbandingan Omset Bulanan (Tahun {{ $monthlyChartData['year'] }})</h6>
                    <span class="text-muted x-small">Statistik pendapatan lunas setiap bulan dari Januari hingga Desember</span>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fw-semibold x-small">
                    Tahun Kalender {{ $monthlyChartData['year'] }}
                </span>
            </div>

            <div style="position: relative; height: 240px; width: 100%;">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     4. TABEL RIWAYAT TRANSAKSI PENDAPATAN
     ========================================== -->
<div class="card border-0 shadow-sm rounded-3 overflow-hidden">
    <!-- Card Header with Filters -->
    <div class="card-header bg-white border-bottom border-light-subtle py-3 px-3 px-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-3">
            <div>
                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">Riwayat Transaksi Pendapatan</h5>
                <span class="text-muted x-small">Rincian seluruh transaksi yang telah terverifikasi lunas dan masuk omset</span>
            </div>
            <div>
                <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill x-small fw-semibold font-monospace">
                    {{ $transactions->total() }} Transaksi Ditemukan
                </span>
            </div>
        </div>

        <!-- Filter & Search Toolbar -->
        <form method="GET" action="{{ route('admin.revenue.index') }}" class="row g-2 align-items-center">
            @if($period)
                <input type="hidden" name="period" value="{{ $period }}">
            @endif
            @if($startDateInput && $endDateInput)
                <input type="hidden" name="start_date" value="{{ $startDateInput }}">
                <input type="hidden" name="end_date" value="{{ $endDateInput }}">
            @endif

            <!-- Search input -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari Order ID, nama, telepon..." value="{{ $search }}">
                </div>
            </div>

            <!-- Payment method filter -->
            <div class="col-6 col-sm-3 col-lg-3">
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="all">Semua Metode Pembayaran</option>
                    @foreach($availablePaymentMethods as $method)
                        <option value="{{ $method }}" {{ $paymentMethodFilter === $method ? 'selected' : '' }}>
                            {{ $method }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort By -->
            <div class="col-6 col-sm-3 col-lg-3">
                <select name="sort_by" class="form-select form-select-sm">
                    <option value="date" {{ $sortBy === 'date' ? 'selected' : '' }}>Urutkan: Tanggal Transaksi</option>
                    <option value="amount" {{ $sortBy === 'amount' ? 'selected' : '' }}>Urutkan: Nominal Pembayaran</option>
                </select>
            </div>

            <!-- Sort Direction & Action Buttons -->
            <div class="col-12 col-lg-2 d-flex align-items-center gap-1.5">
                <select name="sort_dir" class="form-select form-select-sm" style="max-width: 100px;">
                    <option value="desc" {{ $sortDir === 'desc' ? 'selected' : '' }}>Terbaru / Tertinggi</option>
                    <option value="asc" {{ $sortDir === 'asc' ? 'selected' : '' }}>Terlama / Terendah</option>
                </select>
                <button type="submit" class="btn btn-primary btn-sm px-2.5 fw-semibold flex-fill d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>
                @if($search || ($paymentMethodFilter && $paymentMethodFilter !== 'all') || $sortBy !== 'date' || $sortDir !== 'desc')
                    <a href="{{ route('admin.revenue.index', ['period' => $period]) }}" class="btn btn-outline-secondary btn-sm px-2" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table Responsive -->
    <div class="table-responsive">
        <table class="table table-hover table-revenue-custom align-middle">
            <thead>
                <tr>
                    <th class="col-rev-date">Tanggal Transaksi</th>
                    <th class="col-rev-code">Order ID</th>
                    <th class="col-rev-user">Pelanggan</th>
                    <th class="col-rev-method">Metode Pembayaran</th>
                    <th class="col-rev-total text-end">Total Pembayaran</th>
                    <th class="col-rev-status text-center">Status Pembayaran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $order)
                    <tr>
                        <!-- 1. Tanggal Transaksi -->
                        <td class="col-rev-date">
                            <div class="fw-semibold text-dark">
                                {{ $order->created_at ? $order->created_at->translatedFormat('d M Y') : '-' }}
                            </div>
                            <div class="text-muted x-small">
                                {{ $order->created_at ? $order->created_at->format('H:i') : '' }} WIB
                            </div>
                        </td>

                        <!-- 2. Order ID -->
                        <td class="col-rev-code">
                            <div class="d-flex align-items-center gap-1.5">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="text-decoration-none fw-bold text-primary font-monospace small">
                                    {{ $order->order_code }}
                                </a>
                                <button type="button" class="btn-copy-code" title="Salin Order ID" onclick="copyOrderCode('{{ $order->order_code }}', this)">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="text-muted x-small mt-0.5">
                                {{ $order->items->count() }} jenis barang
                            </div>
                        </td>

                        <!-- 3. Pelanggan -->
                        <td class="col-rev-user">
                            <div class="d-flex align-items-center gap-2">
                                <div class="stat-icon-sm bg-primary-subtle text-primary rounded-circle fw-bold small" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate small" style="max-width: 170px;">
                                        {{ $order->user->name ?? 'Guest/Pelanggan Terhapus' }}
                                    </div>
                                    <div class="text-muted x-small text-truncate" style="max-width: 170px;">
                                        {{ $order->user->email ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- 4. Metode Pembayaran -->
                        <td class="col-rev-method">
                            @php
                                $methodName = $order->payment_method ?? 'Tidak Diketahui';
                                $lowerMethod = strtolower($methodName);
                                $badgeClass = 'bg-light text-dark border';
                                $iconClass = 'bi-credit-card-2-front';
                                
                                if (str_contains($lowerMethod, 'qris')) {
                                    $badgeClass = 'bg-info-subtle text-info border border-info-subtle';
                                    $iconClass = 'bi-qr-code-scan';
                                } elseif (str_contains($lowerMethod, 'transfer') || str_contains($lowerMethod, 'bank')) {
                                    $badgeClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                                    $iconClass = 'bi-bank';
                                } elseif (str_contains($lowerMethod, 'cod')) {
                                    $badgeClass = 'bg-warning-subtle text-dark border border-warning-subtle';
                                    $iconClass = 'bi-cash-coin';
                                } elseif (str_contains($lowerMethod, 'snap') || str_contains($lowerMethod, 'midtrans')) {
                                    $badgeClass = 'bg-primary-subtle text-primary border border-primary-subtle';
                                    $iconClass = 'bi-shield-check';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-2.5 py-1 x-small fw-semibold d-inline-flex align-items-center gap-1">
                                <i class="bi {{ $iconClass }}"></i>
                                <span>{{ $methodName }}</span>
                            </span>
                        </td>

                        <!-- 5. Total Pembayaran -->
                        <td class="col-rev-total text-end">
                            <div class="fw-bold text-dark font-monospace" style="font-size: 0.95rem;">
                                {{ $order->formatted_total_price }}
                            </div>
                            <div class="text-success x-small fw-semibold">
                                <i class="bi bi-check-circle-fill me-0.5"></i>Lunas
                            </div>
                        </td>

                        <!-- 6. Status Pembayaran / Pesanan -->
                        <td class="col-rev-status text-center">
                            @php
                                $statusBadge = 'bg-success text-white';
                                $statusText = 'Lunas / Selesai';
                                if (in_array($order->status, ['processing', 'dikemas'])) {
                                    $statusBadge = 'bg-info text-dark';
                                    $statusText = 'Lunas (Dikemas)';
                                } elseif (in_array($order->status, ['shipped', 'dikirim'])) {
                                    $statusBadge = 'bg-primary text-white';
                                    $statusText = 'Lunas (Dikirim)';
                                }
                            @endphp
                            <span class="badge {{ $statusBadge }} rounded-pill px-2.5 py-1 x-small fw-semibold">
                                {{ $statusText }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <div class="stat-icon-sm bg-light text-muted rounded-circle mx-auto mb-3" style="width: 56px; height: 56px; font-size: 1.5rem;">
                                    <i class="bi bi-wallet2"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Tidak Ada Riwayat Transaksi</h6>
                                <p class="text-muted small mb-3">Tidak ditemukan transaksi pembayaran lunas yang sesuai dengan kriteria filter saat ini.</p>
                                <a href="{{ route('admin.revenue.index') }}" class="btn btn-outline-primary btn-sm px-3 py-1.5 rounded-pill fw-semibold">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Semua Filter
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Card Footer with Pagination -->
    @if($transactions->hasPages())
        <div class="card-footer bg-white border-top border-light-subtle py-3 px-3 px-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="text-muted x-small">
                    Menampilkan <span class="fw-semibold text-dark">{{ $transactions->firstItem() }}</span> s/d <span class="fw-semibold text-dark">{{ $transactions->lastItem() }}</span> dari <span class="fw-semibold text-dark">{{ $transactions->total() }}</span> transaksi
                </div>
                <div>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    // Copy Order Code to Clipboard Helper
    function copyOrderCode(code, el) {
        if (!navigator.clipboard) {
            const tempInput = document.createElement("input");
            tempInput.value = code;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            showCopyFeedback(el);
            return;
        }

        navigator.clipboard.writeText(code).then(() => {
            showCopyFeedback(el);
        }).catch(() => {
            console.error("Gagal menyalin teks");
        });
    }

    function showCopyFeedback(el) {
        const originalHtml = el.innerHTML;
        el.innerHTML = '<i class="bi bi-check-lg text-success"></i>';
        setTimeout(() => {
            el.innerHTML = originalHtml;
        }, 1500);
    }

    // Number formatter for Indonesian Rupiah
    function formatRupiah(value) {
        return 'Rp ' + Number(value).toLocaleString('id-ID');
    }

    // 1. Line Chart: Tren Pendapatan Harian
    const lineCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    
    // Gradient background under the curve
    const lineGradient = lineCtx.createLinearGradient(0, 0, 0, 260);
    lineGradient.addColorStop(0, 'rgba(8, 102, 255, 0.22)');
    lineGradient.addColorStop(1, 'rgba(8, 102, 255, 0.01)');

    const chartDailyLabels = @json($chartData['labels']);
    const chartDailyValues = @json($chartData['data']);

    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: chartDailyLabels,
            datasets: [{
                label: 'Pendapatan Harian',
                data: chartDailyValues,
                fill: true,
                backgroundColor: lineGradient,
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
                        font: {
                            size: 11
                        }
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
                        font: {
                            size: 11
                        },
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

    // 2. Bar Chart: Perbandingan Omset Bulanan
    const barCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const chartMonthlyLabels = @json($monthlyChartData['labels']);
    const chartMonthlyValues = @json($monthlyChartData['data']);

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: chartMonthlyLabels,
            datasets: [{
                label: 'Omset Bulanan',
                data: chartMonthlyValues,
                backgroundColor: 'rgba(8, 102, 255, 0.85)',
                hoverBackgroundColor: '#0866FF',
                borderRadius: 6,
                borderSkipped: false,
                maxBarThickness: 36
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
                        font: {
                            size: 11
                        }
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
                        font: {
                            size: 11
                        },
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
</script>
@endpush

