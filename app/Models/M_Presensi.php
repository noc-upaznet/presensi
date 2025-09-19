<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Presensi extends Model
{
    protected $table = 'presensi';
    protected $fillable = [
        'user_id',
        'tanggal',
        'clock_in',
        'clock_out',
        'lokasi',
        'lokasi_clock_out',
        'lokasi_lock',
        'file',
        'status',
    ];

    protected $casts = [
        'lokasi_presensi' => 'array',
    ];

    public function getLokasisAttribute()
    {
        return RoleLokasiModel::whereIn('id', $this->lokasi_presensi ?? [])->get();
    }

    public function getKaryawan()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'user_id', 'id');
    }

    public function getUser()
    {
        return $this->belongsTo(M_DataKaryawan::class, 'user_id', 'id');
    }

    public function getLokasis()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi');
    }

    // accessor untuk lokasi_final
    public function getLokasiFinalAttribute()
    {
        // ambil data lokasi_lock
        if ($this->lokasi_lock == 1) {
            // jika berupa ID lokasis
            $id = json_decode($this->lokasi, true);
            // dd($id);
            if (is_array($id) && isset($id[0])) {
                return Lokasi::find($id[0])->nama_lokasi ?? 'Tidak Diketahui';
            }
        } else {
            // jika berupa koordinat
            return json_decode($this->lokasi, true)[0] ?? null;
        }
    }

    public function getLokasiClockOutFinalAttribute()
    {
        $val = $this->lokasi_clock_out;

        if (!$val) {
            return null;
        }

        // Jika value hanya angka (id lokasi)
        if (is_numeric($val)) {
            return Lokasi::find($val)->nama_lokasi ?? 'Tidak Diketahui';
        }

        // Kalau bentuknya JSON array (contoh: "[3]")
        if (str_starts_with($val, '[')) {
            $decoded = json_decode($val, true);
            if (is_array($decoded) && isset($decoded[0]) && is_numeric($decoded[0])) {
                return Lokasi::find($decoded[0])->nama_lokasi ?? 'Tidak Diketahui';
            }
        }

        // Selain itu, anggap koordinat (langsung tampilkan)
        return $val;
    }
}
