<?php
namespace App\Services;
use Auth;
use Carbon\Carbon;
use App\Repositories\AbsensiRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AbsensiService{

    protected $absensiRepository;

    public function __construct(AbsensiRepository $absensiRepository){
        $this->absensiRepository = $absensiRepository;
    }

    function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo){
        $earthRadius = 6371000;
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }

    function insertAbsen($request){
        $dateTime = Carbon::now();
        $jam_kerja = $this->absensiRepository->getJamKerja();
        $data_absen = $this->absensiRepository->dataAbsenMasuk($dateTime);

        $clock_in = $exJamKerja[0];
        $clock_out = $exJamKerja[1];

        $data['status'] = "";
        if(empty($data_absen)){
            if($dateTime->toTimeString() <= $jamPulang){
                $files = $request->file('foto');
                $file_name = 'absen-masuk-'.date('YmdHis-').str_replace(' ', '', $files->getClientOriginalName());
                Storage::disk('local')->putFileAs('public/absensi', $files, $file_name);
                $data['foto'] = $file_name;
                $data['jam_masuk'] = $dateTime->toTimeString();
                $data['jam_pulang'] = null;
                $data['koordinat'] = $request['koordinat'];
                if($dateTime->toTimeString() > $jamMasuk){
                    $data['status'] = 'Terlambat';
                }else{
                    $data['status'] = 'Hadir';
                }
                return  $this->absensiRepository->absenMasuk($data,$dateTime);
                }else{
                    throw new HttpResponseException(response()->json([
                        'message'   => 'Masa Absen Telah Berakhir',
                    ],422));
            }
        }else{
            throw new HttpResponseException(response()->json([
                'message'   => 'Anda Telah Melakukan Absen Hari Ini',
            ],422));
        }
    }

    public function absenMasuk($request){
            $data_validate = $request->all();
            $validator = Validator::make($data_validate, [
                'foto' => 'required',
                'koordinat' => 'required'
            ]);
            if ($validator->fails()) {
                throw new HttpResponseException(response()->json([
                    'Meta' => [
                        'Message' => 'Validasi Error',
                        'Code' => 422,
                        'Status' => 'Failed'
                    ],
                    'Data' => $validator->errors()
                ],422));
            }
            $lokasiKerja = $this->absensiRepository->getLokasiKerja();
            $koordinatUser = explode(',',$request->koordinat);
            $koordinatLokasi = explode(',',$lokasiKerja->titik_koordinat);
            $radius = 100;
            if( $this->haversineGreatCircleDistance($koordinatUser[0],$koordinatUser[1],$koordinatLokasi[0],$koordinatLokasi[1]) <= $radius){
                return $this->insertAbsen($request);
            }else{
                throw new HttpResponseException(response()->json([
                    'message'   => 'Maaf Anda berada Diluar Radius Lokasi Kerja'
                ],500));
            }
    }

    public function absenPulang($request){
        $data_validate = $request->all();
        $validator = Validator::make($data_validate, [
            'foto' => 'required',
            'koordinat' => 'required'
        ]);
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'Meta' => [
                    'Message' => 'Validasi Error',
                    'Code' => 422,
                    'Status' => 'Failed'
                ],
                'Data' => $validator->errors()
            ],422));
        }
        $dateTime = Carbon::now();
        $data_absen = $this->absensiRepository->dataAbsenPulang($dateTime);
        $jam_kerja = $this->absensiRepository->getJamKerja();

        $exJamKerja = explode(',',$jam_kerja);
        $jamMasuk = $exJamKerja[0];
        $jamPulang = $exJamKerja[1];

        if($dateTime->toTimeString() <= $jamPulang){
            throw new HttpResponseException(response()->json([
                'message'   => 'Belum Bisa Melakukan Absen Pulang',
            ],500));
        }

        if(!empty($data_absen) && ($data_absen->status=='Hadir' || $data_absen->status =='Terlambat')){
                $lokasiKantor = $this->absensiRepository->getLokasiKerja();
                $koordinatUser = explode(',',$request->koordinat);
                $koordinatKantor = explode(',',$lokasiKantor->titik_koordinat);
                $radius = 100;
                if( $this->haversineGreatCircleDistance($koordinatUser[0],$koordinatUser[1],$koordinatKantor[0],$koordinatKantor[1]) <= $radius){
                    $files = $request->file('foto');
                    Storage::delete('/public/absensi/'. $data_absen->foto_pulang);
                    $file_name = 'absen-pulang-'.date('YmdHis-').str_replace(' ', '', $files->getClientOriginalName());
                    Storage::disk('local')->putFileAs('public/absensi', $files, $file_name);
                    $data['foto'] = $file_name;
                    $data['jam_pulang'] = $dateTime->toTimeString();
                    $data['koordinat'] = $request->koordinat;
                    return  $this->absensiRepository->absenPulang($data,$dateTime);
                }else{
                    throw new HttpResponseException(response()->json([
                        'message'   => 'Maaf Anda berada Diluar Radius Lokasi Kerja'
                    ],500));
                }
        }else{
            throw new HttpResponseException(response()->json([
                'message'   => 'Anda Belum Melakukan Absen Masuk Hari Ini',
            ],500));
        }

    }


    public function getAllAbsensi(){
        return $this->absensiRepository->getAllAbsensi();
    }

    public function getAbsenById($id){
        return $this->absensiRepository->getAbsenById($id);
    }
}
