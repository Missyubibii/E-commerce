<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->has('category')) {
            $products->whereHas('category', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        if ($request->has('brand_id')) {
            $products->where('brand_id', $request->brand_id);
        }

        if ($request->has('min_price')) {
            $products->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $products->where('price', '<=', $request->max_price);
        }

        if ($request->has('stock_status')) {
            if ($request->stock_status == 'instock') {
                $products->where('stock_quantity', '>', 0);
            } else {
                $products->where('stock_quantity', 0);
            }
        }

        $products = $products->paginate(12);

        $categories = Category::all();
        $brands = Brand::all();

        return view('welcome', compact('products', 'categories', 'brands'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('shop.products.show', compact('product'));
    }

    public function getBrandsByCategory(Category $category)
    {
        return response()->json($category->brands);
    }
}
