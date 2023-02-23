<?php

namespace App\Services;

use Auth;
use Carbon\Carbon;
use App\Repositories\AbsensiRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AbsensiService
{

    protected $absensiRepository;

    public function __construct(AbsensiRepository $absensiRepository)
    {
        $this->absensiRepository = $absensiRepository;
    }

    function haversineGreatCircleDistance(
        $latitudeFrom,
        $longitudeFrom,
        $latitudeTo,
        $longitudeTo
    ) {
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

    function insertAbsen($request)
    {
        $dateTime = Carbon::now();
        $working_hours = $this->absensiRepository->getJamKerja();
        $data_absen = $this->absensiRepository->dataAbsenMasuk($dateTime);

        $clock_in = $working_hours->clock_in;
        $clock_out = $working_hours->home_time;

        $data['status'] = "";
        if (empty($data_absen)) {
            if ($dateTime->toTimeString() <= $working_hours->home_time) {
                $files = $request->file('foto');
                $file_name = 'absen-masuk-' . date('YmdHis-') . str_replace(' ', '', $files->getClientOriginalName());
                Storage::disk('local')->putFileAs('public/absensi', $files, $file_name);
                $data['foto'] = $file_name;
                $data['clock_in'] = $dateTime->toTimeString();
                $data['clock_out'] = null;
                $data['koordinat'] = $request['koordinat'];
                if ($dateTime->toTimeString() > $clock_in) {
                    $data['status'] = 'Terlambat';
                } else {
                    $data['status'] = 'Hadir';
                }
                return  $this->absensiRepository->absenMasuk($data, $dateTime);
            } else {
                throw new HttpResponseException(response()->json([
                    'message'   => 'Masa Absen Telah Berakhir',
                ], 422));
            }
        } else {
            throw new HttpResponseException(response()->json([
                'message'   => 'Anda Telah Melakukan Absen Hari Ini',
            ], 422));
        }
    }

    public function absenMasuk($request)
    {
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
            ], 422));
        }
        $working_hours = $this->absensiRepository->getJamKerja();
        $koordinatUser = explode(',', $request->koordinat);
        $koordinatLokasi = explode(',', $working_hours->location);
        $radius = 100;
        if ($this->haversineGreatCircleDistance($koordinatUser[0], $koordinatUser[1], $koordinatLokasi[0], $koordinatLokasi[1]) <= $radius) {
            return $this->insertAbsen($request);
        } else {
            throw new HttpResponseException(response()->json([
                'message'   => 'Maaf Anda berada Diluar Radius Lokasi Kerja'
            ], 500));
        }
    }

    public function absenPulang($request)
    {
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
            ], 422));
        }
        $dateTime = Carbon::now();
        $data_absen = $this->absensiRepository->dataAbsenPulang($dateTime);
        $working_hours = $this->absensiRepository->getJamKerja();
        $clock_out = $working_hours->home_time;

        if ($dateTime->toTimeString() <= $clock_out) {
            throw new HttpResponseException(response()->json([
                'message'   => 'Belum Bisa Melakukan Absen Pulang',
            ], 500));
        }

        if (!empty($data_absen) && ($data_absen->status == 'Hadir' || $data_absen->status == 'Terlambat')) {
            $koordinatUser = explode(',', $request->koordinat);
            $koordinatKantor = explode(',', $working_hours->location);
            $radius = 100;
            if ($this->haversineGreatCircleDistance($koordinatUser[0], $koordinatUser[1], $koordinatKantor[0], $koordinatKantor[1]) <= $radius) {
                $files = $request->file('foto');
                Storage::delete('/public/absensi/' . $data_absen->foto_pulang);
                $file_name = 'absen-pulang-' . date('YmdHis-') . str_replace(' ', '', $files->getClientOriginalName());
                Storage::disk('local')->putFileAs('public/absensi', $files, $file_name);
                $data['foto'] = $file_name;
                $data['clock_out'] = $dateTime->toTimeString();
                $data['koordinat'] = $request->koordinat;
                return  $this->absensiRepository->absenPulang($data, $dateTime);
            } else {
                throw new HttpResponseException(response()->json([
                    'message'   => 'Maaf Anda berada Diluar Radius Lokasi Kerja'
                ], 500));
            }
        } else {
            throw new HttpResponseException(response()->json([
                'message'   => 'Anda Belum Melakukan Absen Masuk Hari Ini',
            ], 500));
        }
    }


    public function getAllAbsensi()
    {
        return $this->absensiRepository->getAllAbsensi();
    }

    public function getAbsenById($id)
    {
        return $this->absensiRepository->getAbsenById($id);
    }
}
