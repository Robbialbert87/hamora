<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Bidang;
use App\Models\Kategori;
use App\Models\ActivityLog;
use App\Services\DocumentService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected $documentService;
    protected $activityLog;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
        $this->activityLog = new ActivityLogService();
    }

    public function index()
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        return view('documents.index', compact('bidang', 'kategori'));
    }

    public function byStatus($status)
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        return view('documents.index', compact('bidang', 'kategori'))->with('defaultStatus', $status);
    }

    public function data(Request $request)
    {
        $documents = Document::with(['bidang', 'kategori', 'uploader'])
            ->select('documents.*');

        if ($request->filled('nomor_dokumen')) {
            $documents->where('nomor_dokumen', 'like', '%' . $request->nomor_dokumen . '%');
        }

        if ($request->filled('nama_dokumen')) {
            $documents->where('nama_dokumen', 'like', '%' . $request->nama_dokumen . '%');
        }

        if ($request->filled('tahun')) {
            $documents->where('tahun', $request->tahun);
        }

        if ($request->filled('bidang_id')) {
            $documents->where('bidang_id', $request->bidang_id);
        }

        if ($request->filled('kategori_id')) {
            $documents->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('status')) {
            $documents->where('status', $request->status);
        }

        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $documents->where(function ($q) use ($search) {
                $q->where('nomor_dokumen', 'like', "%{$search}%")
                  ->orWhere('nama_dokumen', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        return DataTables::of($documents)
            ->addIndexColumn()
            ->addColumn('bidang', function ($doc) {
                return $doc->bidang?->nama ?? '-';
            })
            ->addColumn('kategori', function ($doc) {
                return $doc->kategori?->nama ?? '-';
            })
            ->addColumn('tanggal_terbit_formatted', function ($doc) {
                return $doc->tanggal_terbit ? $doc->tanggal_terbit->format('d/m/Y') : '-';
            })
            ->addColumn('status_badge', function ($doc) {
                $labels = ['draft' => 'Draft', 'aktif' => 'Aktif', 'direvisi' => 'Direvisi', 'kadaluarsa' => 'Kadaluarsa', 'dicabut' => 'Dicabut', 'diubah' => 'Diubah'];
                $colors = ['draft' => 'warning', 'aktif' => 'success', 'direvisi' => 'info', 'kadaluarsa' => 'danger', 'dicabut' => 'secondary', 'diubah' => 'primary'];
                $label = $labels[$doc->status] ?? e($doc->status);
                $color = $colors[$doc->status] ?? 'secondary';
                return "<span class=\"badge bg-{$color}\">{$label}</span>";
            })
            ->addColumn('action', function ($doc) {
                $btn = '<a href="' . e(route('documents.show', $doc->id)) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                if (auth()->user()->can('edit dokumen')) {
                    $btn .= ' <a href="' . e(route('documents.edit', $doc->id)) . '" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>';
                }
                if (auth()->user()->can('hapus dokumen')) {
                    $btn .= ' <button class="btn btn-sm btn-danger btn-delete" data-url="' . e(route('documents.destroy', $doc->id)) . '" data-name="' . e($doc->nama_dokumen) . '"><i class="fas fa-trash"></i></button>';
                }
                return $btn;
            })
            ->addColumn('latest_revision', function ($doc) {
                if ($doc->status === 'direvisi') {
                    $latest = $doc->latestRevision();
                    if ($latest) {
                        return '<span class="badge bg-warning text-dark">Ada revisi baru</span>';
                    }
                }
                return '-';
            })
            ->rawColumns(['status_badge', 'action', 'latest_revision'])
            ->make(true);
    }

    public function create()
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        return view('documents.create', compact('bidang', 'kategori'));
    }

    public function createBaru()
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        return view('documents.create-baru', compact('bidang', 'kategori'));
    }

    public function createMou()
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        return view('documents.create-mou', compact('bidang', 'kategori'));
    }

    public function createUpdate()
    {
        return view('documents.create-update');
    }

    public function createUpdateDiubah()
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        $documents = Document::whereIn('status', ['aktif', 'kadaluarsa', 'direvisi', 'dicabut', 'diubah'])->get();
        return view('documents.create-update-diubah', compact('bidang', 'kategori', 'documents'));
    }

    public function createUpdateDicabut()
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        $documents = Document::whereIn('status', ['aktif', 'kadaluarsa', 'direvisi', 'dicabut', 'diubah'])->get();
        return view('documents.create-update-dicabut', compact('bidang', 'kategori', 'documents'));
    }

    public function createUpdateDicabutSebagian()
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        $documents = Document::whereIn('status', ['aktif', 'kadaluarsa', 'direvisi', 'dicabut', 'diubah'])->get();
        return view('documents.create-update-dicabut-sebagian', compact('bidang', 'kategori', 'documents'));
    }

    public function store(Request $request)
    {
        $jenisUpload = $request->input('jenis_upload', 'baru');

        if ($jenisUpload === 'dicabut') {
            $validated = $request->validate([
                'parent_document_id' => 'required|exists:documents,id',
                'tanggal_pencabutan' => 'required|date',
                'keterangan_pencabutan' => 'nullable|string|max:500',
            ]);

            $parentDocument = Document::findOrFail($validated['parent_document_id']);
            $this->documentService->archiveDocument($parentDocument, $validated['keterangan_pencabutan'] ?? '', $validated['tanggal_pencabutan']);

            return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diarsipkan.');
        }

        $rules = [
            'nama_dokumen' => 'required|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
            'bidang_id' => 'required|exists:bidang,id',
            'kategori_id' => 'required|exists:kategori,id',
            'tanggal_terbit' => 'required|date',
            'deskripsi' => 'nullable',
            'file_pdf' => 'required|mimes:pdf|max:20480',
            'status' => 'required|in:draft,aktif,kadaluarsa,dicabut',
        ];

        if (in_array($jenisUpload, ['baru', 'mou'])) {
            $rules['nomor_dokumen'] = 'required';
        } else {
            $rules['nomor_dokumen'] = 'nullable';
        }

        if (in_array($jenisUpload, ['mou', 'revisi', 'update'])) {
            $rules['tanggal_berlaku'] = $jenisUpload === 'mou'
                ? 'required|date|after_or_equal:tanggal_terbit'
                : 'nullable|date|after_or_equal:tanggal_terbit';
        }

        if (in_array($jenisUpload, ['revisi', 'update'])) {
            $rules['parent_document_id'] = 'required|exists:documents,id';
        }

        if (in_array($jenisUpload, ['update', 'revisi'])) {
            $rules['bidang_id'] = 'nullable|exists:bidang,id';
            $rules['kategori_id'] = 'nullable|exists:kategori,id';
            $rules['status'] = 'nullable|in:draft,aktif,kadaluarsa,dicabut,diubah';
        }

        $validated = $request->validate($rules);
        $validated['tanggal_berlaku'] = $validated['tanggal_berlaku'] ?? null;

        if (in_array($jenisUpload, ['revisi', 'update'])) {
            $parentDocument = Document::findOrFail($validated['parent_document_id']);

            if ($validated['nomor_dokumen'] && $validated['nomor_dokumen'] === $parentDocument->nomor_dokumen && $validated['tanggal_terbit'] === $parentDocument->tanggal_terbit?->format('Y-m-d')) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'tanggal_terbit' => 'Tanggal terbit harus berbeda dari dokumen asli jika nomor dokumen sama.',
                ]);
            }

            if (in_array($jenisUpload, ['update', 'revisi'])) {
                $validated['bidang_id'] = $validated['bidang_id'] ?? $parentDocument->bidang_id;
                $validated['kategori_id'] = $validated['kategori_id'] ?? $parentDocument->kategori_id;
                $validated['tanggal_berlaku'] = $validated['tanggal_berlaku'] ?? $parentDocument->tanggal_berlaku;
            }

            $parentStatus = $jenisUpload === 'update' ? 'diubah' : 'direvisi';
            if ($request->hasFile('file_pdf')) {
                $this->documentService->createRevision($parentDocument, $validated, $request->file('file_pdf'), $parentStatus);
            }
        } elseif ($request->hasFile('file_pdf')) {
            $this->documentService->createDocument($validated, $request->file('file_pdf'));
        }

        $messages = [
            'baru' => 'Dokumen baru berhasil diupload.',
            'mou' => 'Dokumen MOU berhasil diupload.',
            'revisi' => 'Revisi dokumen berhasil diupload.',
            'update' => 'Update dokumen berhasil disimpan.',
        ];

        return redirect()->route('documents.index')->with('success', $messages[$jenisUpload] ?? 'Dokumen berhasil diupload.');
    }

    public function show(Document $document)
    {
        $document->load(['bidang', 'kategori', 'uploader', 'verifier', 'versions.creator', 'parent']);
        $latestRevision = $document->latestRevision();
        $revisionHistory = $document->revisionHistory();
        $latestDocId = !empty($revisionHistory) ? last($revisionHistory)->id : $document->id;
        return view('documents.show', compact('document', 'latestRevision', 'revisionHistory', 'latestDocId'));
    }

    public function edit(Document $document)
    {
        $bidang = Bidang::all();
        $kategori = Kategori::all();
        return view('documents.edit', compact('document', 'bidang', 'kategori'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'nomor_dokumen' => 'required|unique:documents,nomor_dokumen,' . $document->id,
            'nama_dokumen' => 'required|max:255',
            'tahun' => 'required|integer|min:2000|max:2099',
            'bidang_id' => 'required|exists:bidang,id',
            'kategori_id' => 'required|exists:kategori,id',
            'tanggal_terbit' => 'required|date',
            'tanggal_berlaku' => 'nullable|date|after_or_equal:tanggal_terbit',
            'deskripsi' => 'nullable',
            'file_pdf' => 'nullable|mimes:pdf|max:20480',
            'status' => 'required|in:draft,aktif,direvisi,kadaluarsa,dicabut,diubah',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($document->file_pdf && Storage::disk('public')->exists($document->file_pdf)) {
                Storage::disk('public')->delete($document->file_pdf);
            }
            $validated['file_pdf'] = $this->documentService->uploadPdf($request->file('file_pdf'), $validated['tahun']);
        }

        $document->update($validated);

        ActivityLog::log('edit', "Edit dokumen: {$document->nama_dokumen}", ['document_id' => $document->id]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document)
    {
        $document->delete();
        ActivityLog::log('hapus', "Hapus dokumen: {$document->nama_dokumen}", ['document_id' => $document->id]);
        return response()->json(['success' => true]);
    }

    public function restore($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        $document->restore();
        ActivityLog::log('restore', "Restore dokumen: {$document->nama_dokumen}", ['document_id' => $document->id]);
        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil direstore.');
    }

    public function forceDelete($id)
    {
        $document = Document::withTrashed()->findOrFail($id);
        if ($document->file_pdf && Storage::disk('public')->exists($document->file_pdf)) {
            Storage::disk('public')->delete($document->file_pdf);
        }
        $document->forceDelete();
        ActivityLog::log('hapus_permanen', "Hapus permanen dokumen: {$document->nama_dokumen}", ['document_id' => $document->id]);
        return redirect()->route('documents.trashed')->with('success', 'Dokumen berhasil dihapus permanen.');
    }

    public function trashed()
    {
        $documents = Document::onlyTrashed()->with(['bidang', 'kategori', 'uploader'])->get();
        return view('documents.trashed', compact('documents'));
    }

    public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_pdf)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        return Storage::disk('public')->download($document->file_pdf, $document->nama_dokumen . '.pdf');
    }

    public function preview(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_pdf)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }
        return response()->file(Storage::disk('public')->path($document->file_pdf));
    }
}
