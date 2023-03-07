<?php

namespace Database\Seeders;

use App\Models\WorkingHours;
use App\Models\WorkLocation;
use Illuminate\Database\Seeder;

class WorkingHoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkingHours::create([
            'jam_masuk' => '08:15:00',
            'jam_pulang' => '16:30:00',
        ]);
        WorkLocation::create([
            'ket' => 'Kantor Liny Jaya',
            'titik_koordinat' => '-5.1552719291637015_119.44172994190482'
        ]);
    }
}
