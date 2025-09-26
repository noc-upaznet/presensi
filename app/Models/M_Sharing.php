<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Sharing extends Model
{
    protected $table = 'sharing';
    protected $fillable = [
        'karyawan_id',
        'date',
        'description',
        'file',
        'status',
        'approve_hr'
    ];

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'karyawan_id');
    }

    public function pengajuRole(string $role): bool
    {
        return optional(optional($this->getKaryawan)->user)->hasRole($role) ?? false;
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
        return $this->pengajuRole('user') || $this->pengajuRole('spv') || $this->pengajuRole('branch-manager') || $this->pengajuRole('hr');
    }
}
