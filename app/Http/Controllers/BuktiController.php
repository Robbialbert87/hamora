<?php
namespace App\Http\Controllers;

use App\Models\RekapBukti;
use App\Models\RekapBuktiField;
use App\Models\RekapBuktiRecord;
use App\Models\RekapBuktiFile;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use FPDF;
use Yajra\DataTables\Facades\DataTables;

class BuktiController extends Controller
{
    protected array $tipeFields = ['text', 'textarea', 'number', 'date', 'select', 'file'];

    public function index()
    {
        return view('bukti.index');
    }

    public function data()
    {
        $rekap = RekapBukti::select('rekap_buktis.*')->withCount(['fields', 'records']);

        return DataTables::of($rekap)
            ->addIndexColumn()
            ->addColumn('deskripsi_short', function ($r) {
                return $r->deskripsi ? Str::limit($r->deskripsi, 60) : '-';
            })
            ->addColumn('status_badge', function ($r) {
                $label = $r->status === 'aktif' ? 'Aktif' : 'Nonaktif';
                $color = $r->status === 'aktif' ? 'success' : 'secondary';
                return "<span class=\"badge bg-{$color}\">{$label}</span>";
            })
            ->addColumn('created_formatted', function ($r) {
                return $r->created_at->format('d/m/Y H:i');
            })
            ->addColumn('link_publik', function ($r) {
                $url = route('bukti.publik.form', $r->token);
                $badge = $r->status === 'aktif'
                    ? '<span class="badge bg-success-subtle text-success" style="font-size:11px;"><i class="ti ti-link me-1"></i>Bisa diisi</span>'
                    : '<span class="badge bg-secondary-subtle text-secondary" style="font-size:11px;"><i class="ti ti-link-off me-1"></i>Ditutup</span>';
                return '<a href="' . e($url) . '" target="_blank" class="text-primary fw-medium" style="font-size:12px; word-break:break-all;">' . e($url) . '</a><br>' . $badge;
            })
            ->addColumn('action', function ($r) {
                $btn = '<div class="d-flex align-items-center gap-1">';
                $btn .= '<a href="' . e(route('bukti.show', $r->id)) . '" class="btn btn-light-info btn-icon" style="flex: 0 0 auto;" title="Lihat Rekap"><i class="ti ti-eye"></i></a>';
                $btn .= '<a href="' . e(route('bukti.edit', $r->id)) . '" class="btn btn-light-warning btn-icon" style="flex: 0 0 auto;" title="Edit"><i class="ti ti-pencil"></i></a>';
                $btn .= '<button class="btn btn-light-danger btn-icon btn-delete" style="flex: 0 0 auto;" title="Hapus" data-url="' . e(route('bukti.destroy', $r->id)) . '" data-name="' . e($r->nama) . '"><i class="ti ti-trash"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status_badge', 'link_publik', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('bukti.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'status' => 'required|in:aktif,nonaktif',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.tipe' => 'required|in:' . implode(',', $this->tipeFields),
            'fields.*.required' => 'nullable|boolean',
            'fields.*.options' => 'nullable|string|max:1000',
        ], [
            'nama.required' => 'Nama formulir wajib diisi.',
            'fields.required' => 'Minimal tambahkan satu kolom.',
            'fields.min' => 'Minimal tambahkan satu kolom.',
            'fields.*.label.required' => 'Label kolom wajib diisi.',
            'fields.*.tipe.in' => 'Jenis kolom tidak valid.',
        ]);

        $rekap = RekapBukti::create([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'token' => RekapBukti::generateToken(),
            'status' => $validated['status'],
            'created_by' => auth()->id(),
        ]);

        $this->simpanFields($rekap, $request->input('fields', []));

        ActivityLog::log('buat_bukti', "Buat rekap bukti: {$rekap->nama}");

        return redirect()->route('bukti.show', $rekap->id)
            ->with('success', 'Formulir berhasil dibuat. Salin link untuk mulai mengumpulkan data.');
    }

    public function show(RekapBukti $rekapBukti)
    {
        $rekapBukti->load(['fields', 'creator']);

        $stats = [
            'total_entri' => $rekapBukti->records()->count(),
            'total_file' => RekapBuktiFile::where('rekap_bukti_id', $rekapBukti->id)->count(),
            'terakhir' => $rekapBukti->records()->latest('created_at')->value('created_at'),
        ];

        $records = $rekapBukti->records()->with('files')->latest()->paginate(15);

        return view('bukti.show', compact('rekapBukti', 'records', 'stats'));
    }

    public function edit(RekapBukti $rekapBukti)
    {
        $rekapBukti->load('fields');

        return view('bukti.edit', compact('rekapBukti'));
    }

    public function update(Request $request, RekapBukti $rekapBukti)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
            'status' => 'required|in:aktif,nonaktif',
            'fields' => 'required|array|min:1',
            'fields.*.label' => 'required|string|max:255',
            'fields.*.tipe' => 'required|in:' . implode(',', $this->tipeFields),
            'fields.*.required' => 'nullable|boolean',
            'fields.*.options' => 'nullable|string|max:1000',
        ], [
            'nama.required' => 'Nama formulir wajib diisi.',
            'fields.required' => 'Minimal tambahkan satu kolom.',
            'fields.min' => 'Minimal tambahkan satu kolom.',
            'fields.*.label.required' => 'Label kolom wajib diisi.',
            'fields.*.tipe.in' => 'Jenis kolom tidak valid.',
        ]);

        $rekapBukti->update([
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'status' => $validated['status'],
        ]);

        $this->sinkronFields($rekapBukti, $request->input('fields', []));

        ActivityLog::log('edit_bukti', "Edit rekap bukti: {$rekapBukti->nama}");

        return redirect()->route('bukti.show', $rekapBukti->id)
            ->with('success', 'Formulir berhasil diperbarui.');
    }

    public function toggle(RekapBukti $rekapBukti)
    {
        $rekapBukti->update([
            'status' => $rekapBukti->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);

        ActivityLog::log('toggle_bukti', "Ubah status link rekap bukti: {$rekapBukti->nama}");

        return response()->json(['success' => true, 'status' => $rekapBukti->status]);
    }

    public function destroy(RekapBukti $rekapBukti)
    {
        $nama = $rekapBukti->nama;

        $files = RekapBuktiFile::where('rekap_bukti_id', $rekapBukti->id)->get();
        foreach ($files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        $rekapBukti->forceDelete();

        ActivityLog::log('hapus_bukti', "Hapus rekap bukti: {$nama}");

        return response()->json(['success' => true]);
    }

    public function destroyRecord(RekapBuktiRecord $record)
    {
        $namaRekap = $record->rekap->nama;

        foreach ($record->files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        $record->delete();

        ActivityLog::log('hapus_entri_bukti', "Hapus entri bukti pada: {$namaRekap}");

        return response()->json(['success' => true]);
    }

    public function preview(RekapBuktiRecord $record, RekapBuktiFile $file)
    {
        abort_unless($file->record_id === $record->id, 404);
        abort_unless(Storage::disk('public')->exists($file->path), 404);

        return Storage::disk('public')->response($file->path, $file->nama_asli);
    }

    public function download(RekapBuktiRecord $record, RekapBuktiFile $file)
    {
        abort_unless($file->record_id === $record->id, 404);
        abort_unless(Storage::disk('public')->exists($file->path), 404);

        return Storage::disk('public')->download($file->path, $file->nama_asli);
    }

    public function report(RekapBukti $rekapBukti)
    {
        $rekapBukti->load('fields');

        $records = $rekapBukti->records()->with('files')->latest()->get();

        if ($records->isEmpty()) {
            return back()->with('error', 'Belum ada data masuk untuk dicetak.');
        }

        $pdf = new class extends FPDF
        {
            public string $footerText = '';

            public function Footer(): void
            {
                $this->SetY(-12);
                $this->SetFont('Helvetica', '', 8);
                $this->SetTextColor(110, 110, 110);
                $this->Cell(0, 6, $this->footerText, 0, 0, 'L');
                $this->Cell(0, 6, 'Halaman ' . $this->PageNo() . ' / {nb}', 0, 0, 'R');
            }
        };

        $pdf->AliasNbPages();
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetAutoPageBreak(false);
        $pdf->footerText = $this->pdfText('Dicetak oleh ' . (auth()->user()->name ?? '-') . ' pada ' . now()->format('d/m/Y H:i'));
        $pdf->AddPage('L', 'A4');

        $this->drawReportKop($pdf, $rekapBukti, $records->count());

        $fields = $rekapBukti->fields;
        $noW = 12.0;
        $timeW = 32.0;
        $fieldW = floor((277 - $noW - $timeW) / max(1, $fields->count()));
        $lineH = 4.2;

        $this->drawReportHeader($pdf, $fields, $noW, $fieldW, $timeW);

        $fill = false;
        foreach ($records as $index => $record) {
            $cells = [(string) ($index + 1)];

            foreach ($fields as $field) {
                if ($field->tipe === 'file') {
                    $file = $record->fileFor($field);
                    $cells[] = ($file && Storage::disk('public')->exists($file->path)) ? $file->nama_asli : '-';
                } else {
                    $value = $record->valueFor($field);
                    if ($value === null || $value === '') {
                        $cells[] = '-';
                    } elseif ($field->tipe === 'date') {
                        $cells[] = Carbon::parse($value)->format('d/m/Y');
                    } else {
                        $cells[] = $this->clampText((string) $value);
                    }
                }
            }

            $cells[] = $record->created_at->format('d/m/Y H:i');

            $pdf->SetFont('Helvetica', '', 8);
            $maxLines = 1;
            $wrapped = [];
            foreach ($cells as $i => $text) {
                $width = $i === 0 ? $noW : ($i === count($cells) - 1 ? $timeW : $fieldW);
                $wrapped[$i] = $this->wrapText($pdf, $this->pdfText($text), $width);
                $maxLines = max($maxLines, substr_count($wrapped[$i], "\n") + 1);
            }

            $rowH = $maxLines * $lineH;

            if ($pdf->GetY() + $rowH > 182) {
                $pdf->AddPage('L', 'A4');
                $this->drawReportHeader($pdf, $fields, $noW, $fieldW, $timeW);
                $pdf->SetFont('Helvetica', '', 8);
                $fill = !$fill;
            }

            $y = $pdf->GetY();
            $pdf->SetFillColor($fill ? 245 : 255, $fill ? 246 : 255, $fill ? 249 : 255);
            $pdf->SetDrawColor(190, 190, 190);
            $pdf->SetTextColor(40, 40, 40);

            $x = 10;
            foreach ($cells as $i => $text) {
                $width = $i === 0 ? $noW : ($i === count($cells) - 1 ? $timeW : $fieldW);
                $align = ($i === 0 || $i === count($cells) - 1) ? 'C' : 'L';
                $cellText = $wrapped[$i] . str_repeat("\n", $maxLines - substr_count($wrapped[$i], "\n"));
                $pdf->SetXY($x, $y);
                $pdf->MultiCell($width, $lineH, $cellText, 1, $align, $fill);
                $x += $width;
            }
            $pdf->SetY($y + $rowH);
            $fill = !$fill;
        }

        ActivityLog::log('unduh_laporan_bukti', "Unduh laporan data: {$rekapBukti->nama}");

        $slug = Str::slug($rekapBukti->nama) ?: 'data';

        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Laporan-' . $slug . '.pdf"',
        ]);
    }

    public function filesZip(RekapBukti $rekapBukti)
    {
        $files = RekapBuktiFile::where('rekap_bukti_id', $rekapBukti->id)
            ->whereNotNull('path')
            ->with(['record', 'field'])
            ->get()
            ->filter(fn (RekapBuktiFile $file) => Storage::disk('public')->exists($file->path));

        if ($files->isEmpty()) {
            return back()->with('error', 'Belum ada file untuk diunduh.');
        }

        $fieldPengirim = $this->cariFieldPengirim($rekapBukti);

        $zipPath = tempnam(sys_get_temp_dir(), 'bukti-zip-');
        if ($zipPath === false) {
            return back()->with('error', 'Gagal membuat file arsip sementara.');
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::OVERWRITE | \ZipArchive::CREATE) !== true) {
            @unlink($zipPath);
            return back()->with('error', 'Gagal membuat file arsip.');
        }

        $usedNames = [];

        foreach ($files as $file) {
            $entryName = $this->zipEntryName($this->namaEntriZip($file, $fieldPengirim));

            $finalName = $entryName;
            $counter = 2;
            while (isset($usedNames[$finalName])) {
                $finalName = $this->zipDedupeName($entryName, $counter++);
            }
            $usedNames[$finalName] = true;

            $zip->addFile(Storage::disk('public')->path($file->path), $finalName);
        }

        $zip->close();

        ActivityLog::log('unduh_semua_bukti', "Unduh semua file bukti: {$rekapBukti->nama}");

        $slug = Str::slug($rekapBukti->nama) ?: 'data';

        return response()->download($zipPath, 'Bukti-' . $slug . '.zip', [
            'Content-Type' => 'application/zip',
        ])->deleteFileAfterSend(true);
    }

    protected function cariFieldPengirim(RekapBukti $rekap): ?RekapBuktiField
    {
        return $rekap->fields
            ->first(fn (RekapBuktiField $field) => $field->tipe === 'text'
                && preg_match('/nama|pengirim|identitas/i', (string) $field->label) === 1);
    }

    protected function namaEntriZip(RekapBuktiFile $file, ?RekapBuktiField $fieldPengirim): string
    {
        if ($fieldPengirim && $file->field && $file->record) {
            $pengirim = trim((string) ($file->record->data[$fieldPengirim->id] ?? ''));

            if ($pengirim !== '') {
                $ext = pathinfo($file->nama_asli, PATHINFO_EXTENSION);
                $base = $this->zipNamePart("{$pengirim} - {$file->field->label}");

                return $ext !== '' ? "{$base}.{$ext}" : $base;
            }
        }

        return $file->nama_asli;
    }

    protected function zipNamePart(string $text): string
    {
        $text = preg_replace('/[^\w\s.\-\x{0080}-\x{FFFF}]/u', '_', $text) ?? $text;
        $text = trim($text);

        return $text !== '' && $text !== '.' && $text !== '..' ? $text : 'file';
    }

    protected function zipEntryName(string $nama): string
    {
        $nama = basename(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $nama));
        $nama = preg_replace('/[^\w\s.\-\x{0080}-\x{FFFF}]/u', '_', $nama) ?? $nama;
        $nama = trim($nama);

        return $nama !== '' && $nama !== '.' && $nama !== '..' ? $nama : 'file';
    }

    protected function zipDedupeName(string $nama, int $counter): string
    {
        $dot = strrpos($nama, '.');

        if ($dot === false) {
            return "{$nama} ({$counter})";
        }

        return substr($nama, 0, $dot) . " ({$counter})" . substr($nama, $dot);
    }

    protected function drawReportKop(FPDF $pdf, RekapBukti $rekap, int $totalEntri): void
    {
        $pdf->SetTextColor(40, 40, 40);
        $pdf->SetFont('Helvetica', 'B', 13);
        $pdf->Cell(0, 7, $this->pdfText('Himpunan Arsip Manajemen Online RSUD Abdul Manap'), 0, 1, 'C');
        $pdf->SetFont('Helvetica', 'B', 16);
        $pdf->Cell(0, 8, 'LAPORAN DATA', 0, 1, 'C');
        $pdf->SetFont('Helvetica', 'B', 11);
        $pdf->Cell(0, 6, $this->pdfText($rekap->nama), 0, 1, 'C');

        $pdf->SetFont('Helvetica', '', 8.5);
        $pdf->SetTextColor(80, 80, 80);
        $pdf->Cell(0, 5, $this->pdfText("Jumlah entri: {$totalEntri}  |  Dicetak: " . now()->format('d/m/Y H:i')), 0, 1, 'C');
        $pdf->Ln(3);
    }

    protected function drawReportHeader(FPDF $pdf, $fields, float $noW, float $fieldW, float $timeW): void
    {
        $pdf->SetFillColor(226, 232, 255);
        $pdf->SetDrawColor(180, 180, 180);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(40, 40, 40);
        $pdf->SetX(10);
        $pdf->Cell($noW, 7, 'No', 1, 0, 'C', true);

        foreach ($fields as $field) {
            $label = $this->pdfText($field->label);
            $label = $this->truncateToWidth($pdf, $label, $fieldW - 2);
            $pdf->Cell($fieldW, 7, $label, 1, 0, 'C', true);
        }

        $pdf->Cell($timeW, 7, 'Waktu Kirim', 1, 0, 'C', true);
        $pdf->Ln();
    }

    protected function pdfText(?string $text): string
    {
        $text = (string) ($text ?? '');

        $converted = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');

        return $converted === false
            ? preg_replace('/[^\x20-\x7E]/', '?', $text) ?? ''
            : $converted;
    }

    protected function clampText(string $text): string
    {
        if (mb_strlen($text) > 200) {
            return mb_substr($text, 0, 197) . '...';
        }

        return $text;
    }

    protected function truncateToWidth(FPDF $pdf, string $text, float $width): string
    {
        $width = max(4, $width);

        while ($text !== '' && $pdf->GetStringWidth($text) > $width) {
            $text = substr($text, 0, -1);
        }

        return $text;
    }

    protected function wrapText(FPDF $pdf, string $text, float $width): string
    {
        $width = max(2, $width - 2);
        $lines = [];
        $remaining = $text;

        while ($remaining !== '') {
            if ($pdf->GetStringWidth($remaining) <= $width) {
                $lines[] = $remaining;
                break;
            }

            $cut = $remaining;
            while (strlen($cut) > 1 && $pdf->GetStringWidth($cut) > $width) {
                $cut = substr($cut, 0, -1);
            }

            $spacePos = strrpos($cut, ' ');
            if ($spacePos !== false && $spacePos > 0) {
                $lines[] = substr($cut, 0, $spacePos);
                $remaining = ltrim(substr($remaining, $spacePos));
            } else {
                $lines[] = $cut;
                $remaining = substr($remaining, strlen($cut));
            }
        }

        return implode("\n", $lines);
    }

    protected function simpanFields(RekapBukti $rekap, array $fields): void
    {
        $urutan = 0;

        foreach ($fields as $field) {
            RekapBuktiField::create([
                'rekap_bukti_id' => $rekap->id,
                'label' => $field['label'],
                'tipe' => $field['tipe'],
                'required' => !empty($field['required']),
                'options' => $this->parseOptions($field),
                'urutan' => $urutan++,
            ]);
        }
    }

    protected function sinkronFields(RekapBukti $rekap, array $fields): void
    {
        $submittedIds = [];
        $urutan = 0;

        foreach ($fields as $field) {
            $data = [
                'label' => $field['label'],
                'tipe' => $field['tipe'],
                'required' => !empty($field['required']),
                'options' => $this->parseOptions($field),
                'urutan' => $urutan++,
            ];

            if (!empty($field['id'])) {
                $existing = RekapBuktiField::where('rekap_bukti_id', $rekap->id)->find($field['id']);
                if ($existing) {
                    $existing->update($data);
                    $submittedIds[] = $existing->id;
                    continue;
                }
            }

            $created = RekapBuktiField::create($data + ['rekap_bukti_id' => $rekap->id]);
            $submittedIds[] = $created->id;
        }

        $removed = RekapBuktiField::where('rekap_bukti_id', $rekap->id)
            ->whereNotIn('id', $submittedIds)
            ->get();

        foreach ($removed as $field) {
            $files = RekapBuktiFile::where('rekap_bukti_id', $rekap->id)->where('field_id', $field->id)->get();
            foreach ($files as $file) {
                Storage::disk('public')->delete($file->path);
                $file->delete();
            }
            $field->delete();
        }
    }

    protected function parseOptions(array $field): ?array
    {
        if (($field['tipe'] ?? '') !== 'select' || empty($field['options'])) {
            return null;
        }

        $options = array_values(array_filter(array_map('trim', explode("\n", (string) $field['options']))));

        return $options ?: null;
    }
}
