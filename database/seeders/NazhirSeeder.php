<?php

namespace Database\Seeders;

use App\Models\Nazhir;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NazhirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Badan Wakaf Indonesia Jawa Barat',
                'alamat' => 'Jl. Soekarno Hatta No. 123, Bandung',
                'no_hp' => '081234567890',
            ],
            [
                'nama' => 'Yayasan Masjid Agung Tasikmalaya',
                'alamat' => 'Jl. HZ Mustofa No. 10, Tasikmalaya',
                'no_hp' => '082345678901',
            ],
            [
                'nama' => 'DKM Al-Ihsan Depok',
                'alamat' => 'Jl. Raya Margonda, Depok',
                'no_hp' => '083456789012',
            ]
        ];

        foreach ($data as $item) {
            Nazhir::create($item);
        }
    }
}
