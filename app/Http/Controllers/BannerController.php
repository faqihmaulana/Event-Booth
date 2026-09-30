<?php

namespace App\Http\Controllers;

use App\Models\Categori;
use App\Models\Gallery;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Booth;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    /**
     * Display banner management page
     */
    public function index()
    {
        $banner = Banner::with('galleries')->first();
        if (!$banner) {
            // Create default banner if none exists
            $banner = new Banner();
            $banner->title = 'Tea Fiesta & Food Bazar';
            $banner->subtitle = 'Bergabunglah bersama kami untuk acara terbesar tahun ini!';
            $banner->event_date = '2025-06-26';
            $banner->event_location = 'Jl. Pancasila, Alun-alun Tegal';
            $banner->countdown_enabled = true;
            // Don't save, just provide default values for view
        }
        return view('admin.manage-banner', compact('banner'));
    }

    /**
     * Update the banner information
     */
    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'event_date' => 'nullable|date',
            'event_location' => 'nullable|string|max:255',
            'countdown_enabled' => 'required|boolean',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_titles.*' => 'nullable|string|max:255',
            'gallery_descriptions.*' => 'nullable|string|max:500',
            'remove_gallery_ids' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $banner = Banner::first();

            if (!$banner) {
                $banner = new Banner();
            }

            $banner->title = $request->title;
            $banner->subtitle = $request->subtitle;
            $banner->event_date = $request->event_date;
            $banner->event_location = $request->event_location;
            $banner->countdown_enabled = $request->countdown_enabled;

            if ($request->hasFile('banner_image')) {
                // Delete old image if exists
                if ($banner->image_path && Storage::exists('public/banners/' . $banner->image_path)) {
                    Storage::delete('public/banners/' . $banner->image_path);
                }

                // Store new image
                $imageName = time() . '.' . $request->banner_image->extension();
                $request->banner_image->storeAs('public/banners', $imageName);
                $banner->image_path = $imageName;
            }

            $banner->save();

            // Handle gallery images removal
            if ($request->remove_gallery_ids) {
                $removeIds = explode(',', $request->remove_gallery_ids);
                $removeIds = array_filter($removeIds);

                if (!empty($removeIds)) {
                    $galleriesToRemove = Gallery::whereIn('id', $removeIds)->get();
                    foreach ($galleriesToRemove as $gallery) {
                        if ($gallery->image_path && Storage::exists('public/galleries/' . $gallery->image_path)) {
                            Storage::delete('public/galleries/' . $gallery->image_path);
                        }
                        $gallery->delete();
                    }
                }
            }

            // Handle new gallery images
            if ($request->hasFile('gallery_images')) {
                $maxSortOrder = $banner->galleries()->max('sort_order') ?? 0;

                foreach ($request->file('gallery_images') as $index => $file) {
                    if ($file->isValid()) {
                        $imageName = time() . '_' . $index . '.' . $file->extension();
                        $file->storeAs('public/galleries', $imageName);

                        Gallery::create([
                            'banner_id' => $banner->id,
                            'image_path' => $imageName,
                            'title' => $request->gallery_titles[$index] ?? null,
                            'alt_text' => $request->gallery_titles[$index] ?? 'Gallery Image',
                            'description' => $request->gallery_descriptions[$index] ?? null,
                            'sort_order' => $maxSortOrder + $index + 1,
                            'is_active' => true,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('manage-banner')->with('success', 'Banner dan galeri berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('manage-banner')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get banner data for homepage
     */
    public function getHomepageBanner()
    {
        $events = Event::latest()->get();
        $banner = Banner::with('activeGalleries')->first();
        $booths = Categori::all();
        return view('pages.home', compact('banner', 'booths', 'events'));
    }

    /**
     * Delete specific gallery image via AJAX
     */
    public function deleteGalleryImage($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);

            // Delete image file
            if ($gallery->image_path && Storage::exists('public/galleries/' . $gallery->image_path)) {
                Storage::delete('public/galleries/' . $gallery->image_path);
            }

            // Delete database record
            $gallery->delete();

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus gambar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update gallery information
     */
    public function updateGallery(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $gallery = Gallery::findOrFail($id);

            $gallery->title = $request->title;
            $gallery->description = $request->description;
            $gallery->alt_text = $request->title ?? 'Gallery Image';

            // Handle image update
            if ($request->hasFile('image')) {
                // Delete old image
                if ($gallery->image_path && Storage::exists('public/galleries/' . $gallery->image_path)) {
                    Storage::delete('public/galleries/' . $gallery->image_path);
                }

                // Store new image
                $imageName = time() . '_' . $id . '.' . $request->image->extension();
                $request->image->storeAs('public/galleries', $imageName);
                $gallery->image_path = $imageName;
            }

            $gallery->save();

            return response()->json([
                'success' => true,
                'message' => 'Galeri berhasil diperbarui',
                'data' => $gallery
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui galeri: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Add multiple gallery images
     */
    public function addGalleryImages(Request $request)
    {
        $request->validate([
            'gallery_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_titles.*' => 'nullable|string|max:255',
            'gallery_descriptions.*' => 'nullable|string|max:500',
        ]);

        try {
            $banner = Banner::first();
            if (!$banner) {
                return response()->json([
                    'success' => false,
                    'message' => 'Banner tidak ditemukan'
                ]);
            }

            $maxSortOrder = $banner->galleries()->max('sort_order') ?? 0;
            $addedGalleries = [];

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $file) {
                    if ($file->isValid()) {
                        $imageName = time() . '_' . $index . '.' . $file->extension();
                        $file->storeAs('public/galleries', $imageName);

                        $gallery = Gallery::create([
                            'banner_id' => $banner->id,
                            'image_path' => $imageName,
                            'title' => $request->gallery_titles[$index] ?? null,
                            'alt_text' => $request->gallery_titles[$index] ?? 'Gallery Image',
                            'description' => $request->gallery_descriptions[$index] ?? null,
                            'sort_order' => $maxSortOrder + $index + 1,
                            'is_active' => true,
                        ]);

                        $addedGalleries[] = $gallery;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($addedGalleries) . ' gambar berhasil ditambahkan',
                'data' => $addedGalleries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan gambar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update gallery order
     */
    public function updateGalleryOrder(Request $request)
    {
        try {
            $request->validate([
                'gallery_ids' => 'required|array',
                'gallery_ids.*' => 'exists:galleries,id'
            ]);

            foreach ($request->gallery_ids as $index => $id) {
                Gallery::where('id', $id)->update(['sort_order' => $index + 1]);
            }

            return response()->json(['success' => true, 'message' => 'Urutan galeri berhasil diperbarui']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()]);
        }
    }

    public function about()
    {
        $banner = Banner::with('activeGalleries')->first();
        return view('pages.about', compact('banner'));
    }

    public function bookingBooth()
    {
        $banner = Banner::with('activeGalleries')->first();
        return view('pages.booking-booth', compact('banner'));
    }

    public function contact()
    {
        $banner = Banner::with('activeGalleries')->first();
        return view('pages.contact', compact('banner'));
    }

    /**
     * Get specific gallery data for editing
     */
    public function getGallery($id)
    {
        try {
            $gallery = Gallery::findOrFail($id);

            return response()->json([
                'success' => true,
                'id' => $gallery->id,
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image_path' => $gallery->image_path,
                'alt_text' => $gallery->alt_text
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gallery tidak ditemukan'
            ], 404);
        }
    }
}