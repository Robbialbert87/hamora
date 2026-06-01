<?php
namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'SK Direktur', 'SOP', 'Pedoman', 'Kebijakan', 'Surat Edaran',
            'Program Kerja', 'Dokumen Akreditasi', 'Formulir', 'Lainnya',
        ];

        foreach ($data as $nama) {
            Kategori::create(['nama' => $nama]);
        }
    }
}
