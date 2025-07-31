<?php

namespace App\Imports;

use App\Models\M_DataKaryawan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KaryawanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);    
        // Cek apakah user dengan email ini sudah ada
        $user = User::where('email', $row['email'])->count();
        if ($user < 1) {
            $user = User::create([
                'name' => $row['nama_karyawan'],
                'email' => $row['email'],
                'password' => Hash::make('12345678'),
                'current_role' => strtolower($row['level']) === 'staff' ? 'user' : strtolower($row['level']),
                'password_expired' => 1,
            ]);
            // dd('User created: ' . $user);
        }

        // Simpan ke tabel data_karyawan dan hubungkan dengan user
        return new M_DataKaryawan([
            'user_id' => $user->id,
            'nama_karyawan' => $row['nama_karyawan'],
            'email' => $row['email'],
            'no_hp' => $row['no_hp'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $this->convertExcelDate($row['tanggal_lahir']),
            'jenis_kelamin' => $row['jenis_kelamin'],
            'status_perkawinan' => $row['status_perkawinan'],
            'gol_darah' => $row['gol_darah'],
            'agama' => $row['agama'],
            'jenis_identitas' => $row['jenis_identitas'],
            'nik' => $row['nik'],
            'visa' => $row['visa'],
            'alamat_ktp' => $row['alamat_ktp'],
            'alamat_domisili' => $row['alamat_domisili'],
            'nip_karyawan' => $row['nip_karyawan'],
            'status_karyawan' => $row['status_karyawan'],
            'tgl_masuk' => $this->convertExcelDate($row['tgl_masuk']),
            'tgl_keluar' => $this->convertExcelDate($row['tgl_keluar']),
            'entitas' => $row['entitas'],
            'divisi' => $row['divisi'],
            'jabatan' => $row['jabatan'],
            'level' => $row['level'],
            'sistem_kerja' => $row['sistem_kerja'],
            'spv' => $row['spv'],
            'total_upah' => $row['total_upah'],
            'gaji_pokok' => $row['gaji_pokok'],
            'tunjangan_jabatan' => $row['tunjangan_jabatan'],
            'bonus' => $row['bonus'],
            'jenis_penggajian' => $row['jenis_penggajian'],
            'nama_bank' => $row['nama_bank'],
            'no_rek' => $row['no_rek'],
            'nama_pemilik_rekening' => $row['nama_pemilik_rekening'],
            'no_bpjs_tk' => $row['no_bpjs_tk'],
            'npp_bpjs_tk' => $row['npp_bpjs_tk'],
            'tgl_aktif_bpjstk' => $this->convertExcelDate($row['tgl_aktif_bpjstk']),
            'no_bpjs' => $row['no_bpjs'],
            'anggota_bpjs' => $row['anggota_bpjs'],
            'tgl_aktif_bpjs' => $this->convertExcelDate($row['tgl_aktif_bpjs']),
            'penanggung' => $row['penanggung'],
        ]);
    }

    private function convertExcelDate($value)
    {
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value)->format('Y-m-d');
            }
            return $value;
        } catch (\Exception $e) {
            return null;
        }
    }
}
