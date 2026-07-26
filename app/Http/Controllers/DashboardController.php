<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Mou;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Kategori;
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
            ->get()
            ->keyBy('tahun');

        $mouPerTahun = Mou::select(DB::raw('YEAR(mulai_perjanjian) as tahun, count(*) as total'))
            ->groupBy('tahun')
            ->orderBy('tahun')
            ->get()
            ->keyBy('tahun');

        $semuaTahun = $dokumenPerTahun->keys()
            ->merge($mouPerTahun->keys())
            ->unique()
            ->sort()
            ->values();

        $chartDokumenData = $semuaTahun->map(fn($t) => $dokumenPerTahun[$t]->total ?? 0);
        $chartMouData = $semuaTahun->map(fn($t) => $mouPerTahun[$t]->total ?? 0);

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

        // MOU Expiration
        $totalMou = Mou::count();
        $mouAktif = Mou::where('status', 'aktif')->count();
        $mouMendekati = Mou::where('status', 'aktif')
            ->whereBetween('akhir_perjanjian', [now()->addDay(), now()->addDays(30)])
            ->count();
        $mouKadaluarsa = Mou::where('status', 'kadaluarsa')
            ->orWhere(function ($q) {
                $q->where('status', 'aktif')
                  ->where('akhir_perjanjian', '<=', now());
            })
            ->count();
        $pctMendekati = $totalMou > 0 ? round($mouMendekati / $totalMou * 100) : 0;

        $mouTerdekat = Mou::where('status', 'aktif')
            ->where('akhir_perjanjian', '>', now())
            ->orderBy('akhir_perjanjian', 'asc')
            ->take(5)
            ->get(['id', 'judul', 'nomor', 'akhir_perjanjian']);

        $bulanLabel = [];
        $bulanData = [];
        for ($i = 0; $i < 12; $i++) {
            $bulan = now()->addMonths($i);
            $bulanLabel[] = $bulan->locale('id')->isoFormat('MMM YYYY');
            $bulanData[] = (int) Mou::where('status', 'aktif')
                ->whereYear('akhir_perjanjian', $bulan->year)
                ->whereMonth('akhir_perjanjian', $bulan->month)
                ->count();
        }

        return view('dashboard.index', compact(
            'totalDokumen', 'dokumenAktif', 'dokumenDirevisi', 'dokumenKadaluarsa', 'totalUser',
            'semuaTahun', 'chartDokumenData', 'chartMouData', 'chartBidangLabels', 'chartBidangData',
            'chartKategoriLabels', 'chartKategoriData', 'dokumenTerbaru',
            'totalMou', 'mouAktif', 'mouMendekati', 'mouKadaluarsa', 'pctMendekati',
            'mouTerdekat', 'bulanLabel', 'bulanData'
        ));
    }

}
