<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with optional search.
     */
    /**
     * Display a listing of products with optional search, category, price, and sorting.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'latest');
        $category = $request->query('category');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $inStockOnly = $request->boolean('in_stock');

        $availableCategories = [
            'Elektronik',
            'Fashion',
            'Kecantikan',
            'Rumah Tangga',
            'Makanan Kemasan',
            'Olahraga',
            'Aksesori',
            'Otomotif',
        ];

        $products = Product::query()
            ->when($search, function ($query, $search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%");
                });
            })
            ->when($category, function ($query, $category): void {
                $query->where(function ($q) use ($category): void {
                    $q->where('category', $category)
                        ->orWhere('name', 'like', "%{$category}%")
                        ->orWhere('description', 'like', "%{$category}%");
                });
            })
            ->when($minPrice !== null && is_numeric($minPrice), fn ($q) => $q->where('price', '>=', (float) $minPrice))
            ->when($maxPrice !== null && is_numeric($maxPrice), fn ($q) => $q->where('price', '<=', (float) $maxPrice))
            ->when($inStockOnly, fn ($q) => $q->where('stock', '>', 0))
            ->when($sort === 'price_asc', fn ($q) => $q->orderBy('price', 'asc'))
            ->when($sort === 'price_desc', fn ($q) => $q->orderBy('price', 'desc'))
            ->when($sort === 'popular', fn ($q) => $q->orderBy('stock', 'asc'))
            ->when($sort === 'latest' || ! in_array($sort, ['price_asc', 'price_desc', 'popular']), fn ($q) => $q->latest('id'))
            ->paginate(12)
            ->withQueryString();

        return view('products.index', compact(
            'products',
            'search',
            'sort',
            'category',
            'minPrice',
            'maxPrice',
            'inStockOnly',
            'availableCategories'
        ));
    }

    /**
     * Display the specified product detail with related products in the same category.
     */
    public function show(Product $product): View
    {
        $relatedProducts = Product::where('id', '!=', $product->id)
            ->when($product->category, fn ($q) => $q->where('category', $product->category))
            ->inRandomOrder()
            ->limit(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $fallback = Product::where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->inRandomOrder()
                ->limit(4 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->merge($fallback);
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }
}
