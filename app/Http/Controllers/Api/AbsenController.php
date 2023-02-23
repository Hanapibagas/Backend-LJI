<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AbsensiService;

class AbsenController extends Controller
{
    protected $absensiService;

    public function __construct(AbsensiService $absensiService){
        $this->absensiService = $absensiService;
    }

    public function absentEntry(request $request){
        try{
            $data = $this->absensiService->absenMasuk($request);
        }catch(Error $e){
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

    public function absentHome(Request $request){
        try{
            $data = $this->absensiService->absenPulang($request);
        }catch(Error $e){
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
}
