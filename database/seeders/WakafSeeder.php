<?php

namespace Database\Seeders;

use App\Models\Wakaf;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WakafSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(json_decode(file_get_contents(database_path('data/seeder_wakafs_simas.json')), true) as $item) {
            Wakaf::create([
                'nama_aset' => $item['nama_aset'],
                'jenis_aset' => $item['jenis_aset'],
                'nilai_estimasi' => $item['nilai_aset'],
                'lokasi' => $item['lokasi'],
                'latitude' => $item['latitude'],
                'longitude' => $item['longitude'],
                'status' => $item['status'],
                'nazhir_id' => $item['nazhir_id']
            ]);
        }
    }
}
