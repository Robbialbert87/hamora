<?php
namespace Database\Seeders;

use App\Models\Bidang;
use Illuminate\Database\Seeder;

class BidangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Direksi', 'Pelayanan Medik', 'Keperawatan', 'SDM', 'Keuangan',
            'PPI', 'PMKP', 'IPSRS', 'Farmasi', 'Rekam Medis',
            'Laboratorium', 'Radiologi', 'Gizi', 'Hukum dan Humas', 'Lainnya',
        ];

        foreach ($data as $nama) {
            Bidang::create(['nama' => $nama]);
        }
    }
}
