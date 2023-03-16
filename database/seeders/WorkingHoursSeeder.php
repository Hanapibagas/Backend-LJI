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
            'clock_in' => '08:15:00',
            'home_time' => '16:30:00',
            'location' => '-5.1552719291637015,119.44172994190482',
            'ket' => 'Pt.Liny jaya Informatika'
        ]);
    }
}
