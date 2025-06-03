<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class jenis_tunjangan extends Model
{
    use HasFactory;

    protected $table = 'jenis_tunjangans';

    protected $fillable = [
        'nama_tunjangan',
        'maksimal_jumlah',
    ];
    
}
