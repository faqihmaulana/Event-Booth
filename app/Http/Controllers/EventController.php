<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Categori;
use App\Models\Booth;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    // TAMPILKAN EVENT UNTUK ADMIN
    public function indexAdmin()
    {
        $events = Event::all();
        return view('admin.manage-event', compact('events'));
    }

    // TAMPILKAN EVENT UNTUK PENGUNJUNG UMUM, DENGAN BANNER
    public function indexPublic()
    {
        $events = Event::all();
        $categoris = Categori::all();
        $banner = Banner::first();
        $booths = Categori::all();
        return view('pages.event', compact('events', 'banner', 'categoris', 'booths'));
    }

    // SIMPAN EVENT BARU
    public function store(Request $request)
    {
        $data = $request->validate([
            'main_title' => 'required|string|max:255',
            'main_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            
            'category' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'speaker' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        // Simpan main_image jika ada
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('events', 'public');
        }

        Event::create($data);

        return redirect()->back()->with('success', 'Event berhasil disimpan!');
    }

    // UPDATE EVENT YANG ADA
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $data = $request->validate([
            'main_title' => 'required|string|max:255',
            'main_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'main_image' => 'nullable|image|max:2048',
            
            'category' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'speaker' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
        ]);

        // Update main_image jika ada
        if ($request->hasFile('main_image')) {
            if ($event->main_image && Storage::disk('public')->exists($event->main_image)) {
                Storage::disk('public')->delete($event->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->back()->with('success', 'Event berhasil diperbarui!');
    }

    // HAPUS EVENT
    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->main_image && Storage::disk('public')->exists($event->main_image)) {
            Storage::disk('public')->delete($event->main_image);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus!');
    }
}