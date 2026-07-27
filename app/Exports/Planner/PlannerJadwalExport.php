<?php

namespace App\Exports\Planner;

use App\Models\M_DataKaryawan;
use App\Models\M_JadwalShift;
use App\Traits\CutoffPayrollTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PlannerJadwalExport implements
    FromArray,
    ShouldAutoSize
{
    use Exportable;
    use CutoffPayrollTrait;

    protected $bulan;

    public function __construct($bulan)
    {
        $this->bulan = $bulan;
    }

    public function array(): array
    {
        $carbon = Carbon::parse($this->bulan);

        $cutoff = $this->resolveCutoff(
            $carbon->year,
            $carbon->month,
            'cutoff_25'
        );

        $user = Auth::user();

        $selectedEntitas = session('selected_entitas', 'UHO');

        $login = M_DataKaryawan::where('user_id', $user->id)->first();

        $query = M_DataKaryawan::query()
            ->where('status_karyawan', '!=', 'NONAKTIF');

        /*
        |--------------------------------------------------------------------------
        | Filter Role
        |--------------------------------------------------------------------------
        */

        if ($user->hasAnyRole(['spv-teknisi', 'spv-helpdesk'])) {

            $query->where('divisi', $login->divisi);

            if ($login->entitas == 'UNR') {
                $query->whereIn('entitas', ['UNR', 'UHO']);
            } else {
                $query->where('entitas', $login->entitas);
            }
        } elseif ($user->hasRole('spv-sales')) {

            $query->whereIn('divisi', [
                $login->divisi,
                'Support'
            ]);

            if ($login->entitas == 'UNB') {
                $query->whereIn('entitas', ['UNB', 'UHO']);
            } else {
                $query->where('entitas', $login->entitas);
            }
        } elseif ($user->hasRole('branch-manager')) {

            $query->where('entitas', $login->entitas);
        } elseif ($user->hasAnyRole(['admin', 'hr'])) {

            $query->where('entitas', $selectedEntitas);
        } else {

            $query->where('entitas', $login->entitas);
        }

        $pegawai = $query
            ->orderBy('planner_order')
            ->orderBy('nama_karyawan')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Header Tanggal
        |--------------------------------------------------------------------------
        */

        $header1 = [
            'No',
            'Nama Karyawan'
        ];

        $header2 = [
            '',
            ''
        ];

        $tanggal = $cutoff['start']->copy();

        while ($tanggal <= $cutoff['end']) {

            $header1[] = $tanggal->day;

            $header2[] = $tanggal->translatedFormat('D');

            $tanggal->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Generate Excel
        |--------------------------------------------------------------------------
        */

        // $rows = [];

        // // Judul
        // $rows[] = ['TEMPLATE IMPORT PLANNER JADWAL'];

        // $rows[] = [];

        // // Petunjuk
        // $rows[] = ['PETUNJUK'];
        // $rows[] = ['1. Isi jadwal menggunakan KODE SHIFT.'];
        // $rows[] = ['2. Jangan mengubah Nama Karyawan.'];
        // $rows[] = ['3. Kosongkan sel jika tidak ada jadwal.'];

        // $rows[] = [];

        // // Daftar kode shift
        // $rows[] = ['DAFTAR KODE SHIFT'];

        // $rows[] = [
        //     'Kode',
        //     'Nama Shift'
        // ];

        // foreach (
        //     M_JadwalShift::orderBy('kode_shift')
        //         ->get() as $shift
        // ) {

        //     $rows[] = [
        //         $shift->kode_shift,
        //         $shift->nama_shift
        //     ];
        // }

        // $rows[] = [];
        // $rows[] = [];

        // Header tabel import
        $rows[] = $header1;
        $rows[] = $header2;

        $no = 1;

        foreach ($pegawai as $item) {

            $row = [
                $no++,
                $item->nama_karyawan
            ];

            $tanggal = $cutoff['start']->copy();

            while ($tanggal <= $cutoff['end']) {

                $row[] = '';

                $tanggal->addDay();
            }

            $rows[] = $row;
        }

        return $rows;
    }
}
