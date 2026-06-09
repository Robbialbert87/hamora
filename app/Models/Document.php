<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nomor_dokumen', 'nama_dokumen', 'tahun', 'bidang_id', 'kategori_id',
        'tanggal_terbit', 'tanggal_berlaku', 'versi', 'status',
        'deskripsi', 'file_pdf', 'parent_document_id', 'uploaded_by', 'verified_by'
    ];

    protected function casts(): array
    {
        return [
            'tanggal_terbit' => 'date',
            'tanggal_berlaku' => 'date',
            'tahun' => 'integer',
            'versi' => 'integer',
        ];
    }

    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_document_id');
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_document_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(DocumentVersion::class);
    }

    public function latestVersion()
    {
        return $this->versions()->orderBy('versi', 'desc');
    }

    public function isLatest(): bool
    {
        return !self::where('parent_document_id', $this->id)->exists();
    }

    public function latestRevision(): ?self
    {
        return self::where('parent_document_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function revisionHistory(): array
    {
        $history = collect();

        $ancestors = collect();
        $current = $this->parent;
        while ($current) {
            $ancestors->push($current);
            $current = $current->parent;
        }
        $history = $history->merge($ancestors->reverse());

        $history->push($this);

        $descendants = collect();
        $current = $this->latestRevision();
        while ($current) {
            $descendants->push($current);
            $current = $current->latestRevision();
        }
        $history = $history->merge($descendants);

        if ($history->count() <= 1) return [];

        return $history->values()->all();
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
