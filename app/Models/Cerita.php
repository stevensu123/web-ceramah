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
        "waktu_pagi",
        "nama_kategori_siang",
        "gambar_siang",
        "text_cerita_siang",
        "keterangan_siang",
        "waktu_siang",
        "nama_kategori_sore",
        "gambar_sore",
        "text_cerita_sore",
        "keterangan_sore",
        "waktu_sore",
        "status",
        "tanggal",
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

    public function waktuCeritas()
    {
        return $this->belongsToMany(Waktu::class, 'waktu_cerita');
    }

    public function waktuCeritass()
    {
        return $this->belongsToMany(Waktu::class, 'waktu_cerita')
                    ->withPivot('id') // Jika Anda memiliki kolom tambahan di pivot table, tambahkan di sini
                    ->withTimestamps();
    }

    public function quotes()
    {
        return $this->belongsToMany(Quote::class);
    }

    public function getKeteranganByWaktu($waktuKey)
    {
        $fields = [
            'pagi' => 'keterangan_pagi',
            'siang' => 'keterangan_siang',
            'sore' => 'keterangan_sore'
        ];

        return $this->{$fields[$waktuKey]} ?? 'Keterangan Tidak Ditemukan'; // Ganti dengan keterangan default jika tidak ada
    }
    
    public function getGambarByWaktu($waktuKey)
    {
        $fields = [
            'pagi' => 'gambar_pagi',
            'siang' => 'gambar_siang',
            'sore' => 'gambar_sore'
        ];

        return $this->{$fields[$waktuKey]} ?? 'default.jpg'; // Ganti dengan gambar default jika tidak ada
    }

    public function getNamaKategoriByWaktu($waktuKey)
    {
        $fields = [
            'pagi' => 'nama_kategori_pagi',
            'siang' => 'nama_kategori_siang',
            'sore' => 'nama_kategori_sore'
        ];

        return $this->{$fields[$waktuKey]} ?? 'Kategori Tidak Ditemukan';
    }

    public function getNamaKeteranganByWaktu($waktuKey)
    {
        $fields = [
            'pagi' => 'keterangan_pagi',
            'siang' => 'keterangan_siang',
            'sore' => 'keterangan_sore'
        ];

        return $this->{$fields[$waktuKey]} ?? 'Kategori Tidak Ditemukan';
    }

    public function getNamaTextCeritaByWaktu($waktuKey)
    {
        $fields = [
            'pagi' => 'text_cerita_pagi',
            'siang' => 'text_cerita_siang',
            'sore' => 'text_cerita_sore'
        ];

        return $this->{$fields[$waktuKey]} ?? 'Kategori Tidak Ditemukan';
    }



    // //mengambil waktucerita
    // public function waktuCeritas()
    // {
    //     return $this->belongsToMany(User::class, 'waktu_cerita')
    //                 ->withPivot('waktu_id');
    // }
}
