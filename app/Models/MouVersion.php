<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouVersion extends Model
{
    protected $table = 'mou_versions';

    protected $fillable = [
        'mou_id', 'versi', 'nomor', 'pihak', 'judul', 'mulai_perjanjian', 'akhir_perjanjian',
        'masa_berlaku', 'file_pdf', 'keterangan', 'created_by'
    ];

    protected function casts(): array
    {
        return [
            'mulai_perjanjian' => 'date',
            'akhir_perjanjian' => 'date',
            'masa_berlaku' => 'integer',
            'versi' => 'integer',
        ];
    }

    public function mou(): BelongsTo
    {
        return $this->belongsTo(Mou::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
