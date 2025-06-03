<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class jenis_potongan extends Model
{
    use HasFactory;
    protected $table = 'jenis_potongans';
    protected $fillable = [
        'nama_potongan',
        'maksimal_jumlah',
    ];
}
