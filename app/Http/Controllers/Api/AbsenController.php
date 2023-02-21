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

    public function absenMasuk(Request $request){
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

    public function absenPulang(Request $request){
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

    function getAllAbsensi(){
        try{
            $absensi = $this->absensiService->getAllAbsensi();
            $datas = [];
            foreach($absensi as $value){
                $fotopulang = $value->foto_pulang == NULL ? NULL: url('storage/absensi/'.$value->foto_pulang);
                $data[] =[
                    'id' => $value->id,
                    'nama' => $value->User->nama,
                    'foto_masuk' => url('storage/absensi/'.$value->foto_masuk),
                    'foto_pulang' => $fotopulang,
                    'tgl' => $value->tgl,
                    'jam_masuk' => $value->jam_masuk,
                    'jam_pulang' => $value->jam_pulang,
                    'status' => $value->status,
                    'koordinat_masuk' => $value->koordinat_masuk,
                    'koordinat_pulang' => $value->koordinat_pulang,
                ];
                $datas = $data;
            }
        }catch(Error $e){
            $absensiData = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
            return response()->json([
                'message' => 'Data Absensi',
                'data' => $datas
            ]);
    }

    function getAbsenById($id){
        try{
            $absensi = $this->absensiService->getAbsenById($id);
            $datas = [];
            foreach($absensi as $value){
                $fotopulang = $value->foto_pulang == NULL ? NULL : url('storage/absensi/'.$value->foto_pulang);
                $data[] = [
                            'id' => $value->id,
                            'nama' => $value->User->nama,
                            'foto_masuk' => url('storage/absensi/'.$value->foto_masuk),
                            'foto_pulang' => $fotopulang,
                            'tgl' => $value->tgl,
                            'jam_masuk' => $value->jam_masuk,
                            'jam_pulang' => $value->jam_pulang,
                            'status' => $value->status,
                            'koordinat_masuk' => $value->koordinat_masuk,
                            'koordinat_pulang' => $value->koordinat_pulang,
                            ];
                $datas = $data;
            }
        }catch(Error $e){
            $absensiData = [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
            return response()->json([
                'message' => 'Data Absensi',
                'data' => $datas
            ]);
    }
}
