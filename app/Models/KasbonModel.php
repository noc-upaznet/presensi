<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasbonModel extends Model
{
    protected $table = 'kasbons';

    protected $fillable = [
        'karyawan_id',
        'total_kasbon',
        'kasbon_perbulan',
        'sisa_kasbon',
        'jumlah_angsuran',
        'angsuran_ke',
        'tanggal_kasbon',
        'mulai_potong',
        'tanggal_lunas',
        'status',
        'keterangan',
        'approved_by',
    ];

    public function detail()
    {
        return $this->hasMany(KasbonDetails::class, 'kasbon_id');
    }

    public function karyawan()
    {
        return $this->belongsTo(M_DataKaryawan::class);
    }
}
