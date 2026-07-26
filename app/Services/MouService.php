<?php
namespace App\Services;

use App\Models\Mou;
use App\Models\MouVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MouService
{
    public function createMou(array $data, UploadedFile $file): Mou
    {
        $filePath = $this->uploadFile($file, 'mou');

        $mou = Mou::create([
            'pihak' => $data['pihak'],
            'judul' => $data['judul'],
            'bidang_id' => $data['bidang_id'] ?? null,
            'kategori_id' => $data['kategori_id'] ?? null,
            'nomor' => $data['nomor'],
            'mulai_perjanjian' => $data['mulai_perjanjian'],
            'masa_berlaku' => $data['masa_berlaku'] ?? null,
            'akhir_perjanjian' => $data['akhir_perjanjian'],
            'status' => $data['status'] ?? 'aktif',
            'versi' => 1,
            'parent_mou_id' => null,
            'file_pdf' => $filePath,
            'uploaded_by' => auth()->id(),
        ]);

        MouVersion::create([
            'mou_id' => $mou->id,
            'versi' => 1,
            'nomor' => $mou->nomor,
            'pihak' => $mou->pihak,
            'judul' => $mou->judul,
            'mulai_perjanjian' => $mou->mulai_perjanjian,
            'akhir_perjanjian' => $mou->akhir_perjanjian,
            'masa_berlaku' => $mou->masa_berlaku,
            'file_pdf' => $mou->file_pdf,
            'keterangan' => null,
            'created_by' => auth()->id(),
        ]);

        return $mou;
    }

    public function renewMou(Mou $oldMou, array $data, ?UploadedFile $file = null): Mou
    {
        $filePath = $oldMou->file_pdf;
        if ($file) {
            $filePath = $this->uploadFile($file, 'mou');
        }

        $newVersi = $oldMou->versi + 1;

        $newMou = Mou::create([
            'pihak' => $data['pihak'],
            'judul' => $data['judul'],
            'bidang_id' => $data['bidang_id'] ?? $oldMou->bidang_id,
            'kategori_id' => $data['kategori_id'] ?? $oldMou->kategori_id,
            'nomor' => $data['nomor'],
            'mulai_perjanjian' => $data['mulai_perjanjian'],
            'masa_berlaku' => $data['masa_berlaku'] ?? null,
            'akhir_perjanjian' => $data['akhir_perjanjian'],
            'status' => $data['status'] ?? 'aktif',
            'versi' => $newVersi,
            'parent_mou_id' => $oldMou->id,
            'file_pdf' => $filePath,
            'uploaded_by' => auth()->id(),
        ]);

        $this->snapshotVersion($oldMou, 'diperbarui');

        return $newMou;
    }

    public function snapshotVersion(Mou $mou, ?string $keterangan = null): MouVersion
    {
        return MouVersion::create([
            'mou_id' => $mou->id,
            'versi' => $mou->versi,
            'nomor' => $mou->nomor,
            'pihak' => $mou->pihak,
            'judul' => $mou->judul,
            'mulai_perjanjian' => $mou->mulai_perjanjian,
            'akhir_perjanjian' => $mou->akhir_perjanjian,
            'masa_berlaku' => $mou->masa_berlaku,
            'file_pdf' => $mou->file_pdf,
            'keterangan' => $keterangan,
            'created_by' => auth()->id(),
        ]);
    }

    protected function uploadFile(UploadedFile $file, string $directory): string
    {
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $directory . '/' . $fileName;
        Storage::disk('public')->put($filePath, file_get_contents($file->getPathname()));

        return $filePath;
    }
}
