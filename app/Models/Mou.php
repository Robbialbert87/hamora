<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Bidang;
use App\Models\Kategori;

class Mou extends Model
{
    use SoftDeletes;

    protected $table = 'mou';

    protected $fillable = [
        'pihak', 'judul', 'bidang_id', 'kategori_id', 'nomor', 'mulai_perjanjian', 'masa_berlaku',
        'akhir_perjanjian', 'status', 'file_pdf', 'uploaded_by'
    ];

    protected function casts(): array
    {
        return [
            'mulai_perjanjian' => 'date',
            'akhir_perjanjian' => 'date',
            'masa_berlaku' => 'integer',
            'bidang_id' => 'integer',
            'kategori_id' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeKadaluarsa($query)
    {
        return $query->where('status', 'kadaluarsa');
    }

    public function getKeteranganAttribute(): string
    {
        $akhir = $this->akhir_perjanjian ? \Carbon\Carbon::parse($this->akhir_perjanjian) : null;

        if (!$akhir) {
            return '-';
        }

        $now = now();
        if ($now->greaterThanOrEqualTo($akhir)) {
            return '0 Tahun 0 Bulan 0 Hari';
        }

        $diff = $now->diff($akhir);

        $parts = [];
        $parts[] = $diff->y . ' Tahun';
        $parts[] = $diff->m . ' Bulan';
        $parts[] = $diff->d . ' Hari';

        return implode(' ', $parts);
    }

    public function getMasaBerlakuFormattedAttribute(): string
    {
        $hari = $this->masa_berlaku;
        if (!$hari) {
            return '-';
        }

        $tahun = intdiv($hari, 365);
        $sisa = $hari % 365;
        $bulan = intdiv($sisa, 30);
        $hari_sisa = $sisa % 30;

        $parts = [];
        if ($tahun > 0) $parts[] = $tahun . ' Tahun';
        if ($bulan > 0) $parts[] = $bulan . ' Bulan';
        if ($hari_sisa > 0) $parts[] = $hari_sisa . ' Hari';

        return !empty($parts) ? implode(' ', $parts) : $hari . ' Hari';
    }
}
