<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapBuktiField extends Model
{
    protected $fillable = [
        'rekap_bukti_id', 'label', 'tipe', 'required', 'options', 'urutan'
    ];

    protected function casts(): array
    {
        return [
            'required' => 'boolean',
            'options' => 'array',
            'urutan' => 'integer',
        ];
    }

    public function rekap(): BelongsTo
    {
        return $this->belongsTo(RekapBukti::class, 'rekap_bukti_id');
    }
}
