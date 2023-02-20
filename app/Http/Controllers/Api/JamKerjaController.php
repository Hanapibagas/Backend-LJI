<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasukKerja;
use App\Models\PulangKerja;
use App\Models\RiwayatAbsensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JamKerjaController extends Controller
{
    public function masuk_kerja(Request $request)
    {
        Validator::make($request->all(), [
            'jam_masuk' => 'required',
            'foto' => 'required',
        ]);

        if ($request->file('foto')) {
            $file = $request->file('foto')->store('jam-masuk', 'public');
        }

        $masuk_kerja = MasukKerja::create([
            'user_id' => Auth::user()->id,
            'jam_masuk' =>  $request->input('jam_masuk'),
            'foto' =>  $file,
        ]);

        RiwayatAbsensi::create([
            'user_id' => Auth::user()->id,
            'masuk_kerja_id' => $masuk_kerja->id,
            // 'tanggal' => $request->input('tanggal')
        ]);

        return response()->json(['success' => $masuk_kerja]);
    }

    public function pulang_kerja(Request $request)
    {
        Validator::make($request->all(), [
            'jam_pulang' => 'required',
            'foto' => 'required',
        ]);

        if ($request->file('foto')) {
            $file = $request->file('foto')->store('jam-pulang', 'public');
        }

        $pulang_kerja = PulangKerja::create([
            'user_id' => Auth::user()->id,
            'jam_pulang' =>  $request->input('jam_pulang'),
            'foto' =>  $file,
        ]);

        RiwayatAbsensi::create([
            'user_id' => Auth::user()->id,
            'pulang_kerja_id' => $pulang_kerja->id,
            // 'tanggal' => $request->input('tanggal')
        ]);

        return response()->json(['success' => $pulang_kerja]);
    }
}
