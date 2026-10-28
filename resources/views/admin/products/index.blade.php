@extends('layouts.admin')

@section('title', 'Katalog Produk — Admin LaraShop')
@section('page-title', 'Katalog & Stok Produk')

@section('content')
<!-- Header Summary Banner -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-dark mb-1">Manajemen Produk Marketplace</h4>
        <p class="text-muted small mb-0">Kelola katalog multi-kategori, perbarui harga, pantau level stok gudang, dan atur etalase toko.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm px-3 py-2 fw-semibold">
            <i class="bi bi-plus-lg me-1"></i> Tambah Produk Baru
        </a>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-4">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted x-small text-uppercase fw-bold">Total Produk</span>
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-tags"></i>
                </div>
            </div>
            <div class="h4 fw-bold text-dark mb-0">{{ $totalProducts }}</div>
            <div class="text-muted x-small mt-1">Item aktif di etalase marketplace</div>
        </div>
    </div>

    <div class="col-6 col-sm-4">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted x-small text-uppercase fw-bold">Total Stok Fisik</span>
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
            <div class="h4 fw-bold text-dark mb-0">{{ number_format($totalStock, 0, ',', '.') }} unit</div>
            <div class="text-muted x-small mt-1">Akumulasi seluruh gudang</div>
        </div>
    </div>

    <div class="col-6 col-sm-4">
        <div class="stat-card h-100">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted x-small text-uppercase fw-bold">Stok Menipis (≤ 5)</span>
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
            </div>
            <div class="h4 fw-bold text-warning mb-0">{{ $lowStockCount }}</div>
            <div class="text-muted x-small mt-1">Perlu restock segera</div>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="card border-0 bg-white p-3 mb-4 rounded-3 shadow-sm">
    <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
        <div class="col-md-6">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-light border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control border-start-0"
                       placeholder="Cari nama produk, deskripsi..."
                       value="{{ $search ?? '' }}">
            </div>
        </div>
        <div class="col-md-4">
            <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ ($category ?? '') === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary flex-grow-1">Filter</button>
            @if($search || $category)
                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- Table Produk Admin -->
<div class="card border-0 bg-white overflow-hidden rounded-3 shadow-sm">
    <div class="table-responsive">
        <table class="table table-admin align-middle table-hover mb-0">
            <thead>
                <tr>
                    <th scope="col" style="width: 70px;">Foto</th>
                    <th scope="col">Nama & Slug</th>
                    <th scope="col">Kategori</th>
                    <th scope="col">Harga Jual</th>
                    <th scope="col" class="text-center" style="width: 140px;">Stok Gudang</th>
                    <th scope="col" class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($products as $product)
                    <tr>
                        <td>
                            <div style="width: 48px; height: 48px; background-color: #F8FAFC; border-radius: 6px; overflow: hidden;" class="border">
                                <img src="{{ $product->image_url }}"
                                     class="w-100 h-100 object-fit-cover"
                                     alt="{{ $product->name }}"
                                     onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small">{{ $product->name }}</div>
                            <div class="x-small text-muted font-monospace">/{{ $product->slug }}</div>
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border small">
                                {{ $product->category ?? 'Umum' }}
                            </span>
                        </td>
                        <td>
                            @if($product->discount_percentage > 0)
                                <div class="fw-bold text-primary small">{{ $product->formatted_final_price }}</div>
                                <div class="x-small">
                                    <span class="text-muted text-decoration-line-through">{{ $product->formatted_price }}</span>
                                    <span class="badge bg-danger-subtle text-danger px-1 py-0 ms-1">-{{ $product->discount_percentage }}%</span>
                                </div>
                            @else
                                <div class="fw-bold text-primary small">{{ $product->formatted_price }}</div>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($product->stock > 10)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                                    {{ $product->stock }} unit (Aman)
                                </span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 small">
                                    {{ $product->stock }} unit (Menipis)
                                </span>
                            @else
                                <span class="badge bg-danger text-white px-2 py-1 small">
                                    Habis (0)
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit Produk">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Hapus produk \'{{ $product->name }}\' dari katalog?');" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hapus Produk">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-tags fs-2 d-block mb-2 text-secondary"></i>
                                Belum ada data produk atau tidak ada yang cocok dengan pencarian.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="card-footer bg-white border-top py-3 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection
