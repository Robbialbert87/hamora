<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekapBuktiFile extends Model
{
    protected $fillable = [
        'record_id', 'rekap_bukti_id', 'field_id', 'nama_asli', 'path'
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(RekapBuktiRecord::class, 'record_id');
    }

    public function rekap(): BelongsTo
    {
        return $this->belongsTo(RekapBukti::class, 'rekap_bukti_id');
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(RekapBuktiField::class, 'field_id');
    }
}
