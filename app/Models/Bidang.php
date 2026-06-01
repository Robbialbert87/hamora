<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Bidang extends Model
{
    protected $table = 'bidang';

    protected $fillable = ['nama', 'slug', 'deskripsi'];

    protected static function booted()
    {
        static::creating(function ($bidang) {
            $bidang->slug = Str::slug($bidang->nama);
        });
        static::updating(function ($bidang) {
            $bidang->slug = Str::slug($bidang->nama);
        });
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
