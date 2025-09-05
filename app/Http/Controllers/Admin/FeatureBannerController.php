<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeatureBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeatureBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = FeatureBanner::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        FeatureBanner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = FeatureBanner::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $banner = FeatureBanner::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            
            $imagePath = $request->file('image')->store('banners', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $banner = FeatureBanner::findOrFail($id);
        
        // Delete image file
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully!');
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus($id)
    {
        $banner = FeatureBanner::findOrFail($id);
        $banner->update(['is_active' => !$banner->is_active]);

        $status = $banner->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.banners.index')->with('success', "Banner {$status} successfully!");
    }
}
