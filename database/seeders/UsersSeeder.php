<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'jabatan' => 'admin',
            'foto' => 'default.jpg',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => bcrypt('12345678')
        ]);
        User::create([
            'name' => 'Andito',
            'jabatan' => 'Programmer',
            'foto' => 'default.jpg',
            'email' => 'andito@email.com',
            'role' => 'karyawan',
            'password' => bcrypt('12345678')
        ]);
    }
}
