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
        // $lokasikerja = WorkLocation::first();
        return view('landing.location-jam.index', compact('datas'));
    }

    public function update_jamkantor(Request $request)
    {
        $id = $request->id;
        $jamKerja = WorkingHours::findOrFail($id);
        $jamKerja->clock_in = $request->clock_in;
        $jamKerja->home_time = $request->home_time;
        $jamKerja->save();
        return response()->json($jamKerja);
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $lokasi = WorkingHours::findOrFail($id);
        $lokasi->location = $request->location;
        $lokasi->ket = $request->ket;
        $lokasi->save();
        return response()->json($lokasi);
    }
}
