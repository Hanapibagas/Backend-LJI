<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $pengumuman = Announcement::all();
        return view('landing.pengumuman.index', compact('pengumuman'));
    }

    public function post(Request $request)
    {
        $this->validate($request, [
            "title" => 'required',
            "picture" => 'required'
        ]);

        if ($request->file('picture')) {
            $gambar = $request->file('picture')->store('pengumuman', 'public');
        }

        Announcement::create([
            "title" => $request->input('title'),
            "picture" => $gambar
        ]);

        return redirect()->route('index_pengumuman')->with('status', 'Selamat data pengumuman berhasil di kirim');
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Announcement::where('id', $id)->first();

        if ($request->file('picture')) {
            $file = $request->file('picture')->store('pengumuman', 'public');
            if ($pengumuman->picture && file_exists(storage_path('app/public/' . $pengumuman->picture))) {
                Storage::delete('public/' . $pengumuman->picture);
                $file = $request->file('picture')->store('pengumuman', 'public');
            }
        }

        if ($request->file('picture') === null) {
            $file = $pengumuman->picture;
        }

        $pengumuman->update([
            "title" => $request->input('title'),
            "picture" => $file
        ]);

        return redirect()->route('index_pengumuman')->with('status', 'Selamat data pengumuman berhasil di update');
    }

    public function destroy($id)
    {
        $delete = Announcement::where('id', $id)->first();
        if ($delete->picture && file_exists(storage_path('app/public/' . $delete->picture))) {
            Storage::delete('public/' . $delete->picture);
        }

        $delete->delete();
        return redirect()->route('index_pengumuman')->with('status', 'Selamat data pengumuman berhasil di hapus');
    }
}
