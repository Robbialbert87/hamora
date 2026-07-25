<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:cek-kadaluarsa')]
#[Description('Cek dan update otomatis status kadaluarsa untuk dokumen dan MOU')]
class CekKadaluarsa extends Command
{
    public function handle()
    {
        // Cek MOU
        $expiredMou = \App\Models\Mou::where('status', 'aktif')
            ->where('akhir_perjanjian', '<', now()->format('Y-m-d'))
            ->get();

        foreach ($expiredMou as $mou) {
            $mou->update(['status' => 'kadaluarsa']);
            $this->info("MOU {$mou->nomor} - {$mou->judul} => kadaluarsa");
        }

        // Cek Dokumen
        $expiredDocs = \App\Models\Document::where('status', 'aktif')
            ->whereNotNull('tanggal_berlaku')
            ->where('tanggal_berlaku', '<', now()->format('Y-m-d'))
            ->get();

        foreach ($expiredDocs as $doc) {
            $doc->update(['status' => 'kadaluarsa']);
            $this->info("Dokumen {$doc->nomor_dokumen} - {$doc->nama_dokumen} => kadaluarsa");
        }

        $total = $expiredMou->count() + $expiredDocs->count();
        $this->info("Total {$total} item diupdate ke status kadaluarsa.");
    }
}
