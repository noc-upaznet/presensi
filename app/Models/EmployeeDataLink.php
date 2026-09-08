<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDataLink extends Model
{
    protected $table = 'employee_data_links';

    protected $fillable = [
        'karyawan_id',
        'token',
        'expires_at',
        'last_accessed_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function karyawan()
    {
        return $this->belongsTo(
            M_DataKaryawan::class,
            'karyawan_id'
        );
    }
}
