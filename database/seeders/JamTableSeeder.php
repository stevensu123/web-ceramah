<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('waktus')->insert([
            [
                'title' => 'Pagi',
                'jam_mulai' => '00:00',
                'jam_selesai' => '12:00',
            ],
            [
                'title' => 'Siang',
                'jam_mulai' => '12:00',
                'jam_selesai' => '18:00',
            ],
            [
                'title' => 'Sore',
                'jam_mulai' => '18:00',
                'jam_selesai' => '24:00',
            ],
            
        ]);
    }
}
