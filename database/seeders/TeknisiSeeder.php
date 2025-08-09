<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Auth\Teknisi;

class TeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Teknisi::firstOrCreate(
            [
                'id_teknisi' => 'TEK1' 
            ],
            [
                'nama_teknisi' => 'Arinda',
                'nohp_teknisi' => '081234567890',
                'status' => 'Pemilik',
                'password' => Hash::make('password'),
            ]
        );

        /*
        Teknisi::firstOrCreate(
            ['id_teknisi' => 'TEK2'],
            [
                'nama_teknisi' => 'Pegawai Contoh',
                'nohp_teknisi' => '089876543210',
                'status' => 'Pegawai',
                'password' => Hash::make('pegawai123'),
            ]
        );
        */
    }
}