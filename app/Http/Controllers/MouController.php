<?php
namespace App\Http\Controllers;

use App\Models\Mou;
use App\Models\Bidang;
use App\Models\Kategori;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;

class MouController extends Controller
{
    public function index()
    {
        return view('mou.index');
    }

    public function data(Request $request)
    {
        $mou = Mou::with(['uploader', 'bidang', 'kategori'])->select('mou.*');

        if ($request->filled('global_search')) {
            $search = $request->global_search;
            $mou->where(function ($q) use ($search) {
                $q->where('pihak', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('nomor', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $mou->where('status', $request->status);
        }

        return DataTables::of($mou)
            ->addIndexColumn()
            ->addColumn('mulai_formatted', function ($m) {
                return $m->mulai_perjanjian ? $m->mulai_perjanjian->format('d/m/Y') : '-';
            })
            ->addColumn('masa_berlaku_formatted', function ($m) {
                return $m->masa_berlaku_formatted;
            })
            ->addColumn('akhir_formatted', function ($m) {
                return $m->akhir_perjanjian ? $m->akhir_perjanjian->format('d/m/Y') : '-';
            })
            ->addColumn('keterangan', function ($m) {
                return $m->keterangan;
            })
            ->addColumn('bidang_nama', function ($m) {
                return $m->bidang ? $m->bidang->nama : '-';
            })
            ->addColumn('kategori_nama', function ($m) {
                return $m->kategori ? $m->kategori->nama : '-';
            })
            ->addColumn('status_badge', function ($m) {
                $labels = ['aktif' => 'Aktif', 'kadaluarsa' => 'Kadaluarsa', 'dicabut' => 'Dicabut'];
                $colors = ['aktif' => 'success', 'kadaluarsa' => 'danger', 'dicabut' => 'secondary'];
                $label = $labels[$m->status] ?? e($m->status);
                $color = $colors[$m->status] ?? 'secondary';
                return "<span class=\"badge bg-{$color}\">{$label}</span>";
            })
            ->addColumn('action', function ($m) {
                $btn = '<div class="d-flex align-items-center gap-1">';
                $btn .= '<a href="' . e(route('mou.show', $m->id)) . '" class="btn btn-light-info btn-icon" title="Lihat"><i class="ti ti-eye"></i></a>';
                if (auth()->user()->can('edit dokumen')) {
                    $btn .= '<a href="' . e(route('mou.edit', $m->id)) . '" class="btn btn-light-warning btn-icon" title="Edit"><i class="ti ti-pencil"></i></a>';
                }
                if (auth()->user()->can('hapus dokumen')) {
                    $btn .= '<button class="btn btn-light-danger btn-icon btn-delete" title="Hapus" data-url="' . e(route('mou.destroy', $m->id)) . '" data-name="' . e($m->judul) . '"><i class="ti ti-trash"></i></button>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create()
    {
        $bidang = Bidang::orderBy('nama')->get();
        $kategori = Kategori::orderBy('nama')->get();
        return view('mou.create', compact('bidang', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pihak' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'bidang_id' => 'nullable|exists:bidang,id',
            'kategori_id' => 'nullable|exists:kategori,id',
            'nomor' => 'required|string|max:255|unique:mou,nomor',
            'mulai_perjanjian' => 'required|date',
            'masa_berlaku' => 'nullable|integer|min:1|max:3650',
            'akhir_perjanjian' => 'nullable|date|after_or_equal:mulai_perjanjian',
            'status' => 'required|in:aktif,kadaluarsa,dicabut',
            'file_pdf' => 'required|file|mimes:pdf|max:20480',
        ]);

        $file = $request->file('file_pdf');
        $fileName = time() . '_' . $file->getClientOriginalName();

        if (!Storage::disk('public')->exists('mou')) {
            Storage::disk('public')->makeDirectory('mou');
        }

        $filePath = 'mou/' . $fileName;
        Storage::disk('public')->put($filePath, file_get_contents($file->getPathname()));

        $mulai = \Carbon\Carbon::parse($request->mulai_perjanjian);

        if ($request->filled('masa_berlaku')) {
            $akhir = $mulai->copy()->addDays((int) $request->masa_berlaku);
        } elseif ($request->filled('akhir_perjanjian')) {
            $akhir = \Carbon\Carbon::parse($request->akhir_perjanjian);
            $request->merge(['masa_berlaku' => (int) $mulai->diffInDays($akhir)]);
        } else {
            return back()->withErrors(['akhir_perjanjian' => 'Isi masa berlaku atau akhir perjanjian'])->withInput();
        }

        $mou = Mou::create([
            'pihak' => $request->pihak,
            'judul' => $request->judul,
            'bidang_id' => $request->bidang_id,
            'kategori_id' => $request->kategori_id,
            'nomor' => $request->nomor,
            'mulai_perjanjian' => $request->mulai_perjanjian,
            'masa_berlaku' => $request->masa_berlaku,
            'akhir_perjanjian' => $akhir,
            'status' => $akhir->isPast() ? 'kadaluarsa' : $request->status,
            'file_pdf' => $filePath,
            'uploaded_by' => auth()->id(),
        ]);

        ActivityLog::log('upload_mou', "Upload MOU: {$mou->judul}");

        return redirect()->route('mou.index')->with('success', 'MOU berhasil diupload.');
    }

    public function show(Mou $mou)
    {
        return view('mou.show', compact('mou'));
    }

    public function edit(Mou $mou)
    {
        $bidang = Bidang::orderBy('nama')->get();
        $kategori = Kategori::orderBy('nama')->get();
        return view('mou.edit', compact('mou', 'bidang', 'kategori'));
    }

    public function update(Request $request, Mou $mou)
    {
        $rules = [
            'pihak' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'bidang_id' => 'nullable|exists:bidang,id',
            'kategori_id' => 'nullable|exists:kategori,id',
            'nomor' => 'required|string|max:255|unique:mou,nomor,' . $mou->id,
            'mulai_perjanjian' => 'required|date',
            'masa_berlaku' => 'nullable|integer|min:1|max:3650',
            'akhir_perjanjian' => 'nullable|date|after_or_equal:mulai_perjanjian',
            'status' => 'required|in:aktif,kadaluarsa,dicabut',
        ];

        if ($request->hasFile('file_pdf')) {
            $rules['file_pdf'] = 'file|mimes:pdf|max:20480';
        }

        $request->validate($rules);

        $mulai = \Carbon\Carbon::parse($request->mulai_perjanjian);

        if ($request->filled('masa_berlaku')) {
            $akhir = $mulai->copy()->addDays((int) $request->masa_berlaku);
        } elseif ($request->filled('akhir_perjanjian')) {
            $akhir = \Carbon\Carbon::parse($request->akhir_perjanjian);
            $request->merge(['masa_berlaku' => (int) $mulai->diffInDays($akhir)]);
        } else {
            return back()->withErrors(['akhir_perjanjian' => 'Isi masa berlaku atau akhir perjanjian'])->withInput();
        }

        $data = [
            'pihak' => $request->pihak,
            'judul' => $request->judul,
            'bidang_id' => $request->bidang_id,
            'kategori_id' => $request->kategori_id,
            'nomor' => $request->nomor,
            'mulai_perjanjian' => $request->mulai_perjanjian,
            'masa_berlaku' => $request->masa_berlaku,
            'akhir_perjanjian' => $akhir,
            'status' => $akhir->isPast() ? 'kadaluarsa' : $request->status,
        ];

        if ($request->hasFile('file_pdf')) {
            $file = $request->file('file_pdf');

            if (!Storage::disk('public')->exists('mou')) {
                Storage::disk('public')->makeDirectory('mou');
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'mou/' . $fileName;
            Storage::disk('public')->put($filePath, file_get_contents($file->getPathname()));

            $data['file_pdf'] = $filePath;
        }

        $mou->update($data);

        if (isset($data['file_pdf']) && $data['file_pdf'] !== $mou->file_pdf) {
            Storage::disk('public')->delete($mou->getOriginal('file_pdf'));
        }

        ActivityLog::log('update_mou', "Update MOU: {$mou->judul}");

        return redirect()->route('mou.index')->with('success', 'MOU berhasil diupdate.');
    }

    public function destroy(Mou $mou)
    {
        $mou->delete();
        ActivityLog::log('hapus_mou', "Hapus MOU: {$mou->judul}");
        return response()->json(['success' => true]);
    }

    public function trashed()
    {
        $mou = Mou::onlyTrashed()->with(['uploader', 'bidang', 'kategori'])->get();
        return view('mou.trashed', compact('mou'));
    }

    public function restore($id)
    {
        $mou = Mou::withTrashed()->findOrFail($id);
        $mou->restore();
        ActivityLog::log('restore_mou', "Restore MOU: {$mou->judul}");
        return redirect()->route('mou.index')->with('success', 'MOU berhasil direstore.');
    }

    public function forceDelete($id)
    {
        $mou = Mou::withTrashed()->findOrFail($id);
        $judul = $mou->judul;
        if ($mou->file_pdf && Storage::disk('public')->exists($mou->file_pdf)) {
            Storage::disk('public')->delete($mou->file_pdf);
        }
        $mou->forceDelete();
        ActivityLog::log('hapus_permanen_mou', "Hapus permanen MOU: {$judul}");
        return redirect()->route('mou.trashed')->with('success', 'MOU berhasil dihapus permanen.');
    }

    public function download(Mou $mou)
    {
        if (!Storage::disk('public')->exists($mou->file_pdf)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }

        $filePath = Storage::disk('public')->path($mou->file_pdf);

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($filePath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($templateId);

            $orientation = $size['width'] > $size['height'] ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            $pdf->SetFont('Helvetica', 'B', 10);
            $pdf->SetTextColor(200, 0, 0);
            $pdf->SetXY($size['width'] - 32, 8);
            $pdf->Write(5, 'SALINAN');
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $mou->judul . '.pdf"');
    }

    public function preview(Mou $mou)
    {
        if (!Storage::disk('public')->exists($mou->file_pdf)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }
        return response()->file(Storage::disk('public')->path($mou->file_pdf));
    }
}
