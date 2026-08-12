<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RekapBuktiRecord extends Model
{
    protected $fillable = [
        'rekap_bukti_id', 'data'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function rekap(): BelongsTo
    {
        return $this->belongsTo(RekapBukti::class, 'rekap_bukti_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(RekapBuktiFile::class, 'record_id');
    }

    public function valueFor(RekapBuktiField $field): mixed
    {
        return $this->data[$field->id] ?? null;
    }

    public function fileFor(RekapBuktiField $field): ?RekapBuktiFile
    {
        return $this->files->firstWhere('field_id', $field->id);
    }
}
