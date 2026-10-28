@extends('layouts.admin')

@section('title', 'Edit Produk — ' . $product->name . ' — Admin')
@section('page-title', 'Edit Produk')

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb small">
        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-decoration-none text-muted">Katalog Produk</a></li>
        <li class="breadcrumb-item active text-dark fw-semibold" aria-current="page">Edit: {{ Str::limit($product->name, 35) }}</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card border-0 bg-white p-4 p-md-5 rounded-3 shadow-sm">
            <div class="d-flex align-items-center gap-2 mb-4 pb-3 border-bottom">
                <span class="p-2 rounded-2 text-primary" style="background-color: var(--primary-subtle);">
                    <i class="bi bi-pencil-square fs-4"></i>
                </span>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Ubah Data Produk</h5>
                    <p class="text-muted small mb-0">Perbarui harga, ketersediaan stok, deskripsi, atau foto produk.</p>
                </div>
            </div>

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-4">
                    <!-- Left: Form inputs -->
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label for="name" class="form-label small fw-semibold text-secondary">
                                Nama Produk <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label small fw-semibold text-secondary">
                                Kategori Produk <span class="text-danger">*</span>
                            </label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $product->category) === $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-sm-4">
                                <label for="price" class="form-label small fw-semibold text-secondary">
                                    Harga Jual (Rp) <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="price"
                                       id="price"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', (int)$product->price) }}"
                                       min="0"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label for="discount_percentage" class="form-label small fw-semibold text-secondary">
                                    Persentase Diskon (%)
                                </label>
                                <input type="number"
                                       name="discount_percentage"
                                       id="discount_percentage"
                                       class="form-control @error('discount_percentage') is-invalid @enderror"
                                       placeholder="0"
                                       value="{{ old('discount_percentage', $product->discount_percentage ?? 0) }}"
                                       min="0"
                                       max="100">
                                @error('discount_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-sm-4">
                                <label for="stock" class="form-label small fw-semibold text-secondary">
                                    Jumlah Stok Gudang <span class="text-danger">*</span>
                                </label>
                                <input type="number"
                                       name="stock"
                                       id="stock"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       value="{{ old('stock', $product->stock) }}"
                                       min="0"
                                       required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label small fw-semibold text-secondary">
                                URL Gambar Foto Produk
                            </label>
                            <input type="url"
                                   name="image"
                                   id="imageUrlInput"
                                   class="form-control @error('image') is-invalid @enderror"
                                   value="{{ old('image', $product->image) }}"
                                   oninput="updateImagePreview(this.value)">
                            <div class="form-text x-small text-muted">Mendukung link gambar langsung (Unsplash, CDN, dsb).</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label small fw-semibold text-secondary">
                                Deskripsi & Spesifikasi Produk
                            </label>
                            <textarea name="description"
                                      id="description"
                                      rows="4"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Right: Live Image Preview -->
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-secondary d-block">
                            Preview Foto (Rasio 1:1)
                        </label>
                        <div class="card border rounded-3 overflow-hidden text-center bg-light" style="aspect-ratio: 1/1;">
                            <img id="imagePreview"
                                 src="{{ old('image', $product->image_url) }}"
                                 alt="{{ $product->name }}"
                                 class="w-100 h-100 object-fit-cover"
                                 onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                        </div>
                        <div class="text-muted x-small text-center mt-2">
                            Slug URL saat ini: <code class="text-primary font-monospace">{{ $product->slug }}</code>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">
                        <i class="bi bi-check2 me-1"></i> Perbarui Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateImagePreview(url) {
        const preview = document.getElementById('imagePreview');
        if (url && url.trim().length > 5) {
            preview.src = url;
        } else {
            preview.src = '{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';
        }
    }
</script>
@endpush
@endsection
