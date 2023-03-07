<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkingHours;
use App\Models\WorkLocation;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $datas = WorkingHours::first();
        $lokasikerja = WorkLocation::first();
        return view('landing.location-jam.index', compact('datas', 'lokasikerja'));
    }

    public function update_jamkantor(Request $request, $id)
    {
        $datas = WorkingHours::where('id', $id)->first();
        $datas->update([
            'jam_masuk' => $request->input('jam_masuk'),
            'jam_pulang' => $request->input('jam_pulang'),
        ]);
        // return response()->json($datas);
        return redirect()->route('index_setting')->with('status', 'Selamat data jam kerja berhasil di update');
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $lokasi = WorkLocation::findOrFail($id);
        $lokasi->titik_koordinat = $request->work_locations;
        $lokasi->ket = $request->ket;
        $lokasi->save();
        return response()->json($lokasi);
    }
}
