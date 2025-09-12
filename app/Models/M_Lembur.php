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

    public function pengajuRole(string $role): bool
    {
        return optional(optional($this->getKaryawan)->user)->hasRole($role) ?? false;
    }

    public function canBeApprovedBySpv(): bool
    {
        $auth = auth()->user();

        // Hanya untuk SPV
        if (! $auth || ! $auth->hasRole('spv')) {
            return false;
        }

        // Cek status pengajuan dan approve_spv
        if ($this->status != 0 || $this->approve_spv != 0) {
            return false;
        }

        // Hanya jika pengaju punya role 'user'
        return $this->pengajuRole('user');
    }

    public function canBeApprovedByHr(): bool
    {
        $auth = auth()->user();

        // Hanya untuk HR
        if (! $auth || ! $auth->hasRole('hr')) {
            return false;
        }

        // Cek status approval dan status pengajuan
        if ($this->approve_hr != 0 || $this->status != 0) {
            return false;
        }

        // Pengaju harus user atau spv
        return $this->pengajuRole('user') || $this->pengajuRole('spv');
    }

    public function canBeApprovedByAdmin(): bool
    {
        $auth = auth()->user();

        // Hanya untuk Admin
        if (! $auth || ! $auth->hasRole('admin')) {
            return false;
        }

        // Cek status pengajuan
        if ($this->status != 0) {
            return false;
        }

        // Pengaju harus HR atau Admin
        return $this->pengajuRole('hr') || $this->pengajuRole('admin');
    }


    public function canBeDeletedByAdmin()
    {
        return Auth::user()->current_role === 'admin'
            && $this->status == 0;
    }
}
