<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the products for admin management.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $category = $request->query('category');

        $products = Product::query()
            ->when($search, function ($query, $search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category, fn ($q) => $q->where('category', $category))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $lowStockCount = Product::where('stock', '<=', 5)->count();

        $categories = [
            'Elektronik',
            'Fashion',
            'Kecantikan',
            'Rumah Tangga',
            'Makanan Kemasan',
            'Olahraga',
            'Aksesori',
            'Otomotif',
        ];

        return view('admin.products.index', compact('products', 'search', 'category', 'totalProducts', 'totalStock', 'lowStockCount', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = [
            'Elektronik',
            'Fashion',
            'Kecantikan',
            'Rumah Tangga',
            'Makanan Kemasan',
            'Olahraga',
            'Aksesori',
            'Otomotif',
        ];

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'discount_percentage.integer' => 'Persentase diskon harus berupa angka.',
            'discount_percentage.min' => 'Persentase diskon minimal 0%.',
            'discount_percentage.max' => 'Persentase diskon maksimal 100%.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'image.url' => 'Format URL gambar tidak valid.',
        ]);

        $slugBase = Str::slug($validated['name']);
        $slug = $slugBase;
        $counter = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$slugBase}-{$counter}";
            $counter++;
        }

        Product::create([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? 'Umum',
            'slug' => $slug,
            'price' => $validated['price'],
            'discount_percentage' => $validated['discount_percentage'] ?? 0,
            'stock' => $validated['stock'],
            'image' => $validated['image'] ?? 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=600&q=80',
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$validated['name']}' berhasil ditambahkan ke katalog!");
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = [
            'Elektronik',
            'Fashion',
            'Kecantikan',
            'Rumah Tangga',
            'Makanan Kemasan',
            'Olahraga',
            'Aksesori',
            'Otomotif',
        ];

        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_percentage' => ['nullable', 'integer', 'min:0', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'url', 'max:500'],
            'description' => ['nullable', 'string', 'max:2000'],
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka.',
            'discount_percentage.integer' => 'Persentase diskon harus berupa angka.',
            'discount_percentage.min' => 'Persentase diskon minimal 0%.',
            'discount_percentage.max' => 'Persentase diskon maksimal 100%.',
            'stock.required' => 'Jumlah stok wajib diisi.',
            'image.url' => 'Format URL gambar tidak valid.',
        ]);

        if ($validated['name'] !== $product->name) {
            $slugBase = Str::slug($validated['name']);
            $slug = $slugBase;
            $counter = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = "{$slugBase}-{$counter}";
                $counter++;
            }
            $product->slug = $slug;
        }

        $product->name = $validated['name'];
        if (isset($validated['category'])) {
            $product->category = $validated['category'];
        }
        $product->price = $validated['price'];
        $product->discount_percentage = $validated['discount_percentage'] ?? 0;
        $product->stock = $validated['stock'];
        if (! empty($validated['image'])) {
            $product->image = $validated['image'];
        }
        $product->description = $validated['description'] ?? null;
        $product->save();

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Produk '{$name}' berhasil dihapus dari katalog!");
    }
}
