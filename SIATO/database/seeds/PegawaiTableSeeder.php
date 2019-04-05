<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PegawaiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pegawai')->insert([[
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'nama' => 'Administrator',
            'nomor_telepon' => '081234567890',
            'alamat' => 'Yogyakarta',
            'gaji' => 1000000,
            'role' => 0,
            'api_key' => Str::random(12),
        ]]);
    }
}
