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
        $banners = Banner::all();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'required',
            'link' => 'nullable|url',
            'is_active' => 'boolean',
            'position' => 'integer',
        ]);

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->link = $request->link;
        $banner->is_active = $request->is_active;
        $banner->position = $request->position;
        $banner->image = str_replace('img/banner/', '', $request->image);

        $banner->save();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Quảng cáo đã được thêm mới.');
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
        $request->validate([
            'title' => 'required',
            'image' => 'required',
            'link' => 'nullable|url',
            'is_active' => 'boolean',
            'position' => 'integer',
        ]);

        $banner->title = $request->title;
        $banner->link = $request->link;
        $banner->is_active = $request->is_active;
        $banner->position = $request->position;
        $banner->image = $request->image;

        $banner->save();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Quảng cáo đã được cập nhật.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'QUảng cáo đã được xóa.');
    }
}
