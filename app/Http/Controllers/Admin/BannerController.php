<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::latest()->paginate(10);
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url',
            'is_active' => 'required|boolean',
            'position' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('img/banner'), $imageName);
            $data['image'] = $imageName;
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Quảng cáo đã được thêm mới!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return view('admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'nullable|url',
            'is_active' => 'required|boolean',
            'position' => 'required|integer',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && file_exists(public_path('img/banner/' . $banner->image))) {
                unlink(public_path('img/banner/' . $banner->image));
            }

            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('img/banner'), $imageName);
            $data['image'] = $imageName;
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Quảng cáo đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Quảng cáo đã được xóa!');
    }
}
