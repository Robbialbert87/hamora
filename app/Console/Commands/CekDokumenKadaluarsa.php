<?php

namespace App\Console\Commands;

use App\Mail\DokumenKadaluarsa;
use App\Models\Document;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CekDokumenKadaluarsa extends Command
{
    protected $signature = 'app:cek-kadaluarsa';
    protected $description = 'Cek dan kirim notifikasi dokumen yang sudah kadaluarsa';

    public function handle(): int
    {
        $today = now()->startOfDay();

        $baru = Document::where('tanggal_berlaku', '<', $today)
            ->where('status', '!=', 'kadaluarsa')
            ->get();

        foreach ($baru as $doc) {
            $doc->update(['status' => 'kadaluarsa']);
        }

        $semua = Document::where('tanggal_berlaku', '<', $today)->get();

        if ($semua->isEmpty()) {
            $this->info('Tidak ada dokumen kadaluarsa.');
            return self::SUCCESS;
        }

        $email = config('app.kadaluarsa_email') ?: env('KADALUARSA_EMAIL');

        if (!$email) {
            $this->warn('Email penerima notifikasi belum dikonfigurasi. Set KADALUARSA_EMAIL di .env');
            return self::FAILURE;
        }

        Mail::to($email)->send(new DokumenKadaluarsa($semua));

        $info = $baru->count() > 0 ? "{$baru->count()} baru ditandai kadaluarsa. " : "";
        $this->info($info . "Notifikasi {$semua->count()} dokumen terkirim ke {$email}.");

        return self::SUCCESS;
    }
}
