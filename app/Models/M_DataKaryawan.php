<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_DataKaryawan extends Model
{
    protected $table = 'data_karyawan';
    protected $fillable = [
        'user_id',
        'nama_karyawan',
        'email',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_perkawinan',
        'gol_darah',
        'agama',
        'jenis_identitas',
        'nik',
        'visa',
        'alamat_ktp',
        'alamat_domisili',
        'nip_karyawan',
        'status_karyawan',
        'tgl_masuk',
        'tgl_keluar',
        'entitas',
        'divisi',
        'jabatan',
        'posisi',
        'sistem_kerja',
        'spv',
        'total_upah',
        'gaji_pokok',
        'tunjangan_jabatan',
        'bonus',
        'jenis_penggajian',
        'nama_bank',
        'no_rek',
        'nama_pemilik_rekening',
        'no_bpjs_tk',
        'npp_bpjs_tk',
        'tgl_aktif_bpjstk',
        'no_bpjs',
        'anggota_bpjs',
        'tgl_aktif_bpjs',
        'penanggung',
    ];

    public function getJadwal()
    {
        return $this->hasMany(M_Jadwal::class, 'user_id', 'id_karyawan');
    }

    public function getDivisi()
    {
        return $this->belongsTo(M_Divisi::class, 'divisi'); // atau 'id_divisi' jika itu nama kolom foreign key-nya
    }

    public function getJabatan()
    {
        return $this->belongsTo(M_Jabatan::class, 'jabatan'); // atau 'id_divisi' jika itu nama kolom foreign key-nya
    }

    public function getPresensi()
    {
        return $this->hasMany(M_Presensi::class, 'user_id', 'user_id');
    }

    public function payrolls()
    {
        return $this->hasMany(PayrollModel::class, 'karyawan_id');
    }

    // public function jadwal()
    // {
    //     return $this->hasOne(M_Jadwal::class, 'id_karyawan', 'id');
    // }
}
