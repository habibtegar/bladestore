@extends('layouts.admin')

@section('title', 'Manajemen Pesanan — Admin LaraShop')
@section('page-title', 'Manajemen Pesanan')

@section('content')
<!-- Header Summary Banner -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Daftar Transaksi Pesanan</h4>
        <p class="text-muted small mb-0">Kelola status pemesanan, verifikasi transfer bank manual, dan koordinasi COD kurir.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh Data
        </a>
    </div>
</div>

<!-- Summary Status Metric Cards (Real DB Data) -->
<div class="row g-3 mb-4">
    <!-- Total Revenue -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted x-small text-uppercase fw-bold">Pendapatan Selesai</span>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="h4 fw-bold text-dark mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</div>
            <div class="text-muted x-small mt-1">Dari {{ $statusCounts['completed'] }} pesanan completed</div>
        </div>
    </div>

    <!-- All Orders -->
    <div class="col-6 col-sm-6 col-xl-3">
        <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ empty($status) ? 'border-primary shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Semua Pesanan</span>
                    <div class="stat-icon bg-primary-subtle text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-dark mb-0">{{ $statusCounts['all'] }}</div>
                <div class="text-muted x-small mt-1">Total riwayat transaksi</div>
            </div>
        </a>
    </div>

    <!-- Pending Orders -->
    <div class="col-6 col-sm-6 col-xl-3">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ $status === 'pending' ? 'border-warning shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Menunggu (Pending)</span>
                    <div class="stat-icon bg-warning-subtle text-warning">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-warning mb-0">{{ $statusCounts['pending'] }}</div>
                <div class="text-muted x-small mt-1">Perlu verifikasi bayar</div>
            </div>
        </a>
    </div>

    <!-- Processing Orders -->
    <div class="col-6 col-sm-6 col-xl-3">
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="text-decoration-none">
            <div class="stat-card h-100 {{ $status === 'processing' ? 'border-primary shadow-sm' : '' }}">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted x-small text-uppercase fw-bold">Sedang Diproses</span>
                    <div class="stat-icon bg-info-subtle text-info">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
                <div class="h4 fw-bold text-info mb-0">{{ $statusCounts['processing'] }}</div>
                <div class="text-muted x-small mt-1">Dalam packing / kurir</div>
            </div>
        </a>
    </div>
</div>

<!-- Filter and Search Bar -->
<div class="card border-0 bg-white p-3 mb-4 rounded-3 shadow-sm">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control border-start-0"
                       placeholder="Cari kode pesanan, nama pembeli, atau telepon..."
                       value="{{ $search ?? '' }}">
            </div>
        </div>

        <div class="col-md-4">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status Pesanan</option>
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Bayar)</option>
                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Processing (Sedang Diproses)</option>
                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
            </select>
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary flex-grow-1">Filter</button>
            @if($search || $status)
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Table Daftar Pesanan Admin -->
<div class="card border-0 bg-white overflow-hidden rounded-3 shadow-sm">
    <div class="table-responsive">
        <table class="table table-admin align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 190px;">Kode Pesanan</th>
                    <th scope="col">Nama Pembeli</th>
                    <th scope="col">Metode Pembayaran</th>
                    <th scope="col" class="text-end">Total Pembayaran</th>
                    <th scope="col" class="text-center" style="width: 140px;">Status</th>
                    <th scope="col" class="text-center" style="width: 110px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <div class="fw-bold font-monospace text-dark">{{ $order->order_code }}</div>
                            <div class="x-small text-muted">{{ $order->created_at->format('d/m/Y H:i') }} WIB</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark small">{{ $order->user->name ?? 'Pengguna Dihapus' }}</div>
                            <div class="x-small text-muted">
                                @if($order->customer_phone)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $order->customer_phone)) }}" target="_blank" rel="noopener noreferrer" class="text-success text-decoration-none">
                                        <i class="bi bi-whatsapp"></i> {{ $order->customer_phone }}
                                    </a>
                                @else
                                    -
                                @endif
                            </div>
                        </td>
                        <td>
                            @if(str_contains(strtolower($order->payment_method), 'transfer'))
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle small">
                                    <i class="bi bi-bank me-1"></i> {{ $order->payment_method }}
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle small">
                                    <i class="bi bi-truck me-1"></i> {{ $order->payment_method }}
                                </span>
                            @endif
                        </td>
                        <td class="text-end fw-bold text-primary text-nowrap">
                            {{ $order->formatted_total_price }}
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $order->status_badge_class }} small">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 py-1 px-2 small">
                                <i class="bi bi-eye"></i> Kelola
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                Tidak ada data pesanan yang sesuai dengan filter.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
