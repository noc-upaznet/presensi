<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class M_Lembur extends Model
{
    protected $table = 'lembur';
    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jenis',
        'waktu_mulai',
        'waktu_akhir',
        'total_jam',
        'keterangan',
        'file_bukti',
        'status',
    ];

    public function getUSer()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function getJadwal()
    // {
    //     return $this->hasOne(M_Jadwal::class, 'id_karyawan', 'karyawan_id');
    // }
    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }

    public function pengajuRole()
    {
        return optional(optional($this->getKaryawan)->user)->current_role;
    }

    public function canBeApprovedBySpv()
    {
        $pengajuRole = optional(optional($this->getKaryawan)->user)->current_role;
        return Auth::user()->current_role === 'spv'
            && $this->status == 0
            // && in_array($pengajuRole, ['user']);
            && in_array($this->pengajuRole(), ['user'])
            && $this->approve_spv == 0;
    }

    public function canBeApprovedByHr()
    {
        return Auth::user()->current_role === 'hr'
            && $this->approve_hr == 0
            && $this->status == 0
            && in_array($this->pengajuRole(), ['user', 'spv']);
    }

    public function canBeApprovedByAdmin()
    {
        return Auth::user()->current_role === 'admin'
            && $this->status == 0
            && in_array($this->pengajuRole(), ['hr', 'admin']);
    }

    public function canBeDeletedByAdmin()
    {
        return Auth::user()->current_role === 'admin'
            && $this->status == 0;
    }
}
