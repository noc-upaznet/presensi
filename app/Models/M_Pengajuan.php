<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class M_Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $fillable = [
        'karyawan_id',
        'shift_id',
        'tanggal',
        'keterangan',
        'file',
        'status',
    ];

    public function getShift()
    {
        return $this->belongsTo(M_JadwalShift::class, 'shift_id');
    }

    public function getJadwal()
    {
        return $this->hasOne(M_Jadwal::class, 'id_karyawan', 'karyawan_id');
    }

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

        // Hanya untuk user dengan role HR
        if (! $auth || ! $auth->hasRole('hr')) {
            return false;
        }

        // Cek status approval dan status pengajuan
        if ($this->approve_hr != 0 || $this->status != 0) {
            return false;
        }

        // Hanya jika pengaju punya role 'user' atau 'spv'
        if ($this->pengajuRole('user') || $this->pengajuRole('spv')) {
            return true;
        }

        return false;
    }

    public function canBeApprovedByAdmin(): bool
    {
        $auth = auth()->user();

        // Hanya untuk admin
        if (! $auth || ! $auth->hasRole('admin')) {
            return false;
        }

        // Hanya kalau pengaju punya role HR
        if (! $this->pengajuRole('hr')) {
            return false;
        }

        // Hanya kalau belum diapprove admin
        return is_null($this->approve_admin);
    }

    public function canBeDeletedByAdmin()
    {
        return Auth::user()->current_role === 'admin'
            && $this->status == 0;
    }
}
