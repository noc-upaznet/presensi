<?php

namespace App\Models;

use App\Livewire\Karyawan\JadwalShift;
use Illuminate\Database\Eloquent\Model;

class M_TemplateWeek extends Model
{
    protected $table = 'template_week';
    protected $fillable = [
        'nama_template',
        'minggu',
        'senin',
        'selasa',
        'rabu',
        'kamis',
        'jumat',
        'sabtu',
    ];

    public function getMinggu()
    {
        return $this->belongsTo(M_JadwalShift::class, 'minggu');
    }
    public function getSenin()
    {
        return $this->belongsTo(M_JadwalShift::class, 'senin');
    }
    public function getSelasa()
    {
        return $this->belongsTo(M_JadwalShift::class, 'selasa');
    }
    public function getRabu()
    {
        return $this->belongsTo(M_JadwalShift::class, 'rabu');
    }
    public function getKamis()
    {
        return $this->belongsTo(M_JadwalShift::class, 'kamis');
    }
    public function getJumat()
    {
        return $this->belongsTo(M_JadwalShift::class, 'jumat');
    }
    public function getSabtu()
    {
        return $this->belongsTo(M_JadwalShift::class, 'sabtu');
    }
}
