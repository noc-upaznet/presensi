<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JenisPotonganModel extends Model
{
    use HasFactory;
    protected $table = 'jenis_potongans';
    protected $fillable = [
        'nama_potongan',
        'deskripsi',
    ];
}
