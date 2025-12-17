<?php

namespace App\Exports;

use App\Models\M_DataKaryawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataKaryawanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $entitas;

    public function __construct($entitas)
    {
        $this->entitas = $entitas;
    }

    public function collection()
    {
        return M_DataKaryawan::where('entitas', $this->entitas)->get();
    }

    public function headings(): array
    {
        return [
            'Nama Karyawan',
            'Email',
            'No HP',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Status Perkawinan',
            'Agama',
            'Jenis Identitas',
            'NIK',
            'Alamat KTP',
            'Alamat Domisili',
            'NIP Karyawan',
            'Status Karyawan',
            'Tanggal Masuk',
            'Tanggal Keluar',
            'Entitas',
            'Divisi',
            'Jabatan',
            'Level',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nama_karyawan,
            $row->email,
            $row->no_hp,
            $row->tempat_lahir,
            $row->tanggal_lahir,
            $row->jenis_kelamin,
            $row->status_perkawinan,
            $row->agama,
            $row->jenis_identitas,
            $row->nik,
            $row->alamat_ktp,
            $row->alamat_domisili,
            $row->nip_karyawan,
            $row->status_karyawan,
            $row->tgl_masuk,
            $row->tgl_keluar,
            $row->entitas,
            $row->divisi,
            $row->jabatan,
            $row->level,
        ];
    }
}
