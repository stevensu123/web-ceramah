<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cerita extends Model
{
    use HasFactory;
    protected $fillable = [
        "nama_kategori_pagi",
        "gambar_pagi",
        "text_cerita_pagi",
        "keterangan_pagi",
        "nama_kategori_siang",
        "gambar_siang",
        "text_cerita_siang",
        "keterangan_siang",
        "nama_kategori_sore",
        "gambar_sore",
        "text_cerita_sore",
        "keterangan_sore",
        "status",
        "user_id",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategoris()
    {
        return $this->belongsToMany(Kategori::class, 'cerita_kategori');
    }

    public function waktu()
    {
        return $this->hasMany(waktu::class);
    }
}
