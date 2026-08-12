<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RekapBukti extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama', 'deskripsi', 'token', 'status', 'created_by'
    ];

    protected function casts(): array
    {
        return [];
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(16);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(RekapBuktiField::class)->orderBy('urutan')->orderBy('id');
    }

    public function records(): HasMany
    {
        return $this->hasMany(RekapBuktiRecord::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif';
    }
}
