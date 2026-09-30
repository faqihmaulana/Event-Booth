<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Categori;
use App\Models\Event;
use Illuminate\Http\Request;

class CategoriController extends Controller
{
    // Tampilkan daftar booth untuk admin (Categori Booth)
    public function index()
    {
        $categoris = Categori::with('event')->get(); // Load relasi event jika ada
        $events = Event::all(); // Untuk dropdown pilihan event
        return view('admin.categori-booth', compact('categoris', 'events'));
    }

    // Simpan booth baru (admin)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'facilities' => 'nullable|string',
            'event_id' => 'required|exists:events,id', // Validasi event_id wajib ada dan valid
        ]);

        Categori::create($request->only('name', 'price', 'subtitle', 'facilities', 'event_id'));

        return redirect()->route('categori-booth')->with('success', 'Booth berhasil ditambahkan.');
    }

    // Update booth berdasarkan id (admin)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'facilities' => 'nullable|string',
            'event_id' => 'required|exists:events,id', // Validasi event_id wajib ada dan valid
        ]);

        $booth = Categori::findOrFail($id);
        $booth->update($request->only('name', 'price', 'subtitle', 'facilities', 'event_id'));

        return redirect()->route('categori-booth')->with('success', 'Booth berhasil diupdate.');
    }

    // Hapus booth (admin)
    public function destroy($id)
    {
        $booth = Categori::findOrFail($id);
        $booth->delete();

        return redirect()->route('categori-booth')->with('success', 'Booth berhasil dihapus.');
    }

    // Fungsi booking untuk user/publik menampilkan daftar booth yang bisa dipesan
    public function booking($eventId = null)
    {
        $banner = Banner::first();
        
        // Jika event_id diberikan, filter booth berdasarkan event dengan relasi
        if ($eventId) {
            $booths = Categori::with('event')->where('event_id', $eventId)->get();
            $currentEvent = Event::findOrFail($eventId); // Ambil data event untuk informasi tambahan
        } else {
            // Jika tidak, ambil semua booth dengan relasi event
            $booths = Categori::with('event')->get();
            $currentEvent = null;
        }
        
        return view('pages.booking-booth', compact('booths', 'banner', 'currentEvent'));
    }
}