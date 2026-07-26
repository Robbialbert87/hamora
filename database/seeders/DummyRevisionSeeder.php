<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\User;

class DummyRevisionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $this->command?->error('Tidak ada user. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $bidangId = \DB::table('bidang')->first()?->id;
        $kategoriId = \DB::table('kategori')->first()?->id;

        $parentId = null;
        $totalRevisi = 10;

        for ($v = 1; $v <= $totalRevisi; $v++) {
            $isLast = $v === $totalRevisi;
            $isSecondLast = $v === $totalRevisi - 1;

            $status = match(true) {
                $isLast       => 'aktif',
                $isSecondLast => 'diubah',
                default       => 'direvisi',
            };

            $doc = Document::create([
                'nomor_dokumen'      => '099/DUM/HAMORA/' . ($v < 10 ? '0' . $v : $v),
                'nama_dokumen'       => 'Dokumen Dummy Revisi ke-' . $v,
                'tahun'              => date('Y'),
                'bidang_id'          => $bidangId,
                'kategori_id'        => $kategoriId,
                'tanggal_terbit'     => now()->subDays($totalRevisi - $v),
                'tanggal_berlaku'    => now()->addYear(),
                'versi'              => $v,
                'status'             => $status,
                'deskripsi'          => 'Ini adalah dokumen dummy revisi ke-' . $v . ' untuk testing timeline riwayat.',
                'file_pdf'           => 'dummy/dummy-revisi-' . $v . '.pdf',
                'parent_document_id' => $parentId,
                'uploaded_by'        => $user->id,
            ]);

            $parentId = $doc->id;
        }

        $this->command?->info("Berhasil membuat {$totalRevisi} dokumen dummy revision chain.");
    }
}
