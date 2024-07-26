<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;
    protected $fillable = [
        "gambar",
        "nama_kategori",
        "status",
        "keterangan",
   
    ];

    public function ceritas()
    {
        return $this->belongsToMany(Cerita::class, 'cerita_kategori');
    }
}
