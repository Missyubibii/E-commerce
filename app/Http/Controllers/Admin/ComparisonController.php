<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comparison;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{

    public function index()
    {
        $comparisons = Comparison::with(['user', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.comparisons.index', compact('comparisons'));
    }

    public function destroy(Comparison $comparison)
    {
        $comparison->delete();
        return back()->with('success', 'Đã xóa sản phẩm khỏi danh sách so sánh');
    }

    public function clearAll()
    {
        Comparison::truncate();
        return back()->with('success', 'Đã xóa tất cả danh sách so sánh');
    }
}
