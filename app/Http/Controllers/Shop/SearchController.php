<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $categoryId = $request->get('category');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sort = $request->get('sort', 'default');

        $products = Product::query()
            ->when($query, function($q) use ($query) {
                return $q->search($query);
            })
            ->filterByCategory($categoryId)
            ->filterByPrice($minPrice, $maxPrice);

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products->orderBy('price', 'desc');
                break;
            case 'newest':
                $products->orderBy('created_at', 'desc');
                break;
            case 'rating':
                $products->withAvg('reviews', 'rating')
                    ->orderByDesc('reviews_avg_rating');
                break;
            default:
                $products->orderBy('name', 'asc');
                break;
        }

        $products = $products->with(['category', 'reviews'])
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();

        // Get price range for filters
        $priceRange = Product::query()
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return view('shop.search.index', compact(
            'products',
            'categories',
            'priceRange',
            'query',
            'categoryId',
            'minPrice',
            'maxPrice',
            'sort'
        ));
    }

    public function suggestions(Request $request)
    {
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = Product::query()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->select('name', 'slug')
            ->limit(5)
            ->get()
            ->map(function ($product) use ($query) {
                return [
                    'name' => $product->name,
                    'url' => route('products.show', $product->slug),
                    'highlighted' => preg_replace(
                        '/(' . preg_quote($query, '/') . ')/i',
                        '<strong>$1</strong>',
                        $product->name
                    )
                ];
            });

        return response()->json($suggestions);
    }
}
