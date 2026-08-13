<?php
namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class DocumentService
{
    public function uploadPdf(UploadedFile $file, int|string $tahun): string
    {
        $tahun = (string) ($tahun ?: date('Y'));
        $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $file->getClientOriginalExtension();
        $path = "documents/{$tahun}/{$filename}";

        if (!$file->isValid()) {
            $errors = [
                UPLOAD_ERR_INI_SIZE   => 'File melebihi ukuran maksimum upload server.',
                UPLOAD_ERR_FORM_SIZE  => 'File melebihi ukuran maksimum form.',
                UPLOAD_ERR_PARTIAL    => 'File hanya terupload sebagian.',
                UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang diupload.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server kekurangan folder temporary.',
                UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file ke disk.',
                UPLOAD_ERR_EXTENSION  => 'Ekspensi PHP menghentikan upload file.',
            ];
            $code = $file->getError();
            $msg = $errors[$code] ?? 'Upload file gagal (kode error: ' . $code . ').';
            throw new \RuntimeException($msg);
        }

        $pathname = $file->getPathname();
        if (empty($pathname) || !is_file($pathname)) {
            throw new \RuntimeException('File upload tidak valid atau sudah tidak tersedia. Silakan pilih ulang file.');
        }

        $stream = fopen($pathname, 'r');
        if (!$stream) {
            throw new \RuntimeException('Gagal membaca file upload. Silakan coba lagi.');
        }

        $contents = stream_get_contents($stream);
        fclose($stream);

        if (strlen($contents) === 0) {
            throw new \RuntimeException('File upload kosong. Silakan pilih ulang file.');
        }

        $success = Storage::disk('public')->put($path, $contents);
        if (!$success) {
            throw new \RuntimeException('Gagal menyimpan file ke server. Silakan coba lagi.');
        }

        return $path;
    }

    public function createDocument(array $data, ?UploadedFile $file = null): Document
    {
        if ($file) {
            $data['file_pdf'] = $this->uploadPdf($file, $data['tahun']);
        }

        $data['uploaded_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';
        $data['versi'] = 1;

        $document = Document::create($data);

        $this->createVersion($document, $data['file_pdf'] ?? null, 'Dokumen awal');

        ActivityLog::log('upload', "Upload dokumen: {$document->nama_dokumen}", [
            'document_id' => $document->id
        ]);

        return $document;
    }

    public function createRevision(Document $parentDocument, array $data, ?UploadedFile $file = null, string $parentStatus = 'direvisi'): Document
    {
        if ($file) {
            $data['file_pdf'] = $this->uploadPdf($file, $data['tahun']);
        }

        $data['parent_document_id'] = $parentDocument->id;
        $data['uploaded_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'draft';
        $data['versi'] = $this->nextVersionNumber($parentDocument);

        if (!isset($data['nomor_dokumen'])) {
            $data['nomor_dokumen'] = $parentDocument->nomor_dokumen . '-R' . $data['versi'];
        }

        $document = Document::create($data);

        $this->createVersion($document, $data['file_pdf'] ?? null, "Revisi v{$document->versi}");

        $parentDocument->update(['status' => $parentStatus]);

        ActivityLog::log('upload', "Revisi dokumen: {$document->nama_dokumen} (v{$document->versi})", [
            'document_id' => $document->id,
            'parent_id' => $parentDocument->id
        ]);

        return $document;
    }

    protected function nextVersionNumber(Document $parentDocument): int
    {
        $root = $parentDocument;
        while ($root->parent) {
            $root = $root->parent;
        }

        $versions = collect();
        $queue = collect([$root]);
        while ($queue->isNotEmpty()) {
            $current = $queue->shift();
            $versions->push($current->versi);
            foreach ($current->revisions()->get() as $child) {
                $queue->push($child);
            }
        }

        return (int) $versions->max() + 1;
    }

    public function createVersion(Document $document, ?string $filePdf, string $keterangan = ''): DocumentVersion
    {
        return DocumentVersion::create([
            'document_id' => $document->id,
            'versi' => $document->versi,
            'file_pdf' => $filePdf ?? $document->file_pdf,
            'keterangan' => $keterangan,
            'created_by' => auth()->id(),
        ]);
    }

    public function archiveDocument(Document $document, string $keterangan = '', ?string $tanggalPencabutan = null): Document
    {
        $document->update([
            'status' => 'dicabut',
            'tanggal_pencabutan' => $tanggalPencabutan ?? now()->toDateString(),
        ]);

        ActivityLog::log('arsip', "Arsipkan dokumen: {$document->nama_dokumen}", [
            'document_id' => $document->id,
            'keterangan' => $keterangan,
            'tanggal_pencabutan' => $tanggalPencabutan,
        ]);

        return $document;
    }

    public function getLatestDocument(string $nomorDokumen): ?Document
    {
        $documents = Document::where('nomor_dokumen', 'LIKE', "{$nomorDokumen}%")->get();

        if ($documents->isEmpty()) return null;

        return $documents->sortByDesc('versi')->first();
    }
}
