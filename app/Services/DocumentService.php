<?php
namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class DocumentService
{
    public function uploadPdf(UploadedFile $file, int $tahun): string
    {
        $path = "documents/{$tahun}";
        return $file->store($path, 'public');
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

    public function createRevision(Document $parentDocument, array $data, ?UploadedFile $file = null): Document
    {
        if ($file) {
            $data['file_pdf'] = $this->uploadPdf($file, $data['tahun']);
        }

        $data['parent_document_id'] = $parentDocument->id;
        $data['uploaded_by'] = auth()->id();
        $data['status'] = 'draft';
        $data['versi'] = $parentDocument->versi + 1;

        if (!isset($data['nomor_dokumen'])) {
            $data['nomor_dokumen'] = $parentDocument->nomor_dokumen . '-R' . $data['versi'];
        }

        $document = Document::create($data);

        $parentDocument->update(['status' => 'direvisi']);

        ActivityLog::log('upload', "Revisi dokumen: {$document->nama_dokumen} (v{$document->versi})", [
            'document_id' => $document->id,
            'parent_id' => $parentDocument->id
        ]);

        return $document;
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

    public function verifyDocument(Document $document): Document
    {
        $document->update([
            'status' => 'aktif',
            'verified_by' => auth()->id(),
        ]);

        ActivityLog::log('verifikasi', "Verifikasi dokumen: {$document->nama_dokumen}", [
            'document_id' => $document->id
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
