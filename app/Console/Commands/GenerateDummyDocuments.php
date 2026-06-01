<?php
namespace App\Console\Commands;

use App\Models\Document;
use App\Models\Bidang;
use App\Models\Kategori;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateDummyDocuments extends Command
{
    protected $signature = 'documents:generate-dummy';
    protected $description = 'Generate 100 dummy documents with PDF files';

    private $namaDokumen = [
        'Standar Pelayanan Rawat Jalan',
        'Pedoman Pengendalian Infeksi',
        'SOP Pendaftaran Pasien Baru',
        'Kebijakan Mutu Rumah Sakit',
        'SK Direktur tentang Jam Kerja',
        'Pedoman Pelayanan Farmasi',
        'SOP Pengelolaan Obat',
        'Program Kerja Keperawatan',
        'SK Tim Pencegahan Infeksi',
        'Pedoman Rekam Medis',
        'SOP Pengisian Rekam Medis',
        'Kebijakan Keselamatan Pasien',
        'SK Komite Medik',
        'Pedoman Gizi Rumah Sakit',
        'SOP Pelayanan Gizi',
        'Program Kerja PPI',
        'SK Direktur tentang Tarif',
        'Pedoman Akreditasi RS',
        'SOP Tanggap Darurat',
        'Kebijakan SDM',
        'SK Struktur Organisasi',
        'Pedoman Pelayanan Radiologi',
        'SOP Pemeriksaan Radiologi',
        'Program Kerja PMKP',
        'SK Mutu dan Keselamatan',
        'Pedoman Laboratorium',
        'SOP Pengambilan Sampel',
        'Kebijakan Kepegawaian',
        'SK Pengangkatan Jabatan',
        'Pedoman Hukum dan Humas',
        'SOP Pengaduan Masyarakat',
        'Program Kerja IPSRS',
        'SK Tim Akreditasi',
        'Pedoman Pengelolaan Limbah',
        'SOP Sterilisasi Alat',
        'Kebijakan Pendidikan dan Pelatihan',
        'SK Panitia PPI',
        'Pedoman Pelayanan ICU',
        'SOP Tindakan Keperawatan',
        'Program Kerja Bidang Keperawatan',
        'SK Direktur tentang Cuti',
        'Pedoman Penanggulangan Bencana',
        'SOP Evakuasi Pasien',
        'Kebijakan Pengadaan Barang',
        'SK Pengelolaan Aset',
        'Pedoman Sistem Informasi',
        'SOP Backup Data',
        'Program Kerja IT RS',
        'SK Komite Mutu',
        'Pedoman Pelayanan IGD',
        'SOP Triase Pasien',
        'Kebijakan Penggunaan Antibiotik',
        'SK Formularium RS',
        'Pedoman Pelayanan Hemodialisa',
        'SOP Cuci Tangan',
        'Program Kerja K3 RS',
        'SK Keselamatan Kebakaran',
        'Pedoman Pelayanan VK',
        'SOP Persalinan Normal',
        'Kebijakan Parkir RS',
        'SK Keamanan dan Ketertiban',
        'Pedoman Pelayanan Perinatologi',
        'SOP Perawatan Bayi',
        'Program Kerja Diklat',
        'SK Beasiswa Pendidikan',
        'Pedoman Penelitian RS',
        'SOP Pengajuan Penelitian',
        'Kebijakan Etika Penelitian',
        'SK Komite Etik',
        'Pedoman Pelayanan Anestesi',
        'SOP Operasi Bersih',
        'Program Kerja Bedah',
        'SK Pelayanan Unggulan',
        'Pedoman Pengelolaan Donor Darah',
        'SOP Transfusi Darah',
        'Kebijakan Kerohanian',
        'SK Pelayanan Rohani',
        'Pedoman Pelayanan Psikologi',
        'SOP Konseling Pasien',
        'Program Kerja Promkes',
        'SK Penyuluhan Kesehatan',
        'Pedoman Pelayanan Rehabilitasi',
        'SOP Fisioterapi',
        'Kebijakan Rujukan Pasien',
        'SK Jejaring Rujukan',
        'Pedoman Pelayanan Akupuntur',
        'SOP Pengobatan Tradisional',
        'Program Kerja Herbal',
        'SK Pelayanan Komplementer',
        'Pedoman Pengelolaan Parkir',
        'SOP Kebersihan Lingkungan',
        'Kebijakan Laundry RS',
        'SK Pengelolaan Limbah Medis',
        'Pedoman Pelayanan Gawat Darurat',
        'SOP BLS dan ACLS',
        'Program Kerja Code Blue',
        'SK Tim Code Blue',
        'Pedoman Pelayanan Intensif',
        'SOP Ventilator',
        'Kebijakan Pembatasan Pengunjung',
        'SK Protokol Kesehatan',
    ];

    public function handle()
    {
        $bidangIds = Bidang::pluck('id')->toArray();
        $kategoriIds = Kategori::pluck('id')->toArray();
        $userIds = User::pluck('id')->toArray();

        if (empty($bidangIds) || empty($kategoriIds) || empty($userIds)) {
            $this->error('Run migrate:fresh --seed first!');
            return 1;
        }

        Document::query()->forceDelete();

        $statuses = ['draft', 'aktif', 'aktif', 'aktif', 'direvisi', 'kadaluarsa'];
        $years = range(2019, 2026);
        $this->info('Generating 100 dummy documents...');

        $bar = $this->output->createProgressBar(100);
        $bar->start();

        for ($i = 0; $i < 100; $i++) {
            $tahun = $years[array_rand($years)];
            $bidangId = $bidangIds[array_rand($bidangIds)];
            $kategoriId = $kategoriIds[array_rand($kategoriIds)];
            $uploaderId = $userIds[array_rand($userIds)];
            $verifierId = $userIds[array_rand($userIds)];
            $status = $statuses[array_rand($statuses)];
            $nama = $this->namaDokumen[$i] ?? 'Dokumen ' . ($i + 1);
            $nomor = strtoupper(substr(str_replace(' ', '', $nama), 0, 3)) . '/' . str_pad($i + 1, 4, '0', STR_PAD_LEFT) . '/' . $tahun;
            $versi = rand(1, 3);
            $bulan = str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT);
            $hari = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);

            $pdfContent = $this->generatePdf($nomor, $nama, $tahun, $versi);
            $path = "documents/{$tahun}/dummy_{$i}_" . time() . '.pdf';
            Storage::disk('public')->put($path, $pdfContent);

            $doc = Document::create([
                'nomor_dokumen' => $nomor,
                'nama_dokumen' => $nama,
                'tahun' => $tahun,
                'bidang_id' => $bidangId,
                'kategori_id' => $kategoriId,
                'tanggal_terbit' => "{$tahun}-{$bulan}-{$hari}",
                'tanggal_berlaku' => rand(0, 1) ? date('Y-m-d', strtotime("+{$versi} years", strtotime("{$tahun}-{$bulan}-{$hari}"))) : null,
                'versi' => $versi,
                'status' => $status,
                'deskripsi' => "Dokumen {$nama} tahun {$tahun} versi {$versi}",
                'file_pdf' => $path,
                'uploaded_by' => $uploaderId,
                'verified_by' => $status === 'aktif' ? $verifierId : null,
            ]);

            ActivityLog::create([
                'user_id' => $uploaderId,
                'action' => 'upload',
                'description' => "Upload dokumen: {$nama}",
                'data' => json_encode(['document_id' => $doc->id]),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('100 dummy documents created successfully!');
    }

    private function generatePdf(string $nomor, string $nama, int $tahun, int $versi): string
    {
        $content = "
DOKUMEN HAMORA
RSUD H. Abdul Manap
================================
Nomor       : {$nomor}
Nama        : {$nama}
Tahun       : {$tahun}
Versi       : {$versi}
Status      : -
================================

This is a dummy document generated for testing purposes.

RSUD H. Abdul Manap
Kota Jambi
{$tahun}
        ";

        $escaped = $this->escapePdfString($content);
        $stream = $this->pdfStream($content);

        return <<<PDF
%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792]
   /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>
endobj
4 0 obj
<< /Length {$stream['length']} >>
stream
{$stream['data']}
endstream
endobj
5 0 obj
<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>
endobj
xref
0 6
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000266 00000 n 
{$stream['offset']} 00000 n 
trailer
<< /Size 6 /Root 1 0 R >>
startxref
{$stream['xref']}
%%EOF
PDF;
    }

    private function escapePdfString(string $s): string
    {
        $s = str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], $s);
        return $s;
    }

    private function pdfStream(string $text): array
    {
        $lines = explode("\n", $text);
        $pdfContent = '';
        foreach ($lines as $line) {
            $escaped = $this->escapePdfString($line);
            $pdfContent .= "BT /F1 10 Tf 50 {$this->getY()} Td ({$escaped}) Tj ET\n";
        }
        $data = $pdfContent;
        $length = strlen($data);
        $offset = strlen($data) + 366;
        $xref = $offset + strlen("{$offset} 00000 n \n");

        return [
            'data' => $data,
            'length' => $length,
            'offset' => $offset,
            'xref' => $xref,
        ];
    }

    private function getY(): string
    {
        static $y = 750;
        $y -= 15;
        if ($y < 50) $y = 750;
        return (string)$y;
    }
}
