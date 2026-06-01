<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama', 'slug', 'deskripsi'];

    protected static function booted()
    {
        static::creating(function ($kategori) {
            $kategori->slug = Str::slug($kategori->nama);
        });
        static::updating(function ($kategori) {
            $kategori->slug = Str::slug($kategori->nama);
        });
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
