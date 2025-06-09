<?php

namespace App\Imports;

use App\Models\Payroll;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PayrollImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (DB::table('payrolls')->where('no_gaji', $row['no_gaji'])->exists()) {
            return null;
        }
        return new Payroll([
            'no_gaji' => $row['no_gaji'],
            'nama'    => $row['nama'],
            'divisi'  => $row['divisi'],
            'bulan'   => $row['bulan'],
            'tahun'   => $row['tahun'],
            'total'   => $row['total'],
            'kasbon'  => 0, // Default
            'status'  => 'On Process', // Default
        ]);
    }
}
