<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReport;
use App\Models\User;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = DailyReport::all();
        // $laporan = User::all();
        // return response()->json($laporan);
        return view('landing.laporan-harian.index', compact('laporan'));
    }
}
