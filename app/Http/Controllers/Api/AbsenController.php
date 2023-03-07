<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Absen;
use Illuminate\Http\Request;
use App\Services\AbsensiService;
use App\Http\Controllers\Controller;
use Error;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    protected $absensiService;

    public function __construct(AbsensiService $absensiService)
    {
        $this->absensiService = $absensiService;
    }

    public function absentEntry(request $request)
    {
        try {
            $data = $this->absensiService->absenMasuk($request);
        } catch (Error $e) {
            $data = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
        return response()->json([
            'message' => 'Berhasil Melakukan Absen Masuk',
            'data' => $data
        ]);
    }

    public function absentHome(Request $request)
    {
        try {
            $data = $this->absensiService->absenPulang($request);
        } catch (Error $e) {
            $daata = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
        return response()->json([
            'message' => 'Berhasil Melakukan Absen Pulang',
            'data' => $data
        ]);
    }

    public function getWeeklyAbsence()
    {
        $data = Absen::select("*")
            ->whereBetween(
                'created_at',
                [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]
            )
            ->where('user_id', Auth::user()->id)
            ->get();

        return response()->json([
            'message' => 'Daftar absen minggu ini',
            'data' => $data
        ]);
    }

    public function getMonthlyAbsence()
    {
        $data = Absen::select('*')
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('user_id', Auth::user()->id)
            ->get();
        return response()->json([
            'message' => 'Daftar absen bulan ini',
            'data' => $data
        ]);
    }
}
