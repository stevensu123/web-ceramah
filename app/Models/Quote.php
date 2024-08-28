<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = ['quote', 'author', 'translated_quote'];

    public function categories()
    {
        return $this->belongsToMany(Kategori::class, 'category_quote');
    }
}
