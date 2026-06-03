<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Kategori;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDokumen = Document::count();
        $dokumenAktif = Document::where('status', 'aktif')->count();
        $dokumenDirevisi = Document::where('status', 'direvisi')->count();
        $dokumenKadaluarsa = Document::where('status', 'kadaluarsa')->count();
        $totalUser = User::count();

        $dokumenPerTahun = Document::select(DB::raw('tahun, count(*) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get();
        $chartTahunLabels = $dokumenPerTahun->pluck('tahun');
        $chartTahunData = $dokumenPerTahun->pluck('total');

        $dokumenPerBidang = Bidang::withCount('documents')->get();
        $chartBidangLabels = $dokumenPerBidang->pluck('nama');
        $chartBidangData = $dokumenPerBidang->pluck('documents_count');

        $dokumenPerKategori = Kategori::withCount('documents')->get();
        $chartKategoriLabels = $dokumenPerKategori->pluck('nama');
        $chartKategoriData = $dokumenPerKategori->pluck('documents_count');

        $dokumenTerbaru = Document::with(['bidang', 'kategori', 'uploader'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact(
            'totalDokumen', 'dokumenAktif', 'dokumenDirevisi', 'dokumenKadaluarsa', 'totalUser',
            'chartTahunLabels', 'chartTahunData', 'chartBidangLabels', 'chartBidangData',
            'chartKategoriLabels', 'chartKategoriData', 'dokumenTerbaru'
        ));
    }

    public function notifikasiKadaluarsa()
    {
        $exitCode = Artisan::call('app:cek-kadaluarsa');
        $output = Artisan::output();

        \App\Models\ActivityLog::log('notifikasi', 'Trigger notifikasi kadaluarsa dari dashboard', [
            'exit_code' => $exitCode,
            'output' => trim($output),
        ]);

        return redirect()->route('dashboard')->with(
            'success',
            'Notifikasi kadaluarsa selesai. ' . trim($output)
        );
    }
}
