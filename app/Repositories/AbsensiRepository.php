<?php

namespace App\Repositories;

use Auth;
use App\Models\User;
use App\Models\Absen;


class AbsensiRepository
{

    public function absenMasuk($data, $dateTime)
    {
        $absen_masuk = new Absen();
        $absen_masuk->user_id = Auth::user()->id;
        $absen_masuk->foto_masuk = $data['foto'];
        $absen_masuk->tgl = $dateTime->toDateString();
        $absen_masuk->jam_masuk = $data['jam_masuk'];
        $absen_masuk->jam_pulang = $data['jam_pulang'];
        $absen_masuk->status = $data['status'];
        $absen_masuk->koordinat_masuk = $data['koordinat'];
        $absen_masuk->save();
        return $absen_masuk->fresh();
    }

    public function dataAbsenMasuk($dateTime)
    {
        $data_absen = Absen::where('user_id', Auth::user()->id)
            ->where('tgl', $dateTime->toDateString())
            ->first();
        return $data_absen;
    }

    public function dataAbsenPulang($dateTime)
    {
        $data_absen = Absen::where('user_id', Auth::user()->id)
            ->where('tgl', $dateTime->toDateString())
            ->first();
        return $data_absen;
    }


    public function absenPulang($data, $dateTime)
    {
        $absen_pulang =  Absen::where('tgl', $dateTime->toDateString())
            ->where('user_id', Auth::user()->id)
            ->first();
        $absen_pulang->jam_pulang = $data['jam_pulang'];
        $absen_pulang->foto_pulang = $data['foto'];
        $absen_pulang->koordinat_pulang = $data['koordinat'];
        $absen_pulang->save();
        return $absen_pulang->fresh();
    }


    public function getUserNotAbsen($id, $dateNow)
    {
        $getUserNotAbsen = Absen::select('tgl', 'user_id')
            ->whereDate('tgl', $dateNow)
            ->where('user_id', $id)
            ->first();
        return   $getUserNotAbsen;
    }

    public function insertAbsenAlfa($id, $date, $data)
    {
        $absen = new Absen();
        $absen->user_id = $id;
        $absen->status = $data['status'];
        $absen->jam_masuk = null;
        $absen->jam_pulang = null;
        $absen->koordinat = null;
        $absen->tgl = $date;
        $absen->save();
        return $absen->fresh();
    }

    public function getLokasiKerja()
    {
        $lokasiKerja = LokasiKerja::first();
        return $lokasiKerja;
    }

    public function getUserAbsen($userId, $date)
    {
        $absenPulang = Absen::where('user_id', $userId)
            ->whereDate('tgl', $date)
            ->first();
        return $absenPulang;
    }

    public function getJamKerja()
    {
        $operator_tim_kerja = OperatorTimKerja::where('operator_id', Auth::user()->Operator->id)->first();
        $ketua_tim_kerja = TimKerja::where('ketua_tim', Auth::user()->Operator->id)->first();
        if ($operator_tim_kerja != null) {
            $jam_kerja = $operator_tim_kerja->TimKerja->jam_kerja;
        } else {
            $jam_kerja = $ketua_tim_kerja->jam_kerja;
        }
        return $jam_kerja;
    }

    public function getAllAbsensi()
    {
        $user = User::where('role', 'boi')->count();
        $absensi = Absen::paginate($user);
        return $absensi;
    }

    public function getAbsenById($id)
    {
        $absensi = Absen::where('id', $id)->paginate(10);
        return $absensi;
    }
}
