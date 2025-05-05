<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Comparison;
use App\Models\Product;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $comparisons = auth()->user()->comparisons()
            ->with(['product' => function($query) {
                $query->withAvg('reviews', 'rating')
                    ->withCount('reviews');
            }])
            ->get();

        $products = $comparisons->pluck('product');
        $specs = Comparison::getCommonSpecs($products);

        return view('shop.comparison.index', compact('products', 'specs'));
    }

    public function toggle(Product $product)
    {
        $userId = auth()->id();

        if (Comparison::where('user_id', $userId)->where('product_id', $product->id)->exists()) {
            // Remove product from comparison
            Comparison::removeProduct($userId, $product->id);
            $message = 'Đã xóa sản phẩm khỏi danh sách so sánh';
        } else {
            // Add product to comparison
            if (Comparison::addProduct($userId, $product->id)) {
                $message = 'Đã thêm sản phẩm vào danh sách so sánh';
            } else {
                return back()->with('error', 'Danh sách so sánh đã đầy (tối đa 4 sản phẩm)');
            }
        }

        return back()->with('success', $message);
    }

    public function destroy(Comparison $comparison)
    {
        if ($comparison->user_id !== auth()->id()) {
            abort(403);
        }

        $comparison->delete();

        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách so sánh');
    }

    public function clear()
    {
        Comparison::clearList(auth()->id());

        return back()->with('success', 'Đã xóa toàn bộ danh sách so sánh');
    }

    public function compareNow(Request $request)
    {
        $productIds = $request->get('products', []);

        if (count($productIds) < 2) {
            return back()->with('error', 'Vui lòng chọn ít nhất 2 sản phẩm để so sánh');
        }

        if (count($productIds) > 4) {
            return back()->with('error', 'Bạn chỉ có thể so sánh tối đa 4 sản phẩm cùng lúc');
        }

        $products = Product::whereIn('id', $productIds)
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get();

        if ($products->count() !== count($productIds)) {
            return back()->with('error', 'Không tìm thấy một hoặc nhiều sản phẩm');
        }

        $specs = Comparison::getCommonSpecs($products);

        return view('shop.comparison.compare', compact('products', 'specs'));
    }
}
