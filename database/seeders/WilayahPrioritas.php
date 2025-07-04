<?php

namespace Database\Seeders;

use App\Models\WilayahPrioritas as ModelsWilayahPrioritas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WilayahPrioritas extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach(json_decode(file_get_contents(database_path('data/wilayah_prioritas_jabar.json')), true) as $item) {
            ModelsWilayahPrioritas::create([
                'nama_wilayah' => $item['nama'],
                'tingkat_kemiskinan' => $item['tingkat_kemiskinan'],
                'latitude'=> $item['latitude'],
                'longitude'=> $item['longitude']
            ]);
        }
    }
}
