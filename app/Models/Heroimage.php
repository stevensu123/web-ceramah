<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heroimage extends Model
{
    use HasFactory;

    protected $fillable = ['', 'image_path_background', 'image_path_object'];
}
