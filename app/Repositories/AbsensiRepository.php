<?php

namespace App\Repositories;

use Auth;
use App\Models\User;
use App\Models\Absen;
use App\Models\WorkingHours;


class AbsensiRepository
{

    public function absenMasuk($data, $dateTime)
    {
        $absent_entry = new Absen();
        $absent_entry->user_id = Auth::user()->id;
        $absent_entry->incoming_photo = $data['foto'];
        $absent_entry->date = $dateTime->toDateString();
        $absent_entry->clock_in = $data['clock_in'];
        $absent_entry->clock_out = $data['clock_out'];
        $absent_entry->status = $data['status'];
        $absent_entry->login_coordinates = $data['koordinat'];
        $absent_entry->save();
        return $absent_entry->fresh();
    }

    public function dataAbsenMasuk($dateTime)
    {
        $data_absen = Absen::where('user_id', Auth::user()->id)
            ->where('date', $dateTime->toDateString())
            ->first();
        return $data_absen;
    }

    public function dataAbsenPulang($dateTime)
    {
        $data_absen = Absen::where('user_id', Auth::user()->id)
            ->where('date', $dateTime->toDateString())
            ->first();
        return $data_absen;
    }


    public function absenPulang($data, $dateTime)
    {
        $absent_home =  Absen::where('date', $dateTime->toDateString())
            ->where('user_id', Auth::user()->id)
            ->first();
        $absent_home->clock_out = $data['clock_out'];
        $absent_home->home_photo = $data['foto'];
        $absent_home->return_coordinates = $data['koordinat'];
        $absent_home->save();
        return $absent_home->fresh();
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


    public function getUserAbsen($userId, $date)
    {
        $absenPulang = Absen::where('user_id', $userId)
            ->whereDate('date', $date)
            ->first();
        return $absenPulang;
    }

    public function getJamKerja()
    {
        $working_hours = WorkingHours::first();
        return $working_hours;
    }
}
