<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waktu extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "jam_mulai",
        "jam_selesai",
        "status",
   
    ];


    public function cerita()
    {
        return $this->belongsToMany(Cerita::class, 'waktu_cerita');
    }

    public function ceritas()
    {
        return $this->belongsToMany(Cerita::class, 'waktu_cerita')
                    ->withPivot('id') // Jika Anda memiliki kolom tambahan di pivot table, tambahkan di sini
                    ->withTimestamps();
    }

}
